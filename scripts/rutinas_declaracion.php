<?php
//session_start();
//error_reporting(0);

require_once __DIR__ . '/conexion.php';

class CrudDeclaracion{

    protected $db;

    public function __construct()
    {
        $this->db = DB();
    }

    public function declaracionModificar($id)
    {
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT actividades.codigo, right(actividades.descripcion, 23) as ramo, declaracion_detalle.tasa, declaracion_detalle.ingresos_brutos, declaracion_detalle.id, declaracion_detalle.id_declaracion FROM declaracion_detalle INNER JOIN actividades ON actividades.id = declaracion_detalle.id_actividad WHERE declaracion_detalle.id_declaracion = $id");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function ListarDeclaracion($id)
    {
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT declaracion.id, CONCAT(SUBSTRING(YEAR(declaracion.fecha), -2),LPAD(MONTH(declaracion.fecha),2,'0'),LPAD(declaracion.numero,8,'0')) AS numero, DATE_FORMAT(declaracion.fecha, '%d/%m/%Y') AS fecha, declaracion.total_impuesto, declaracion_detalle.periodo_inicio, declaracion_detalle.periodo_fin, planillas.id as id_planilla FROM declaracion INNER JOIN declaracion_detalle ON declaracion_detalle.id_declaracion = declaracion.id INNER JOIN planillas ON planillas.id_declaracion = declaracion.id WHERE declaracion.id_patente = $id AND declaracion.estatus = 0 AND declaracion.total_impuesto > 0 GROUP BY declaracion_detalle.id_declaracion ORDER BY declaracion.fecha DESC");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function ListarPagosEnviados($id)
    {
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT pagos_enviados.id, pagos_enviados.id_planilla, formas_pago.descripcion AS formapago, pagos_enviados.referencia, pagos_enviados.fecha, banco_emisor.banco AS bancoorigen, cuentas_bancarias.banco AS bancodestino, pagos_enviados.monto_pagado, pagos_enviados.estatus, pagos_enviados.id_patente, pagos_enviados.id_declaracion FROM pagos_enviados INNER JOIN formas_pago ON formas_pago.id = pagos_enviados.id_formapago INNER JOIN banco_emisor ON banco_emisor.id_banco = pagos_enviados.id_bancoorigen INNER JOIN cuentas_bancarias ON cuentas_bancarias.id = pagos_enviados.id_bancodestino INNER JOIN patente ON patente.id = pagos_enviados.id_patente WHERE patente.id_contribuyente = $id and  pagos_enviados.estatus < 4 order by pagos_enviados.fecha desc"); 
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function AnularPagosEnviados($id, $origen)
    {
        $permitido = false;
        $mensaje = 'Problemas la registrar la declaración';
        
        //$query = $this->db->prepare("UPDATE pagos_enviados SET estatus = 4 WHERE pagos_enviados.id_planilla = $id AND estatus=0");
        $query = $this->db->prepare("DELETE FROM pagos_enviados WHERE pagos_enviados.id_planilla = $id");
        $transaccion = $query->execute();

        if ($transaccion)
        {
            if ($origen == 2)
            {
                $query = $this->db->prepare("UPDATE declaracion SET estatus=0 WHERE estatus=1 AND id not in (SELECT id_declaracion FROM pagos_enviados);");
                $transaccion = $query->execute();
            }

            $query = $this->db->prepare("UPDATE planillas SET planillas.estatus=5 WHERE id_recibo=0 AND planillas.id = $id");
            $transaccion = $query->execute();

            $permitido = true;
            $mensaje = 'Pago anulado con éxito';
        }
        else
        {
            $permitido = false;
            $mensaje = 'Problemas al anular el pago';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje,
        );

        return json_encode(['resultado' => $data]);

    }

    public function ListarBancos()
    {
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT * FROM banco_receptor WHERE id_banco < 0");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function ListarFormasPagos($accion)
    {
        if ($accion == 1)
        {
            $filtro = ' WHERE id=2';
        } else {
            $filtro = '';
        }
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT formas_pago.id, formas_pago.descripcion FROM formas_pago".$filtro);
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function ListadoBancos()
    {
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT id_banco, banco FROM banco_emisor WHERE id > 3 ORDER BY descripcion");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function DatosEmpresa($id)
    {
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT contribuyente.id, patente.id AS id_patente, contribuyente.rif, contribuyente.nombre, patente.numero, patente.descripcion_establecimiento, patente.direeccion_establecimiento, patente.representante, patente.ced_representante FROM contribuyente INNER JOIN patente ON patente.id_contribuyente = contribuyente.id WHERE patente.id = $id");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function datosSistema()
    {
        $querysistema = $this->db->prepare("SELECT datos_empresa.id, datos_empresa.empresa, datos_empresa.direccion, datos_empresa.telefono, datos_empresa.horario, datos_empresa.email FROM datos_empresa");
        $querysistema->execute();
        while ($row = $querysistema->fetch(PDO::FETCH_ASSOC)) {
            $nombre = $row['empresa'];
            $direccion = utf8_encode($row['direccion']);
            $telefono = $row['telefono'];
            $horario = $row['horario'];
            $email = $row['email'];
        }

        $data = array(
            "nombre" => $nombre,
            "direccion" => $direccion,
            "telefono" => $telefono,
            "horario" => $horario,
            "email" => $email
        );

        return json_encode($data);        
    }

    public function BuscarDeclaracion($inicio, $fin, $id)
    {
        $permitido = false;
        $mensaje = 'Declaración no esta registrada';

        //$query->CloseCursor();
        $sql = "SELECT DISTINCTROW declaracion.id, declaracion.numero, declaracion.fecha FROM declaracion INNER JOIN declaracion_detalle ON  declaracion_detalle.id_declaracion = declaracion.id WHERE declaracion.id_patente = $id AND declaracion_detalle.periodo_inicio = '$inicio' AND declaracion_detalle.periodo_fin = '$fin'";
        //echo $sql;
        $query = $this->db->query($sql);
        $data = array();
        $cant = $query->RowCount();
        $row = $query->fetch(PDO::FETCH_OBJ);
        $numero = $row->numero;
        $numero = str_pad($numero, 6, "0", STR_PAD_LEFT);
        $fecha = $row->fecha;
        $fecha = explode("-",$fecha);
        $fecha = $fecha[0].$fecha[1];
        $numero = $fecha.$numero;
        $idbd = $row->id;
        //echo 'Cantidad: '.$cant.' -- Id:'.$id;

        if ($cant > 0)
        {
            $permitido = false;
            $mensaje = 'Declaración registrada';
        }
        else {
            $permitido = true;
            $mensaje = 'Declaración no registrada';
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje,
            "numero" => $numero,
            "id" => $idbd
        );

        return json_encode($data);
    }

    public function Create($id_patente, $tipo, $usuario)
    {
        $permitido = false;
        $mensaje = 'Problemas la registrar la declaración';
        $anno = date("Y");
        $mes = date("m");

        //GENERAMOS EL NUMERO DE LA DECLARACION
        $consulta = "SELECT MAX(numero) as numero FROM declaracion WHERE year(fecha) = $anno and month(fecha) = $mes";
        $query_c = $this->db->query($consulta);
        $datos = $query_c->fetch(PDO::FETCH_OBJ);
        $cantidad = $query_c->rowCount();
        if ($datos->numero > 0)
        {
            $numero = $datos->numero + 1;
        }
        else
        {
            $numero = 1;
        }
        //echo 'Cantidad ****** ----- : '.$cantidad.'--- '.$numero;

        $consulta_m = "SELECT SUM(ingresos_brutos) as monto, SUM(impuesto) as impuesto FROM declaracion_detalle WHERE id_patente=$id_patente and id_declaracion=0";
        $query_m = $this->db->query($consulta_m);
        $valor = $query_m->fetch(PDO::FETCH_OBJ);
        $monto = $valor->monto;
        $impuesto = $valor->impuesto;

        
       //INSERTAMOS LA DECLARACION
        //echo $id_patente.' - '.$numero.' - '.$tipo.' - '.$monto.' - '.$impuesto.' - '.$usuario;
        $transaccion = false;
        $sql = "CALL sp_add_declaracion(?,?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?", $id_patente, $numero, $tipo, $monto, $impuesto, $usuario);
        $transaccion = $query->execute(array($id_patente, $numero, $tipo, $monto, $impuesto, $usuario));
        /*if ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $id_declaracion = trim($row->id);
        }*/
        if ($transaccion)
        {
            $permitido = true;
            $mensaje = 'Registro agregado con éxito';
        }
        else
        {
            //echo $impuesto;
            $permitido = false;
            $mensaje = 'Problemas al agregar el registro';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje,
            "id_patente" => $id_patente 
        );

        return json_encode(['resultado' => $data]);

    }

    public function CreateEnviarPago($id_planilla, $id_patente, $id_declaracion, $id_formapago, $referencia, $fecha_pago, $id_bancoorigen, $id_bancodestino, $monto_pagado, $usuario)
    {
        $permitido = false;
        $mensaje = 'Problemas la registrar la declaración';

       //INSERTAMOS LA DECLARACION
        //$this->db->beginTransaction();
        $sql = "CALL sp_send_pagos(?,?,?,?,?,?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?,?,?,?,?", $id_planilla, $id_patente, $id_declaracion, $id_formapago, $referencia, $fecha_pago, $id_bancoorigen, $id_bancodestino, $monto_pagado, $usuario);
        $transaccion = $query->execute(array($id_planilla, $id_patente, $id_declaracion, $id_formapago, $referencia, $fecha_pago, $id_bancoorigen, $id_bancodestino, $monto_pagado, $usuario));
      
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

        return json_encode(['resultado' => $data]);

    }

    public function CreateDeclaracioCero($id_patente, $tipo, $usuario, $inicio, $fin)
    {
        $permitido = false;
        $mensaje = 'Problemas la registrar la declaración';
        $anno = date("Y");
        $mes = date("m");

        //GENERAMOS EL NUMERO DE LA DECLARACION
        $consulta = "SELECT MAX(numero) as numero FROM declaracion WHERE year(fecha) = $anno and month(fecha) = $mes";
        $query_c = $this->db->query($consulta);
        $datos = $query_c->fetch(PDO::FETCH_OBJ);
        $cantidad = $query_c->rowCount();
        //echo 'Cantidad ****** ----- : '.$cantidad;
        if ($datos->numero > 0)
        {
            $numero = $datos->numero + 1;
        }
        else
        {
            $numero = 1;
        }


        //BUSCAMOS LAS ACTIVIDADES DE LA PATENTE
        $consulta = "SELECT patente_detalle.id_actividad, actividades.tasa FROM actividades INNER JOIN patente_detalle ON patente_detalle.id_actividad = actividades.id WHERE patente_detalle.id_patente = $id_patente AND actividades.tasa > 0";
        $query_ac = $this->db->query($consulta);
        
        while ($row = $query_ac->fetch(PDO::FETCH_OBJ)) {
            //AGREGAMOS EL TEMPORAL
            $transaccion = false;
            $sql = "CALL sp_add_detalle_dec(?,?,?,?,?,?,?,?)";
            $query = $this->db->prepare($sql);
            $query->bindParam("?,?,?,?,?,?,?,?", $id_patente, 0, $row->id_actividad, $inicio, $fin, 0, $row->tasa, $usuario);
            $transaccion = $query->execute(array($id_patente, 0, $row->id_actividad, $inicio, $fin, 0, $row->tasa, $usuario));
        }
       
       //INSERTAMOS LA DECLARACION
        $sql = "CALL sp_add_declaracion(?,?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?", $id_patente, $numero, $tipo, 0, 0, $usuario);
        $transaccion = $query->execute(array($id_patente, $numero, $tipo, 0, 0, $usuario));
      
        if ($transaccion)
        {
            $permitido = true;
            $mensaje = 'Registro agregado con éxito';
        }
        else
        {
            $permitido = false;
            $mensaje = 'Problemas al agregar el registro';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje,
            "id_patente" => $id_patente 
        );

        return json_encode(['resultado' => $data]);

    }

    public function consultaEstadoCuenta($id)
    {
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT declaracion.id, declaracion.id_patente, CONCAT(SUBSTRING(YEAR(declaracion.fecha), -2),LPAD(MONTH(declaracion.fecha),2,'0'),LPAD(declaracion.numero,8,'0')) AS numero, DATE_FORMAT(declaracion.fecha, '%d/%m/%Y') AS fecha, declaracion.total_impuesto, CONCAT_WS(' A ',DATE_FORMAT(declaracion_detalle.periodo_inicio, '%d/%m/%Y'),DATE_FORMAT(declaracion_detalle.periodo_fin, '%d/%m/%Y')) AS periodo, declaracion.tipo, patente.numero AS numeropatente, declaracion.estatus, planillas.id as id_planilla, planillas.origen FROM declaracion INNER JOIN declaracion_detalle ON declaracion_detalle.id_declaracion = declaracion.id INNER JOIN patente ON patente.id = declaracion.id_patente INNER JOIN planillas ON planillas.id_declaracion = declaracion.id WHERE declaracion.estatus < 2 AND patente.id_contribuyente = $id GROUP BY declaracion.id"); 
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function consultaEstadoCuentaPagadas($id)
    {
        //$query->CloseCursor();
        //$query = $this->db->prepare("SELECT declaracion.id, declaracion.id_patente, CONCAT(SUBSTRING(YEAR(declaracion.fecha), -2),LPAD(MONTH(declaracion.fecha),2,'0'),LPAD(declaracion.numero,8,'0')) AS numero, DATE_FORMAT(declaracion.fecha, '%d/%m/%Y') AS fecha, declaracion.total_impuesto, CONCAT_WS(' A ',DATE_FORMAT(declaracion_detalle.periodo_inicio, '%d/%m/%Y'),DATE_FORMAT(declaracion_detalle.periodo_fin, '%d/%m/%Y')) AS periodo, declaracion.tipo, patente.numero AS numeropatente, declaracion.estatus FROM declaracion INNER JOIN declaracion_detalle ON declaracion_detalle.id_declaracion = declaracion.id INNER JOIN patente ON patente.id = declaracion.id_patente WHERE declaracion.estatus = 2 AND patente.id_contribuyente = $id GROUP BY declaracion.id");
        $query = $this->db->prepare("SELECT planillas.id_recibo, planillas.id, patente.numero, conceptos.descripcion AS concepto, DATE_FORMAT(planillas.fecha, '%d/%m/%Y') AS fecha, CONCAT_WS(' AL ', DATE_FORMAT(planillas.periodo_inicio, '%d/%m/%Y'), DATE_FORMAT(planillas.periodo_fin, '%d/%m/%Y') ) AS periodo, planillas.estatus, planillas.monto, planillas.id_declaracion, planillas.origen FROM planillas INNER JOIN patente ON patente.id = planillas.id_patente INNER JOIN conceptos ON conceptos.id = planillas.id_concepto WHERE planillas.estatus = 10 AND planillas.id_contribuyente = $id"); 
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function MostrarPago($id)
    {
        //$query->CloseCursor();
        $query = $this->db->prepare("SELECT planillas_pagos.referencia, cuentas_bancarias.banco, cuentas_bancarias.cuenta, planillas_pagos.monto_pagado, planillas_pagos.monto_planilla, planillas_pagos.fecha FROM planillas_pagos INNER JOIN cuentas_bancarias ON cuentas_bancarias.id = planillas_pagos.id_banco WHERE planillas_pagos.id_planilla = $id");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function procesarModificacionDeclaracion($data)
    {
        $permitido = false;
        $mensaje = 'Problemas al modificar la declaración';
        $monto_ingresos =0;
        $monto_impuesto =0;
       
         foreach($data['modificar'] as $item){
            //Nombre del participante.
            $id = $item['id'];
            $id_declaracion = $item['id_declaracion'];
            $ingresos_brutos = $item['ingresos_brutos'];
            $tasa = $item['tasa'];
            $impuesto = $ingresos_brutos * $tasa / 100;

           //INSERTAMOS EL REGISTRO
            $transaccion = false;
            $sql = "CALL sp_editar_declaracion(?,?,?,?)";
            $query = $this->db->prepare($sql);
            $query->bindParam("?,?,?,?", $id, $ingresos_brutos, $impuesto, $tasa);
            $transaccion = $query->execute(array($id, $ingresos_brutos, $impuesto, $tasa));

         }

        if ($transaccion)
        {
            $permitido = true;
            $mensaje = 'Declaración modificada con éxito';
        }
        else
        {
            $permitido = false;
            $mensaje = 'Problemas al modificar la declaración';
        }
       
        $datos = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode($datos);
    }

    public function BorrarDetalleTemporal($id)
    {
        $transaccion = false;
        $permitido = false;
        $mensaje = 'Problemas al borrar el temporal';

        $query = $this->db->prepare("DELETE FROM declaracion_detalle WHERE declaracion_detalle.id_patente = $id and id_declaracion=0");
        $transaccion = $query->execute();

        if ($transaccion)
        {
            $permitido = true;
            $mensaje = 'Temporal borrado con exito';
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        return json_encode($data);
    }

}