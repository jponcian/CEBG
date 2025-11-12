<html>

<head>
  <script src="seguridad/jsQR.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Ropa+Sans" rel="stylesheet">
  <style>
    body {
      font-family: 'Ropa Sans', sans-serif;
      color: #333;
      max-width: 100vw;
      margin: 0;
      padding: 0 8px;
      position: relative;
    }

    #githubLink {
      position: absolute;
      right: 0;
      top: 12px;
      color: #2D99FF;
    }

    h1 {
      margin: 10px 0;
      font-size: 32px;
    }

    #loadingMessage {
      text-align: center;
      padding: 24px 8px;
    }

    #canvas {
      width: 100%;
      max-width: 100vw;
      height: auto;
      display: block;
      margin: 0 auto;
    }

    #output {
      margin-top: 16px;
      padding: 8px;
      padding-bottom: 0;
    }

    #output div {
      padding-bottom: 8px;
      word-wrap: break-word;
    }

    /* Estilos para el bot�n */
    #btnCamara {
      background: #28a745;
      color: #fff;
      border: none;
      padding: 12px 16px;
      border-radius: 5px;
      font-size: 18px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 16px auto;
      width: 90vw;
      max-width: 320px;
    }

    #btnCamara i {
      margin-right: 8px;
    }

    #div1 {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }
  </style>
</head>

<body class="bg-light">
  <div class="container py-3">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8">
        <!-- Bot�n con formato e �cono de c�mara -->
        <button id="btnCamara" onclick="activarCamara()" class="btn btn-success btn-lg w-100 mb-3 d-flex align-items-center justify-content-center">
          <i class="fas fa-camera me-2"></i> Activar Cámara
        </button>
        <canvas id="canvas" hidden class="w-100 mb-3"></canvas>
        <div id="loadingMessage" class="text-center mb-3"></div>
        <div id="output" hidden class="mb-3">
          <div id="outputData"></div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-12 col-md-8">
        <div id="div1" class="d-flex flex-column align-items-center"></div>
      </div>
    </div>
  </div>
  <script>
    //listar_tabla();
    var video = document.createElement("video");
    var canvasElement = document.getElementById("canvas");
    var canvas = canvasElement.getContext("2d");
    var loadingMessage = document.getElementById("loadingMessage");
    var outputContainer = document.getElementById("output");
    var outputData = document.getElementById("outputData");
    var stream = null;

    function activarCamara() {
      // Oculta el bot�n mientras la c�mara est� activa
      document.getElementById('btnCamara').style.display = 'none';
      navigator.mediaDevices.getUserMedia({
        video: {
          facingMode: "environment"
        }
      }).then(function(s) {
        stream = s;
        video.srcObject = stream;
        video.setAttribute("playsinline", true);
        video.play();
        requestAnimationFrame(tick);
      }).catch(function(error) {
        console.error("Error al obtener acceso a la Camara:", error);
        // Si hay error, vuelve a mostrar el bot�n
        document.getElementById('btnCamara').style.display = '';
      });
    }

    function tick() {
      if (video.readyState === video.HAVE_ENOUGH_DATA) {
        loadingMessage.hidden = true;
        canvasElement.hidden = false;
        outputContainer.hidden = false;

        canvasElement.height = video.videoHeight;
        canvasElement.width = video.videoWidth;
        canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
        var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
        var code = jsQR(imageData.data, imageData.width, imageData.height, {
          inversionAttempts: "dontInvert",
        });
        if (code) {
          Swal.fire({
            title: 'Codigo QR Detectado',
            text: code.data,
            icon: 'success',
            confirmButtonText: 'Aceptar'
          });
          verificar(code.data);
          canvasElement.hidden = true;

          if (stream) {
            stream.getTracks().forEach(track => track.stop());
            video.srcObject = null;
          }
          // Muestra el bot�n nuevamente cuando termina el escaneo
          document.getElementById('btnCamara').style.display = '';
          return;
        }
      }
      requestAnimationFrame(tick);
    }
    //----------------------------
    function verificar(id) {
      Swal.fire({
        imageUrl: "personal/funcionarios/" + id + "_0.jpg",
        imageWidth: 150,
        imageHeight: 200,
        title: 'Cual Horario utilizar?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'MANANA',
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
              listar_tabla();
            }
          });
        }
      })
    }
    //--------------------- PARA BUSCAR
    function listar_tabla() {
      $('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
      $('#div1').load('seguridad/5a_tablaC.php', function() {
        $('#div1').fadeIn('slow');
      });
    }
    //---------------------
    function borrar(id) {
      Swal.fire({
        title: 'Estas seguro de eliminar el Registro?',
        text: "Esta accion no se puede revertir!",
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
  </script>
</body>

</html>