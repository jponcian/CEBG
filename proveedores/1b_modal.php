<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
  header("Location: ../validacion.php?opcion=val");
  exit();
}

$acceso = 48;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$registro = (object) [
  'id' => 0,
  'rif' => '',
  'nombre' => '',
  'domicilio' => '',
  'estado' => '',
  'ciudad' => '',
  'representante' => '',
  'ced_representante' => '',
  'cel_contacto' => '',
  'email' => ''
];
if ($id > 0) {
  $consultx = "SELECT * FROM contribuyente WHERE id = $id";
  $tablx = $_SESSION['conexionsql']->query($consultx);
  if ($tablx) {
    $registro = $tablx->fetch_object();
  }
}
?>

<style>
  /* Modal ligeramente más ancho que el estándar */
  #modal_normal .modal-dialog {
    max-width: 700px !important;
    width: auto !important;
  }

  /* Evitar compresión horizontal */
  #modal_normal .modal-body {
    overflow-x: auto;
  }

  /* Asegurar que los input-groups ocupen todo el ancho disponible */
  #modal_normal .input-group,
  #modal_normal .form-group {
    width: 100%;
  }

  #modal_normal .form-row .form-group {
    margin-bottom: 0.75rem;
  }
</style>

<form id="formProveedor" name="formProveedor" method="post">
  <div class="modal-header bg-fondo text-center">
    <h4 class="modal-title w-100 font-weight-bold py-2" style="background-color:#0275d8; color:#FFFFFF;">
      <?php echo ($id > 0 ? 'Datos del Proveedor a Modificar' : 'Datos del Proveedor a Incluir'); ?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="white-text">&times;</span></button>
  </div>

  <div class="modal-body bg-white">
    <input type="hidden" id="oid" name="oid" value="<?php echo $id; ?>" />

    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="far fa-credit-card"></i></div>
        </div>
        <input type="text" value="<?php echo htmlspecialchars($registro->rif); ?>" class="form-control" name="rif" id="rif" placeholder="RIF (J-12345678-9)" minlength="10" maxlength="12" required>
      </div>
      <div id="rifHelp" class="invalid-feedback">RIF inválido</div>
    </div>

    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fas fa-file-signature"></i></div>
        </div>
        <input type="text" value="<?php echo htmlspecialchars($registro->nombre); ?>" class="form-control" name="nombre" id="nombre" placeholder="Nombre o Razón Social" minlength="4" maxlength="150" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-grip-horizontal"></i></div>
          </div>
          <select class="form-control" name="estado" id="estado" required></select>
        </div>
      </div>
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="far fa-building"></i></div>
          </div>
          <select class="form-control" name="ciudad" id="ciudad" required></select>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
        </div>
        <input type="text" value="<?php echo htmlspecialchars($registro->domicilio); ?>" class="form-control" name="direccion" id="direccion" placeholder="Dirección o domicilio" minlength="4" maxlength="150" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-user-tag"></i></div>
          </div>
          <input type="text" value="<?php echo htmlspecialchars($registro->representante); ?>" class="form-control" name="representante" id="representante" placeholder="Nombre del Representante" minlength="3" maxlength="150" required>
        </div>
      </div>
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="far fa-id-card"></i></div>
          </div>
          <input type="text" value="<?php echo htmlspecialchars($registro->ced_representante); ?>" class="form-control" name="cedula" id="cedula" placeholder="Cédula Representante (V-12345678)" minlength="4" maxlength="10" required>
          <div id="cedulaHelp" class="invalid-feedback">Cédula inválida</div>
        </div>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-phone"></i></div>
          </div>
          <input type="text" value="<?php echo htmlspecialchars($registro->cel_contacto); ?>" class="form-control" name="celular" id="celular" placeholder="Cel Contacto" minlength="7" maxlength="12" required>
        </div>
      </div>
      <div class="form-group col-sm-6">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="far fa-envelope-open"></i></div>
          </div>
          <input type="email" value="<?php echo htmlspecialchars($registro->email); ?>" class="form-control" name="correo" id="correo" placeholder="Correo Electrónico" maxlength="120" required>
        </div>
      </div>
    </div>
  </div>

  <div class="modal-footer justify-content-center">
    <button type="button" id="btnGuardarProveedor" class="btn btn-outline-success waves-effect" onclick="guardarProveedor()"><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
  </div>
</form>

<script>
  function cargarEstadosYCiudades() {
    $.ajax({
      type: 'POST',
      url: 'proveedores/zonificacion_listar.php',
      contentType: 'application/json',
      dataType: 'json',
      data: JSON.stringify({
        tabla: {
          nombre: 'estado',
          estado: 0
        }
      }),
      success: function(resp) {
        var $estado = $('#estado');
        $estado.empty();
        $.each(resp.zonificacion || [], function(_, est) {
          $estado.append('<option value="' + est.id + '">' + est.descripcion + '</option>');
        });
        <?php if (!empty($registro->estado)) { ?>
          $estado.val('<?php echo $registro->estado; ?>');
        <?php } ?>
        listarCiudades();
      }
    });
  }

  function listarCiudades() {
    var estado = $('#estado').val();
    $.ajax({
      type: 'POST',
      url: 'proveedores/zonificacion_listar.php',
      contentType: 'application/json',
      dataType: 'json',
      data: JSON.stringify({
        tabla: {
          nombre: 'ciudad',
          estado: estado
        }
      }),
      success: function(resp) {
        var $ciudad = $('#ciudad');
        $ciudad.empty();
        $.each(resp.zonificacion || [], function(_, ci) {
          $ciudad.append('<option value="' + ci.id + '">' + ci.descripcion + '</option>');
        });
        <?php if (!empty($registro->ciudad)) { ?>
          $ciudad.val('<?php echo $registro->ciudad; ?>');
        <?php } ?>
      }
    });
  }
  $('#estado').on('change', listarCiudades);
  setTimeout(cargarEstadosYCiudades, 200);

  function normalizarId(str) {
    return (str || '').toUpperCase().replace(/[^A-Z0-9]/g, '');
  }

  function marcarValido($el, helpId) {
    $el.removeClass('is-invalid').addClass('is-valid');
    if (helpId) {
      $('#' + helpId).text('');
    }
  }

  function marcarInvalido($el, helpId, msg) {
    $el.removeClass('is-valid').addClass('is-invalid');
    if (helpId) {
      $('#' + helpId).text(msg || 'Dato inválido');
    }
  }

  function validarRifFormato() {
    var rif = $('#rif').val().trim();
    var rifRe = /^[VEJPG]-\d{8}-\d$/i; // Ejemplo: J-12345678-9
    if (!rifRe.test(rif)) {
      marcarInvalido($('#rif'), 'rifHelp', 'Formato de RIF inválido. Ej: J-12345678-9');
      return false;
    }
    marcarValido($('#rif'), 'rifHelp');
    return true;
  }

  function validarCedulaFormato() {
    var cedula = $('#cedula').val().trim();
    var cedRe = /^[VEJP]-?\d{7,8}$/i; // Ejemplo: V-12345678
    if (!cedRe.test(cedula)) {
      marcarInvalido($('#cedula'), 'cedulaHelp', 'Formato de Cédula inválido. Ej: V-12345678');
      return false;
    }
    marcarValido($('#cedula'), 'cedulaHelp');
    return true;
  }

  function actualizarEstadoGuardar() {
    var inval = $('#formProveedor .is-invalid').length > 0;
    $('#btnGuardarProveedor').prop('disabled', inval);
  }

  function guardarProveedor() {
    // Validación inline
    var okR = validarRifFormato();
    var okC = validarCedulaFormato();
    if (!okR || !okC) {
      actualizarEstadoGuardar();
      $('#formProveedor .is-invalid:first').focus();
      return;
    }
    var rif = $('#rif').val().trim();
    var cedula = $('#cedula').val().trim();
    // Armar datos asegurando que RIF y Cédula viajen sin guiones al servidor
    var form = $('#formProveedor').serializeArray();
    var dataObj = {};
    form.forEach(function(p) {
      dataObj[p.name] = p.value;
    });
    dataObj.rif = normalizarId(rif);
    dataObj.cedula = normalizarId(cedula);
    var datos = $.param(dataObj);
    $('#btnGuardarProveedor').attr('disabled', true);
    $.ajax({
      type: 'POST',
      url: 'proveedores/1c_guardar.php',
      dataType: 'json',
      data: datos,
      success: function(data) {
        var icono = (data.tipo === 'error') ? 'error' : (data.tipo === 'alerta' ? 'warning' : 'success');
        Swal.fire({
          toast: true,
          position: 'bottom-end',
          icon: icono,
          title: data.msg,
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true
        });
        if (data.tipo === 'info') {
          $('#modal_normal .close').click();
          if (window.$ && $('#obuscar').length) {
            $('#obuscar').val('');
          }
          buscar();
        }
        $('#btnGuardarProveedor').attr('disabled', false);
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error de red'
        });
        $('#btnGuardarProveedor').attr('disabled', false);
      }
    });
  }

  // Verificación de RIF duplicado
  $('#rif').on('change', function() {
    var rif = $(this).val().trim();
    if (!validarRifFormato()) {
      actualizarEstadoGuardar();
      return;
    }
    $.ajax({
      type: 'GET',
      url: 'proveedores/buscar_rif.php',
      dataType: 'json',
      data: {
        rif: rif.replace(/[^A-Za-z0-9]/g, '').toUpperCase()
      },
      success: function(resp) {
        var idEncontrado = parseInt(resp.id || '0', 10);
        var idActual = parseInt($('#oid').val() || '0', 10);
        if (idEncontrado > 0 && idEncontrado !== idActual) {
          marcarInvalido($('#rif'), 'rifHelp', 'RIF ya registrado');
        } else {
          marcarValido($('#rif'), 'rifHelp');
        }
        actualizarEstadoGuardar();
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error verificando RIF'
        });
      }
    });
  });
  // Rehabilitar guardar mientras el usuario edita si borra el RIF
  $('#rif').on('input', function() {
    validarRifFormato();
    actualizarEstadoGuardar();
  });

  // Validación inline de cédula
  $('#cedula').on('input change', function() {
    validarCedulaFormato();
    actualizarEstadoGuardar();
  });

  // Ajustes y máscaras si el plugin está disponible
  $(function() {
    // No forzamos modal-xl; el CSS ya amplía ligeramente el ancho
    try {
      if ($.fn.mask) {
        // Si vienen guardados sin guiones, formatear para mostrar con máscara
        var vr = $('#rif').val();
        var vc = $('#cedula').val();
        var mr = vr.replace(/[^A-Za-z0-9]/g, '');
        var mc = vc.replace(/[^A-Za-z0-9]/g, '');
        if (/^[A-Za-z][0-9]{9}$/.test(mr)) {
          $('#rif').val(mr.substring(0, 1).toUpperCase() + '-' + mr.substring(1, 9) + '-' + mr.substring(9));
        }
        if (/^[A-Za-z][0-9]{7,8}$/.test(mc) && mc.indexOf('-') === -1) {
          // Si es de 7 dígitos: se mostrará como a-9999999 (último opcional)
          var l = mc.length;
          $('#cedula').val(mc.substring(0, 1).toUpperCase() + '-' + mc.substring(1, l));
        }
        // Definir máscaras restringiendo el primer carácter
        var isDigitalBush = !!($.mask && $.mask.definitions);
        if (isDigitalBush) {
          // Plugin digitalBush: se usan definitions personalizadas
          $.mask.definitions['R'] = '[VvEeJjGgPp]';
          $.mask.definitions['C'] = '[VvEeJjPp]';
          // RIF: R-99999999-9  (R = V/E/J/G/P)
          $('#rif').mask('R-99999999-9');
          // Cédula: C-9999999?9 (C = V/E/J/P)
          $('#cedula').mask('C-9999999?9');
        } else {
          // Plugin igorescobar: usar translation por campo
          $('#rif').mask('R-99999999-9', {
            translation: {
              'R': {
                pattern: /[VEJGPvejpg]/
              }
            }
          });
          $('#cedula').mask('C-9999999?9', {
            translation: {
              'C': {
                pattern: /[VEJPvejp]/
              }
            }
          });
        }
        // Celular: formateo opcional (ej: 0412-1234567)
        // $('#celular').mask('9999-9999999');
        // Forzar mayúscula en el primer carácter mientras escribe
        $('#rif, #cedula').on('input', function() {
          var v = $(this).val();
          if (v && v.length > 0) {
            $(this).val(v.charAt(0).toUpperCase() + v.slice(1));
          }
        });
      }
    } catch (e) {
      /* no-op */
    }
  });
</script>