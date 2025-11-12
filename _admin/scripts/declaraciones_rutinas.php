<?php
//session_start();
//error_reporting(0);

require_once __DIR__ . '../../../scripts/conexion.php';
include_once 'enviar_email.php';

class CrudAdminDeclaraciones{

    protected $db;

    public function __construct()
    {
        $this->db = DB();
    }

    public function ListarDeclaraciones($fecha)
    {
        $fecha = Obtenerfecha($fecha);
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT declaracion.id, patente.numero AS numeropatente, contribuyente.rif, patente.descripcion_establecimiento, CONCAT(SUBSTRING(YEAR(declaracion.fecha), -2),LPAD(MONTH(declaracion.fecha),2,'0'),LPAD(declaracion.numero,8,'0')) as numerodeclaracion, DATE_FORMAT(declaracion.fecha, '%d/%m/%Y') as fecha, CONCAT_WS('-',LPAD(MONTH(declaracion_detalle.periodo_inicio),2,'0'), YEAR(declaracion_detalle.periodo_inicio)) as periodo, declaracion.monto_declarado, declaracion.total_impuesto, declaracion.estatus, declaracion_detalle.periodo_inicio, declaracion_detalle.periodo_fin FROM declaracion INNER JOIN patente ON patente.id = declaracion.id_patente INNER JOIN contribuyente ON contribuyente.id = patente.id_contribuyente INNER JOIN declaracion_detalle ON declaracion_detalle.id_declaracion = declaracion.id WHERE declaracion.fecha = '$fecha' GROUP BY declaracion.numero"); 
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function Listar($id)
    {
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT patente.numero, planillas.id, CONCAT(year(planillas.fecha),CONCAT(REPEAT('0',2-LENGTH(MONTH(planillas.fecha))),MONTH(planillas.fecha)),CONCAT(REPEAT('0',6-LENGTH(planillas.numero)),planillas.numero)) as numeroplanilla, DATE_FORMAT(planillas.fecha, '%d/%m/%Y') as fecha_planilla, CONCAT(year(planillas.fecha),CONCAT(REPEAT('0',2-LENGTH(MONTH(planillas.fecha))),MONTH(planillas.fecha)),CONCAT(REPEAT('0',6-LENGTH(planillas.numero)),planillas.numero)) as numerodeclaracion, DATE_FORMAT(planillas.fecha, '%d/%m/%Y') as fecha_declaracion, planillas.origen as tipo, CONCAT(DATE_FORMAT(planillas.periodo_inicio, '%d/%m/%Y'),' al ',DATE_FORMAT(planillas.periodo_fin, '%d/%m/%Y')) as periodo, planillas.monto FROM patente INNER JOIN planillas ON planillas.id_patente = patente.id WHERE patente.id = $id AND planillas.estatus = 5 AND planillas.monto > 0");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function ListarPagosPatente($id)
    {
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT pagos_enviados.id, formas_pago.descripcion as formapago, pagos_enviados.referencia, DATE_FORMAT(pagos_enviados.fecha, '%d/%m/%Y') as fecha, banco_emisor.banco as bancoorigen, banco_receptor.banco as bancodestino, pagos_enviados.monto_pagado, pagos_enviados.detalle_planilla, pagos_enviados.estatus, pagos_enviados.id_bancodestino FROM pagos_enviados INNER JOIN formas_pago ON formas_pago.id = pagos_enviados.id_formapago INNER JOIN banco_emisor ON banco_emisor.id_banco = pagos_enviados.id_bancoorigen INNER JOIN banco_receptor ON banco_receptor.id = pagos_enviados.id_bancodestino WHERE pagos_enviados.estatus = 1 AND pagos_enviados.id_patente = $id ORDER BY pagos_enviados.fecha DESC");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);

    }

    public function ListarPagosEnviados()
    {
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT planillas.origen, pagos_enviados.id, pagos_enviados.id_planilla, formas_pago.descripcion AS formapago, pagos_enviados.referencia, DATE_FORMAT(pagos_enviados.fecha, '%d/%m/%Y') AS fecha, banco_emisor.banco AS bancoorigen, banco_receptor.banco AS bancodestino, CONCAT(formas_pago.descripcion, ' DESDE ', banco_emisor.banco, ' AL ', banco_receptor.banco ) AS descripcion_pago, pagos_enviados.monto_pagado, pagos_enviados.estatus, pagos_enviados.id_bancodestino, patente.numero, patente.descripcion_establecimiento AS razonsocial, pagos_enviados.id_patente, pagos_enviados.id_declaracion, conceptos.descripcion AS concepto FROM pagos_enviados INNER JOIN planillas ON planillas.id = pagos_enviados.id_planilla INNER JOIN conceptos ON conceptos.id = planillas.id_concepto INNER JOIN formas_pago ON formas_pago.id = pagos_enviados.id_formapago INNER JOIN banco_emisor ON banco_emisor.id_banco = pagos_enviados.id_bancoorigen INNER JOIN banco_receptor ON banco_receptor.id = pagos_enviados.id_bancodestino INNER JOIN patente ON patente.id = planillas.id_patente WHERE pagos_enviados.estatus = 0 ORDER BY pagos_enviados.fecha DESC, pagos_enviados.monto_pagado DESC"); 
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);

    }

        public function ListarPagosAll($numero)
    {
        $query = $this->db->prepare("SELECT DISTINCT pagos_enviados.id, formas_pago.descripcion AS formapago, DATE_FORMAT(pagos_enviados.fecha, '%d/%m/%Y') AS fecha, declaracion.total_impuesto as monto_pagado, pagos_enviados.estatus, pagos_enviados.id_bancodestino, patente.numero, pagos_enviados.id_patente, pagos_enviados.id_declaracion, CONCAT(SUBSTRING(YEAR(declaracion.fecha), -2),LPAD(MONTH(declaracion.fecha),2,'0'),LPAD(declaracion.numero,8,'0')) AS numerodeclaracion FROM pagos_enviados INNER JOIN formas_pago ON formas_pago.id = pagos_enviados.id_formapago INNER JOIN patente ON patente.id = pagos_enviados.id_patente INNER JOIN declaracion ON declaracion.id = pagos_enviados.id_declaracion WHERE pagos_enviados.estatus <> 4 AND patente.numero = '$numero' GROUP BY declaracion.id ORDER BY pagos_enviados.fecha DESC");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);

    }


    public function ConfirmarPago($id_banco, $id_planilla, $fecha, $referencia, $monto_pago, $monto_planilla, $usuario, $id_pago)
    {
        $permitido = false;
        $mensaje = 'Problemas al confirmar el pago';
       
       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "CALL sp_add_pagos(?,?,?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?,?", $id_banco, $id_planilla, $fecha, $referencia, $monto_pago, $monto_planilla, $usuario);
        $transaccion = $query->execute(array($id_banco, $id_planilla, $fecha, $referencia, $monto_pago, $monto_planilla, $usuario));

        if ($transaccion)
        {
            $sql = "UPDATE pagos_enviados SET estatus = 2 WHERE pagos_enviados.id = ?";
            $query = $this->db->prepare($sql);
            $query->bindParam("?", $id_pago);
            $transaccion = $query->execute(array($id_pago));

            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Pago registrado con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al registrar el pago';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode($data);
    }

    public function RegistrarPagos($data)
    {
        $permitido = false;
        $mensaje = 'Problemas al confirmar el pago';
       
         foreach($data['pagos'] as $item){
            //Nombre del participante.
            $id_declaracion = $item['numero_declaracion'];
            $id_formapago = $item['formapago'];
            $referencia = $item['referencia'];
            $fecha_pago = Obtenerfecha($item['fechapago']);
            $id_bancodestino = $item['bancodestino'];
            $id_bancoorigen = $item['bancoorigen'];
            $monto_pagado = $item['montopago'];
            $numeropatente = $item['numeropatente'];
            $usuario = $item['usuario'];
            $id_planilla = $item['idplanilla'];

            $query = $this->db->prepare("SELECT patente.id FROM patente WHERE patente.numero = $numeropatente");
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $id_patente = $row['id'];
 
           //INSERTAMOS EL REGISTRO
            $transaccion = false;
            $sql = "CALL sp_registrar_pagos(?,?,?,?,?,?,?,?,?,?)";
            $query = $this->db->prepare($sql);
            $query->bindParam("?,?,?,?,?,?,?,?,?,?", $id_patente, $id_planilla, $id_declaracion, $id_formapago, $referencia, $fecha_pago, $id_bancoorigen, $id_bancodestino, $monto_pagado, $usuario);
            $transaccion = $query->execute(array($id_patente, $id_planilla, $id_declaracion, $id_formapago, $referencia, $fecha_pago, $id_bancoorigen, $id_bancodestino, $monto_pagado, $usuario));

         }

        if ($transaccion)
        {
            $permitido = true;
            $mensaje = 'Pago registrado con éxito';
        }
        else
        {
            $permitido = false;
            $mensaje = 'Problemas al registrar el pago';
        }
       
        $datos = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode($datos);
    } 

    public function ActualizarEstatusPago($id_declaracion, $id_planilla, $id, $accion, $usuario, $origen)
    {
        $fecha = date('Y-m-d');
        $permitido = false;
        $mensaje = 'Problemas al actualizar el pago';
        if ($accion == 0)
        {
            $estatus = 1;
        } else {
            $estatus = 3;
        }
       
       //INSERTAMOS EL REGISTRO
        $sql = "UPDATE pagos_enviados SET estatus=?, fecha_validacion=CURDATE(), usuario_validacion=?, usuario = ? WHERE id = ? AND estatus=0";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?", $estatus, $usuario, $usuario, $id);
        $transaccion = $query->execute(array($estatus, $usuario, $usuario, $id));

        if ($transaccion)
        {
            $permitido = true;
            $mensaje = 'Pago registrado con éxito';

            //VERIFICAMOS SI EXISTE DEUDAS
            $query = $this->db->prepare("SELECT planillas.id FROM planillas WHERE planillas.id_patente = (SELECT id_patente FROM pagos_enviados WHERE id=$id_planilla) AND planillas.estatus < 3");
            $query->execute();
            $cantidad = $query->RowCount();

            if ($cantidad < 1)
            {
                $query = $this->db->prepare("UPDATE solicitudes SET solicitudes.estatus = 10, solicitudes.usuario_gestion = $usuario, solicitudes.fecha_cierre = CURDATE() WHERE solicitudes.id_patente = (SELECT id_patente FROM pagos_enviados WHERE id=$id) AND solicitudes.estatus < 10 AND solicitudes.id_origen = 3"); 
                $transaccion=$query->execute();

                $query = $this->db->prepare("UPDATE patente SET patente.fecha_solvencia = CURDATE() WHERE patente.id = (SELECT id_patente FROM pagos_enviados WHERE id=$id)");
                $transaccion=$query->execute();
            }

            if ($accion == 0)
            {
                $est = 2;
                $estplanilla = 3;
            } else {
                $est = 0;
                $estplanilla = 0;
            }

            if ($origen == 2)
            {
                $query = $this->db->prepare("UPDATE declaracion SET declaracion.estatus=$est WHERE declaracion.id = $id_declaracion");
                $transaccion = $query->execute();
            
            } 

            $query = $this->db->prepare("UPDATE planillas SET planillas.estatus=$estplanilla WHERE planillas.id = $id_planilla");
            $transaccion = $query->execute();

            //INSERTAMOS PLANILLAS_PAGO
            $query = $this->db->prepare("SELECT MAX(planillas_pagos.numero_recibo) as maximo FROM planillas_pagos WHERE YEAR(planillas_pagos.fecha) = year(CURDATE())"); 
            $transaccion = $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($row['maximo'] > 0)
            {
                $numero_recibo = $row['maximo'] + 1;
            } else {
                $numero_recibo = 1;
            }

            if ($accion == 0)
            {
                $query = $this->db->prepare("INSERT INTO planillas_pagos (numero_recibo, id_banco, id_planilla, fecha, referencia, monto_pagado, monto_planilla, usuario) (SELECT $numero_recibo, id_bancodestino, id_planilla, fecha, referencia, monto_pagado, monto_pagado as monto_planilla, '$usuario' FROM pagos_enviados WHERE id=$id)"); 
                    $transaccion = $query->execute();
            }

            $query = $this->db->prepare("SELECT CONCAT(SUBSTRING(YEAR(planillas.fecha), -2),LPAD(MONTH(planillas.fecha),2,'0'),LPAD(planillas.numero,8,'0')) AS numero, DATE_FORMAT(pagos_enviados.fecha, '%d/%m/%Y') AS fecha, pagos_enviados.referencia, pagos_enviados.monto_pagado, contribuyente.email FROM planillas, pagos_enviados, patente, contribuyente WHERE contribuyente.id = patente.id_contribuyente AND patente.id = planillas.id_patente AND pagos_enviados.id_planilla = planillas.id AND pagos_enviados.id = $id"); 
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
    
            ProcesarEnvioEmail($estatus, $row['numero'], $row['fecha'], $row['referencia'], number_format($row['monto_pagado'], 2, ',', '.'), $row['email']);
        }
        else
        {
            $permitido = false;
            $mensaje = 'Problemas al registrar el pago';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode($data);
    }

    public function CreateEnviarPago($numeropatente, $id_formapago, $referencia, $fecha_pago, $id_bancoorigen, $id_bancodestino, $monto_pagado, $detalle_planilla, $usuario)
    {
        $permitido = false;
        $mensaje = 'Problemas la registrar la declaración';

        //BUSCAMOS EL ID DE LA PATENTE
        $query_p = $this->db->prepare("SELECT patente.id FROM patente WHERE patente.numero = $numeropatente");
        $query_p->execute();
        $row = $query_p->fetch(PDO::FETCH_ASSOC);
        $id_patente = $row['id'];

       //INSERTAMOS LA DECLARACION
        //$this->db->beginTransaction();
        $sql = "CALL sp_send_pagos(?,?,?,?,?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?,?,?,?", $id_patente, $id_formapago, $referencia, $fecha_pago, $id_bancoorigen, $id_bancodestino, $monto_pagado, $detalle_planilla, $usuario);
        $transaccion = $query->execute(array($id_patente, $id_formapago, $referencia, $fecha_pago, $id_bancoorigen, $id_bancodestino, $monto_pagado, $detalle_planilla, $usuario));
      
        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Registro agregado con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al agregar el registro';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje,
            "id_patente" => $id_patente 
        );

        return json_encode(['estatuspago' => $data]);

    }

}

function ProcesarEnvioEmail($estatus, $numdeclaracion, $fecha, $referencia, $monto, $email_sp)
{
    if ($estatus == 1)
    {
        $estatuspago = 'CONFIRMADO';
    }
    else
    {
        $estatuspago = 'RECHAZADO';
    }
    //************************* TEXTO DEL MENSAJE ****************************************
        // destinatarios
        $para  = $email_sp;// . ', '; // atención a la coma
        //$para .= 'wez@example.com';

        // título
        $título = 'PAGO DE LA PLANILLA '.$numdeclaracion.' DE FECHA '.$fecha.' HA SIDO '.$estatuspago;

        // mensaje
        $mensaje = '
        <html>
        <head>
          <title>SU PAGO DE LA PLANILLA '.$numdeclaracion.' DE FECHA '.$fecha.' HA SIDO '.$estatuspago.'</title>
        </head>
        <body>
          <p>La Alcaldía Bolivariana Francisco de Miranda cumple con informar que el pago referido a la planilla Número '.$numdeclaracion.' de fecha '.$fecha.' ha sido <strong>'.$estatuspago.'</strong> y se encuentra reflejado en su estado de cuenta.</p>
          <p>Le invitamos a continuar utilizando nuestro sistema ONLINE - www.alcaldiafranciscodemiranda.com, estamos a su servicio.</p>
          <table>
            <tr>
              <th>Referencia</th>
              <th>Fecha</th>
              <th>Nro. Planilla</th>
              <th>Monto BsS.</th>
              <th>Estatus</th>
            </tr>
            <tr>
              <td>'.$referencia.'</td>
              <td>'.$fecha.'</td>
              <td>'.$numdeclaracion.'</td>
              <td>'.$monto.'</td>
              <td>'.$estatuspago.'</td>
            </tr>
          </table>
        </body>
        </html>
        ';

        // Para enviar un correo HTML, debe establecerse la cabecera Content-type
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Cabeceras adicionales
        $cabeceras .= 'To: '. $para . "\r\n";
        $cabeceras .= 'From: fundacion <notificaciones@alcaldiafranciscodemiranda.com>' . "\r\n";
        //$cabeceras .= 'Cc: birthdayarchive@example.com' . "\r\n";
        //$cabeceras .= 'Bcc: birthdaycheck@example.com' . "\r\n";

        // Enviarlo
        //$respuesta = enviar_email($para, $asunto, $mensaje, $cabeceras);
        mail($para, $título, $mensaje, $cabeceras);
    //************************************************************************************
} 

function Obtenerfecha($fecha)
{
    $fecha = str_replace('/', '-', $fecha);
    $fecha = explode("-",$fecha);
    $dia = $fecha[0];
    $dia = str_pad($dia, 2, "0", STR_PAD_LEFT);
    $mes = $fecha[1];
    $mes = str_pad($mes, 2, "0", STR_PAD_LEFT);
    $anio = $fecha[2];
    $fecha = $anio.'-'.$mes.'-'.$dia; 
    return $fecha;
}