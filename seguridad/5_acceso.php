<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
  header("Location: ../validacion.php?opcion=val");
  exit();
}

$acceso = 8;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();">
  <div align="center" class="TituloP">Verificar Ingreso</div>
  <br>

  <diw class="row ml-3">
    <table>
      <tr valign="middle">
        <td>
          <div class="form-check">
            <strong>
              <input id="txt_horario" name="txt_horario" type="checkbox" class="switch_new" value="1" /><label for="txt_horario" class="lbl_switch"></label>
          </div>
        </td>
        <td valign="middle">
          <div class="form-check">
            <input placeholder="Escanear..." name="ocedula" id="ocedula" type="text" size="15" class="form-control" onkeyup="verificar(event,this.value)" onFocus="this.select()" style="text-decoration-color: aqua" />
          </div>
        </td>

        <td>
          <div class="form-check">
            <input placeholder="Consultar aqui..." name="obuscar" id="obuscar" type="text" size="40" class="form-control" onkeyup="buscar(event)" onFocus="this.select()" />
          </div>
        </td>
        <td>
          <div class="form-check">
            <strong>
              <div id="personas"></div>
            </strong>
          </div>
        </td>
      </tr>
    </table>
  </diw>
  <br>
  <div id="div1"></div>
</form>
<script language="JavaScript">
  setTimeout(function() {
    $('#ocedula').focus();
    listar_tabla();
    personas();
  }, 500); //document.form1.ocedula.focus;
  //----------------------------
  function ingresob(id) {
    Swal.fire({
      title: '¿Desea darle Ingreso a algun Bien Nacional?',
      icon: 'question',
      showDenyButton: true,
      input: 'text',
      inputPlaceholder: 'Ingrese el número de Bien Nacional',
      inputAttributes: {
        maxlength: 8,
        autocapitalize: 'off'
      },
      showCancelButton: false,
      //confirmButtonText: 'INGRESO',
      denyButtonText: `Listar Bienes`,
    }).then((result) => {
      if (result.isConfirmed) {
        var parametros = "id=" + id;
        $.ajax({
          url: "seguridad/5e_ingreso.php?id=" + id + "&bien=" + result.value,
          dataType: "json",
          type: "POST",
          data: parametros,
          success: function(data) {
            if (data.tipo == 'error') {
              Swal.fire(data.msg, '', data.tipo)
            } else {
              alertify.success(data.msg);
            }
            //--------------
            document.form1.ocedula.value = '';
            document.form1.ocedula.focus;
            //			listar_tabla(); personas();
          }
        });
      } else if (result.isDenied) {
        tabla_bienes(id);
        $('#modal_largo').modal('show');
      }
    })

  }
  //----------------------------
  function salidab(id) {
    Swal.fire({
      title: '¿Desea darle Salida a algun Bien Nacional?',
      icon: 'question',
      //	text: "¿Desea darle Salida a algun Bien Nacional?",				
      showDenyButton: true,
      input: 'text',
      inputPlaceholder: 'Ingrese el número de Bien Nacional',
      inputAttributes: {
        maxlength: 8,
        autocapitalize: 'off'
      },
      showCancelButton: false,
      //confirmButtonText: 'INGRESO',
      denyButtonText: `Listar Bienes`,
    }).then((result) => {
      if (result.isConfirmed) {
        var parametros = "id=" + id;
        $.ajax({
          url: "seguridad/5e_prestamo.php?id=" + id + "&bien=" + result.value,
          dataType: "json",
          type: "POST",
          data: parametros,
          success: function(data) {
            if (data.tipo == 'error') {
              Swal.fire(data.msg, '', data.tipo)
            } else {
              alertify.success(data.msg);
            }
            //--------------
            document.form1.ocedula.value = '';
            document.form1.ocedula.focus;
            //			listar_tabla(); personas();
          }
        });
      } else if (result.isDenied) {
        tabla_bienes(id);
        $('#modal_largo').modal('show');
      }
    })

  }
  //-----------------------
  function ingresados() {
    $('#modal_lg').load('seguridad/5i_tabla.php');
  }
  //-----------------------
  function personas() {
    $('#personas').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Cargando funcionarios presentes...</strong></div>');
    $('#personas').load('seguridad/5d_personas.php');
  }
  //-----------------------
  function tabla_bienes(id) {
    $('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
    $('#modal_lg').load('seguridad/5f_tabla.php?id=' + id);
  }
  //-----------------------
  function ver_observacion(id) {
    Swal.fire({
      //		  title: 'Informacion!',
      icon: 'info',
      text: id,
      timer: 5500,
      //		  timerProgressBar: true,				
      showDenyButton: false,
      showCancelButton: false
    })
  }
  //-----------------------
  function motivo(id) {
    $('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
    $('#modal_n').load('seguridad/5c_modal.php?id=' + id);
  }
  //---------------------
  function borrarb(id, idbien, ida) {
    Swal.fire({
      title: 'Estas seguro de eliminar el Registro?',
      text: "Esta acción no se puede revertir!",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, borrar!',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        //-----------------------
        var parametros = "id=" + id + "&idb=" + idbien;
        $.ajax({
          url: "seguridad/5h_eliminar.php",
          type: "POST",
          data: parametros,
          success: function(r) {
            //Swal.fire('Borrado!', 'El registro fue borrado.', 'success');
            alertify.success('El registro fue borrado con Exito!');
            tabla_bienes(ida);
          }
        });
        //-----------------------
      }
    })
  }
  //---------------------
  function borrar(id) {
    Swal.fire({
      title: 'Estas seguro de eliminar el Registro?',
      text: "Esta acción no se puede revertir!",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, borrar!',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $('#ocedula').focus();
        //-----------------------
        var parametros = "id=" + id;
        $.ajax({
          url: "seguridad/5g_eliminar.php",
          type: "POST",
          data: parametros,
          success: function(r) {
            //Swal.fire('Borrado!', 'El registro fue borrado.', 'success');
            alertify.success('El registro fue borrado con Exito!');
            listar_tabla();
            personas();
            $('#ocedula').focus();
          }
        });
        //-----------------------
      }
    })
  }
  //---------------------
  function buscar(e) {
    (e.keyCode) ? k = e.keyCode: k = e.which;
    // Si la tecla pulsada es enter (codigo ascii 13)
    if (k == 13) {
      listar_tabla();
    }
  }
  //--------------------- PARA BUSCAR
  function listar_tabla() {
    $('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
    $('#div1').load('seguridad/5a_tabla.php?buscar=' + cambia(document.form1.obuscar.value));
  }
  //----------------------------
  function verificar(e, id) {
    var procesar = 0;
    (e.keyCode) ? k = e.keyCode: k = e.which;
    // Si la tecla pulsada es enter (codigo ascii 13)
    if (k == 13 && id != '') {
      //		document.form1.ocedula.value='';
      //		document.form1.ocedula.focus;
      if (document.form1.txt_horario.checked) {
        Swal.fire({
          imageUrl: "personal/funcionarios/" + document.form1.ocedula.value + "_0.jpg",
          imageWidth: 150,
          imageHeight: 200,
          //			imageAlt: "Custom image",
          title: '¿Cual Horario utilizar?',
          //		  icon: 'question',				
          //		  text: "Tipo de Registro?",				
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'MAÑANA',
          denyButtonText: 'TARDE',
        }).then((result) => {
          if (result.isConfirmed) {
            procesar = 1;
          } else if (result.isDenied) {
            procesar = 2;
          }
          if (procesar > 0) {
            var parametros = "id=" + id;
            $.ajax({
              url: "seguridad/5b_revisar.php?tipo=" + procesar,
              dataType: "json",
              type: "POST",
              data: parametros,
              success: function(data) {
                const icon = (data.tipo === 'danger') ? 'error' : (data.tipo || 'success');
                Swal.fire({
                  toast: true,
                  position: data.pos || 'bottom-end',
                  icon: icon,
                  title: data.msg || '',
                  showConfirmButton: false,
                  timer: data.timer || 5000,
                  timerProgressBar: true
                });

                //--------------
                document.form1.ocedula.value = '';
                document.form1.ocedula.focus;
                listar_tabla();
                personas();
              }
            });
          }
        })
      } else {
        var parametros = "id=" + id;
        $.ajax({
          url: "seguridad/5b_revisar.php",
          dataType: "json",
          type: "POST",
          data: parametros,
          success: function(data) {
            const icon = (data.tipo === 'danger') ? 'error' : (data.tipo || 'success');
            Swal.fire({
              toast: true,
              position: data.pos || 'bottom-end',
              icon: icon,
              title: data.msg || '',
              showConfirmButton: false,
              timer: data.timer || 5000,
              timerProgressBar: true
            });

            //--------------
            document.form1.ocedula.value = '';
            document.form1.ocedula.focus;
            listar_tabla();
            personas();
          }
        });
      }
    }
  }
</script>