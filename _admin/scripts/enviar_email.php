<?php

function enviar_email($destino, $asunto, $mensaje, $cabecera) {
        // PARA ENVIAR CORREO DE INFORMACION DE PAGO
        if (mail($destino,$asunto,$mensaje,$cabecera)) {
            $respuesta = true;
        } else {
            $respuesta = false;    
        }
        return $respuesta;
    }

?>