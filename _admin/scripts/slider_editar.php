<?php
	//Tamaños de las imagenes para el slider 1200x250
    $img_anterior = $_GET['img'];
    $id = $_GET['id'];

    if(!empty($_FILES))
    {
        $tipos = array(
            "image/jpg",
            "image/jpeg",
            "image/png",
            "image/gif"
        );
        $limite_kb = 1000514;

        if (in_array($_FILES['file']['type'], $tipos) && $_FILES['file']['size'] <= $limite_kb)
        {
            if ($_FILES['file']['type'] === "image/jpg") {$ext = ".jpg";}
            if ($_FILES['file']['type'] === "image/jpeg") {$ext = ".jpg";}
            if ($_FILES['file']['type'] === "image/png") {$ext = ".png";}
            if ($_FILES['file']['type'] === "image/gif") {$ext = ".gif";}
            $name_partfinal = date("d").date("m").date("Y").time();
            $name_partinicial = 'slider_';
            $nombre_imagen = $name_partinicial.$name_partfinal.$ext;

            $path = '../../images/sliders/' . $nombre_imagen;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $path))
            {
                require __DIR__ . '/slider_rutinas.php';

                $admin_slider = new CrudAdminSlider();

                echo $admin_slider->Editar($nombre_imagen, $id, $img_anterior);
            }
            else
            {
                echo 'Problema al subir la imagen';
            }
        }
        else
        {
            echo 'Tamaño de la imagen o el tipo no estan permitido';
        }
    }
    else
    {
        $permitido = false;
        $mensaje = 'Error al subir la imagen';
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        echo json_encode(['slider' => $data]);
    }
?>
