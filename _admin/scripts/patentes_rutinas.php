<?php
//session_start();
//error_reporting(0);
require_once __DIR__ . '../../../scripts/conexion.php';
class CrudAdminPatentes{

    protected $db;
    public function __construct()
    {
        $this->db = DB();
    }

    public function Listar()
    {
        $query = $this->db->prepare("SELECT date_format(patente.cierre_tmp,'%d/%m/%Y') as cierre_tmp, date_format(patente.cierre_def,'%d/%m/%Y') as cierre_def, patente.id, patente.numero, date_format(patente.fecha_registro,'%d/%m/%Y') as fecha_registro, patente.descripcion_establecimiento, patente.direeccion_establecimiento, patente.representante, patente.ced_representante, date_format(patente.vencimiento,'%d/%m/%Y') as vencimiento, patente.estatus, patente.id_contribuyente, patente.rif, patente.expediente FROM patente");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function ListarEstadoCuenta($filtrar)
    {
        $query = $this->db->prepare("SELECT declaracion.id, patente.numero AS numeropatente, contribuyente.rif, patente.descripcion_establecimiento, CONCAT(SUBSTRING(YEAR(declaracion.fecha), -2),LPAD(MONTH(declaracion.fecha),2,'0'),LPAD(declaracion.numero,8,'0')) as numerodeclaracion, DATE_FORMAT(declaracion.fecha, '%d/%m/%Y') as fecha, CONCAT_WS('-',LPAD(MONTH(declaracion_detalle.periodo_inicio),2,'0'), YEAR(declaracion_detalle.periodo_inicio)) as periodo, declaracion.monto_declarado, declaracion.total_impuesto, declaracion.estatus, declaracion_detalle.periodo_inicio, declaracion_detalle.periodo_fin FROM declaracion INNER JOIN patente ON patente.id = declaracion.id_patente INNER JOIN contribuyente ON contribuyente.id = patente.id_contribuyente INNER JOIN declaracion_detalle ON declaracion_detalle.id_declaracion = declaracion.id WHERE $filtrar GROUP BY declaracion.numero ");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return json_encode(['resultado' => $data]);
    }


 public function buscarPatente($filtrar)
    {
        $query = $this->db->prepare("SELECT date_format(patente.cierre_tmp,'%d/%m/%Y') as cierre_tmp, date_format(patente.cierre_def,'%d/%m/%Y') as cierre_def, patente.id, patente.numero, date_format(patente.fecha_registro,'%d/%m/%Y') as fecha_registro, patente.descripcion_establecimiento, patente.direeccion_establecimiento, patente.representante, patente.ced_representante, date_format(patente.vencimiento,'%d/%m/%Y') as vencimiento, patente.estatus, patente.id_contribuyente, patente.rif, patente.expediente FROM patente WHERE $filtrar");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function buscarNumero($numero)
    {
        $query = $this->db->prepare("SELECT date_format(patente.cierre_tmp,'%d/%m/%Y') as cierre_tmp, date_format(patente.cierre_def,'%d/%m/%Y') as cierre_def, patente.id, patente.numero, date_format(patente.fecha_registro,'%d/%m/%Y') as fecha_registro, patente.descripcion_establecimiento, patente.direeccion_establecimiento, patente.representante, patente.ced_representante, date_format(patente.vencimiento,'%d/%m/%Y') as vencimiento, patente.estatus, patente.id_contribuyente, patente.rif, patente.expediente FROM patente WHERE patente.numero = '$numero'");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }



    /*public function Buscar($codigo)
    {
        $query = $this->db->prepare("SELECT actividades.id FROM actividades WHERE codigo=$codigo");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }*/



    public function Agregar($numero,$fecha,$descripcion,$direccion,$representante,$cedula,$vencimiento,$obreros,$empleados,$turnos,$manana,$tarde,$nocturnos,$talento_vivo,$rockola,$otro,$usuario,$rif)
    {
         //BUSCAMOS EL ID DEL CONTRIBUYENTE
        $rif = strtoupper($rif);
        $query_c = $this->db->prepare("SELECT contribuyente.id FROM contribuyente WHERE contribuyente.rif ='$rif'");
        $query_c->execute();
        $row = $query_c->fetch(PDO::FETCH_OBJ);
        $id_contribuyente = $row->id;
        $estatus = 0;
        $permitido = false;
        $mensaje = 'Problemas al registrar la patente';
       //INSERTAMOS EL REGISTRO

        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "CALL sp_add_patente(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?", $fecha, $descripcion, $direccion, $representante, $cedula, $vencimiento, $estatus, $obreros, $empleados, $turnos, $manana, $tarde, $nocturnos, $talento_vivo, $rockola, $otro, $usuario, $id_contribuyente, $rif, $numero);
        $transaccion = $query->execute(array($fecha, $descripcion, $direccion, $representante, $cedula, $vencimiento, $estatus, $obreros, $empleados, $turnos, $manana, $tarde, $nocturnos, $talento_vivo, $rockola, $otro, $usuario, $id_contribuyente, $rif, $numero));

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
            $mensaje = 'Problemas al registrar la patente';
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['patente' => $data]);
    }

    public function AgregarDetalleTmp($id,$numero,$usuario)
    {
        $permitido = false;
        $mensaje = 'Problemas al registrar la actividad';
       //INSERTAMOS EL REGISTRO
        $id_patente = 0;

        $sql = "INSERT INTO patente_detalle_tmp (id_patente, numero, id_actividad, usuario) VALUES (?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?", $id_patente, $numero, $id, $usuario);
        $transaccion = $query->execute(array($id_patente, $numero, $id, $usuario));
        if ($transaccion)
        {
            $permitido = true;
            $mensaje = 'Registro agregado con éxito';
        }
        else
        {
            $permitido = false;
            $mensaje = 'Problemas al registrar la actividad';
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['actividad' => $data]);
    }

    public function EditarDetalleTmp($id,$id_patente,$numero,$usuario)
    {
        $permitido = false;
        $mensaje = 'Problemas al registrar la actividad';
        $accion = 1;
       //INSERTAMOS EL REGISTRO

        $sql = "INSERT INTO patente_detalle_tmp (id_patente, numero, id_actividad, usuario, accion) VALUES (?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?", $id_patente, $numero, $id, $usuario, $accion);
        $transaccion = $query->execute(array($id_patente, $numero, $id, $usuario, $accion));
        if ($transaccion)
        {
            $permitido = true;
            $mensaje = 'Registro agregado con éxito';
        }
        else
        {
            $permitido = false;
            $mensaje = 'Problemas al registrar la actividad';
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['actividad' => $data]);
    }

    public function Editar($id,$numero,$fecha,$descripcion,$direccion,$representante,$cedula,$vencimiento,$obreros,$empleados,$turnos,$manana,$tarde,$nocturnos,$talento_vivo,$rockola,$otro,$usuario,$rif,$estatus,$cierre_tmp,$cierre_def)
    {
         //BUSCAMOS EL ID DEL CONTRIBUYENTE
        $rif = strtoupper($rif);
        $query_c = $this->db->prepare("SELECT contribuyente.id FROM contribuyente WHERE contribuyente.rif ='$rif'");
        $query_c->execute();
        $row = $query_c->fetch(PDO::FETCH_OBJ);
        $id_contribuyente = $row->id;
        $permitido = false;
        $mensaje = 'Problemas al actualizar el registro';

       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "CALL sp_editar_patente(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?", $id, $numero, date("Y-m-d", strtotime($fecha)), $descripcion, $direccion, $representante, $cedula, date("Y-m-d", strtotime($vencimiento)), $obreros, $empleados, $turnos, $manana, $tarde, $nocturnos, $talento_vivo, $rockola, $otro, $usuario, $id_contribuyente, $rif, $estatus, date("Y-m-d", strtotime($cierre_tmp)), date("Y-m-d", strtotime($cierre_def)));
        $transaccion = $query->execute(array($id, $numero, date("Y-m-d", strtotime($fecha)), $descripcion, $direccion, $representante, $cedula, date("Y-m-d", strtotime($vencimiento)), $obreros, $empleados, $turnos, $manana, $tarde, $nocturnos, $talento_vivo, $rockola, $otro, $usuario, $id_contribuyente, $rif, $estatus, date("Y-m-d", strtotime($cierre_tmp)), date("Y-m-d", strtotime($cierre_def))));
		//echo 'Valores: '.$estatus.' * '.$cierre_tmp.' * '.$cierre_def;
        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Registro actualizado con éxito';
            unlink($archivo);
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al actualizar la patente';
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['patente' => $data]);
    }

	public function Eliminar($id)
    {
        $permitido = false;
        $mensaje = '';

     	//REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "INSERT INTO patente_ (SELECT * FROM patente WHERE id=?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?", $id);
        $transaccion = $query->execute(array($id));

        $sql = "DELETE FROM patente WHERE id=?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?", $id);
        $transaccion = $query->execute(array($id));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Patente eliminada con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al eliminar la patente';
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        return json_encode(['resultado' => $data]);
    }

    public function EliminarTmpAll($numero,$usuario)
    {
        $permitido = false;
        $mensaje = '';

        //REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "DELETE FROM patente_detalle_tmp WHERE id_patente=? and accion=0 and usuario=?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?", $numero, $usuario);
        $transaccion = $query->execute(array($numero, $usuario));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Temporal eliminado con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al eliminar el temporal';
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        return json_encode($data);
    }
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