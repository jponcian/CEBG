<?php
//session_start();
//error_reporting(0);

require_once __DIR__ . '../../../scripts/conexion.php';
require_once __DIR__ . '../../../scripts/funciones.php';

class CrudSolicitudes{

    protected $db;

    public function __construct()
    {
        $this->db = DB();
    }

    public function Listar()
    {
        $query = $this->db->prepare("SELECT
            patente.numero,
            date_format(solicitudes.fecha,'%d/%m/%Y') as fecha, solicitudes.estatus, solicitudes.descripcion, solicitudes.id_solicitud, solicitudes.id_contribuyente, solicitudes.id_patente, patente.descripcion_establecimiento, patente.rif FROM solicitudes , patente WHERE patente.id = solicitudes.id_patente AND patente.estatus < 10 AND solicitudes.estatus<10"); 
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function listarConceptos()
    {
        $query = $this->db->prepare("SELECT conceptos.descripcion, conceptos.id FROM conceptos ORDER BY conceptos.descripcion");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function listarPartida($id)
    {
        $query = $this->db->prepare("SELECT partidas.codigo, partidas.descripcion, partidas.id FROM partidas INNER JOIN conceptos ON  conceptos.id_partida = partidas.id WHERE conceptos.id = $id");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function listarPlanillas($id)
    {
        $query = $this->db->prepare("SELECT planillas.id, planillas.id_contribuyente, planillas.id_patente, planillas.id_declaracion, planillas.origen, planillas.id_concepto, DATE_FORMAT(planillas.fecha,'%d/%m/%Y') as fecha, planillas.anno, planillas.numero, planillas.periodo_inicio, planillas.periodo_fin, CONCAT_WS(' AL ', DATE_FORMAT(planillas.periodo_inicio,'%d/%m/%Y'), DATE_FORMAT(planillas.periodo_fin,'%d/%m/%Y')) as periodo, planillas.monto, planillas.usuario, planillas.fecha_proceso, planillas.estatus, patente.numero as patente, conceptos.descripcion as concepto FROM planillas INNER JOIN patente ON patente.id = planillas.id_patente INNER JOIN conceptos ON conceptos.id = planillas.id_concepto WHERE planillas.id_contribuyente = $id AND planillas.origen <> 2 AND planillas.estatus < 10");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function cerrarSolicitud($id_solicitud, $usuario)
    {
        $query = $this->db->prepare("UPDATE solicitudes SET estatus=10,  usuario_gestion='$usuario', fecha_cierre=CURDATE() WHERE solicitudes.id_solicitud=$id_solicitud");
        $transaccion = $query->execute();

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Solicitud cerrada con éxito';

            //DATOS DE LA SOLICITUD
            echo 'Id solicitud: '.$id_solicitud;
            $sql = "SELECT LPAD(id_solicitud,8,'0') AS numero, DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha, contribuyente.email FROM solicitudes INNER JOIN contribuyente ON contribuyente.id = solicitudes.id_contribuyente WHERE solicitudes.id_solicitud = $id_solicitud"; 
            $query = $this->db->prepare($sql);
            $transaccion = $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $numerosolicitud = $row['numero'];
            $fechasolicitud = $row['fecha'];
            $emailsp = $row['email'];

            ProcesarEnvioEmail($numerosolicitud, $fechasolicitud, $emailsp);
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al cerrar la solicitud';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['resultado' => $data]);
    }

    public function AgregarPlanilla($data)
    {
        $permitido = false;
        $mensaje = 'Problemas al registrar la planilla';

        $transaccion = false;
        $i=0;
        $id=0;
       //RECCORREMOS E INSERTAMOS EL REGISTRO
        foreach($data['solvencia'] as $obj) {
            $id = $obj['id_solicitud'];
            $sql = "CALL sp_planillas_solvencia(?,?,?,?,?,?,?,?)";
            $query = $this->db->prepare($sql);
            $query->bindParam("?,?,?,?,?,?,?,?", $obj['id_contribuyente'], $obj['id_patente'], $obj['id_solicitud'], $obj['concepto'], Obtenerfecha($obj['fecha_inicio']), Obtenerfecha($obj['fecha_fin']), $obj['monto'], $obj['usuario']);
            $transaccion = $query->execute(array($obj['id_contribuyente'], $obj['id_patente'], $obj['id_solicitud'], $obj['concepto'], Obtenerfecha($obj['fecha_inicio']), Obtenerfecha($obj['fecha_fin']), $obj['monto'], $obj['usuario']));
            $i++;
        }

        if ($transaccion && $i > 0)
        {
            $sql = "UPDATE solicitudes SET estatus = 1 WHERE id_solicitud = $id";
            $query = $this->db->prepare($sql);
            $transaccion = $query->execute();
            $permitido = true;
            $mensaje = 'Registro agregado con éxito';

            //DATOS DE LA SOLICITUD
            $sql = "SELECT LPAD(id_solicitud,8,'0') AS numero, DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha, contribuyente.email FROM solicitudes INNER JOIN contribuyente ON contribuyente.id = solicitudes.id_contribuyente WHERE solicitudes.id_solicitud = $id"; 
            $query = $this->db->prepare($sql);
            $transaccion = $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $numerosolicitud = $row['numero'];
            $fechasolicitud = $row['fecha'];
            $emailsp = $row['email'];

            ProcesarEnvioEmail($numerosolicitud, $fechasolicitud, $emailsp);
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al registrar la planilla';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['resultado' => $data]);
    }


}

function ProcesarEnvioEmail($numerosolicitud, $fecha, $email_sp)
{
        //************************* TEXTO DEL MENSAJE ****************************************
        // destinatarios
        $para  = $email_sp;// . ', '; // atención a la coma
        //$para .= 'wez@example.com';

        // título
        $título = 'SOLICITUD EN PRCESO Y LA MISMA TIENE DEUDAS PENDIENTES';

        // mensaje
        $mensaje = '
        <html>
        <head>
          <title>SU SOLICITUD '.$numdeclaracion.' DE FECHA '.$fecha.' SE ENCUENTRA EN PROCESO</title>
        </head>
        <body>
          <p>La Alcaldía Bolivariana Francisco de Miranda cumple con informar que se le ha registrado las deudas pendientes de pago, relacionadas con su solicitud número '.$numerosolicitud.' de fecha '.$fechasolicitud.' para poder continuar con el proceso de la solicitud, debe proceder a la cancelación de las mismas, a través de nuestro sistema online o dirigiendo a las taquillas de pago ubicadas en nuestras instalaciones.</p>
          <p>Le invitamos a continuar utilizando nuestro sistema ONLINE - www.alcaldiafranciscodemiranda.com, estamos a su servicio.</p>
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