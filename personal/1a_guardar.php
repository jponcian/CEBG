<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//--------
$info = array();
$tipo = 'info';
$tiempo_inicio = microtime(true);

//------------- INICIAR TRANSACCIÓN
$_SESSION['conexionsql']->begin_transaction();

// Optimizaciones para InnoDB
$_SESSION['conexionsql']->query("SET autocommit=0");
$_SESSION['conexionsql']->query("SET unique_checks=0");
$_SESSION['conexionsql']->query("SET foreign_key_checks=0");

try {
    //-------------
    $_SESSION['prima_hijos'] = prima_hijos();
    $_SESSION['prima_hogar'] = prima_hogar();
    //-------------
    $consultax = "SELECT id FROM rac_carga WHERE parentesco='Hijo(a)' AND DATE(DATE_ADD(fecha_nac, INTERVAL 18 YEAR))<=CURDATE();";
    $tablax = $_SESSION['conexionsql']->query($consultax);
    if ($tablax->num_rows>0)	
    {
        $consultx = "CALL actualizar_hijos();";
        $tablax = $_SESSION['conexionsql']->query($consultx);
    }
    //-------------
    $consultam = "SELECT * FROM a_nomina WHERE codigo = '".$_POST['ONOMINA']."' LIMIT 1;";
    $tablam = $_SESSION['conexionsql']->query($consultam);
    
    if ($tablam->num_rows == 0) {
        throw new Exception("No se encontró la nómina especificada");
    }
    
    $registrom = $tablam->fetch_object();
    
    $fecha = voltea_fecha($_POST['OFECHA']);
    $mes = mes(voltea_fecha($_POST['OFECHA']));
    $anno = anno(voltea_fecha($_POST['OFECHA']));
    $desde = $anno.'-'.$mes.'-'.$_POST['OQUINCENA'];
    $desde_m = $anno.'-'.$mes.'-01';
    if ($_POST['OQUINCENA']=='01')
    { 	$hasta= $anno.'-'.$mes.'-15'; 	} 
    else 	
    { 	$hasta= baja_dia(sube_mes(voltea_fecha($_POST['OFECHA']))); 	}
    if (substr($hasta,8,2)=='31')
    {$hasta = $anno.'-'.$mes.'-30';}
    
    $tipo_pago = '001';
    //-----------
    $codigo_nomina = $registrom->codigo;
    $nomina = $registrom->nomina;
    $partida = $registrom->partida;
    $partida_hijos = $registrom->hijos;
    $partida_profesion = $registrom->profesion;
    $partida_antiguedad = $registrom->antiguedad;
    $partida_tickets = $registrom->tickets;
    $partida_vacaciones = $registrom->vacaciones;
    $partida_sso = $registrom->seguro;
    $partida_lph = $registrom->leypolitica;
    $partida_pfo = $registrom->paro;
    $partida_fej = $registrom->jubilaciones;
    $categoria_tickets = $registrom->cat_tickets;
    $categoria = $registrom->cat_asignaciones;
    $categoria_descuentos = $registrom->cat_descuentos;
    
    //------------- SI LA NOMINA YA FUE SOLICITADA
    $consultx = "SELECT id FROM nomina WHERE estatus>0 AND nomina='$nomina' AND hasta='$hasta' AND tipo_pago='001' and lote=1;";
    $tablx = $_SESSION['conexionsql']->query($consultx);	
    if ($tablx->num_rows>0)
    { 
        throw new Exception("La nómina de sueldos ya fue generada para este período");
    }
    
    $consultx = "SELECT id FROM nomina WHERE estatus>0 AND nomina='$nomina' AND hasta='$hasta' AND tipo_pago='002' and lote=1;";
    $tablx = $_SESSION['conexionsql']->query($consultx);
    if ($tablx->num_rows>0)
    { 
        $msg_tickets = "no";
    }
    
    $consultx = "SELECT id FROM nomina WHERE estatus>0 AND nomina='$nomina' AND hasta='$hasta' AND tipo_pago='003' and lote=1;";
    $tablx = $_SESSION['conexionsql']->query($consultx);	
    if ($tablx->num_rows>0)
    { 
        $msg_vacaciones = "no";
    }
    
    //------------- LIMPIAR REGISTROS TEMPORALES
    if ($msg_sueldo<>'no' and $_POST['oquincena']==1)
    {
        $consultx = "DELETE FROM nomina WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND desde='$desde' AND hasta='$hasta' AND tipo_pago = '001' and lote=1;"; 
        $_SESSION['conexionsql']->query($consultx);	
        $consultx = "DELETE FROM nomina_solicitudes WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND desde='$desde' AND hasta='$hasta' AND tipo_pago = '001' and lote=1;"; 
        $_SESSION['conexionsql']->query($consultx);	
        //------------- GUARDAR SOLICITUD
        $numero = num_nomina('PAGO DE QUINCENA', $nomina, $anno);
        //-------------
        $consultx = "INSERT INTO nomina_solicitudes(patria, tipo_pago, numero, nomina, anno, fecha, descripcion, desde, hasta, estatus, lote, usuario) VALUES (1, '001', $numero, '$nomina', '$anno', '$fecha', 'PAGO DE QUINCENA', '$desde', '$hasta', 0, 1, '".$_SESSION['CEDULA_USUARIO']."');";
        $_SESSION['conexionsql']->query($consultx);
        //-------------	
        $consultax = "SELECT LAST_INSERT_ID() as id;";
        $tablax = $_SESSION['conexionsql']->query($consultax);	
        $registrox = $tablax->fetch_object();
        $id_solicitud_sueldo = $registrox->id;
        //-------------	FIN SOLICITUD
    }
    //-------------
    if ($msg_tickets<>'no' and  $_POST['OQUINCENA']<>'01' and $_POST['otickets']==1)
    {
        $consultx = "DELETE FROM nomina WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND hasta='$hasta' AND tipo_pago = '002' and lote=1;"; 
        $_SESSION['conexionsql']->query($consultx);
        $consultx = "DELETE FROM nomina_solicitudes WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND desde='$desde_m' AND hasta='$hasta' AND tipo_pago = '002' and lote=1;"; 
        $_SESSION['conexionsql']->query($consultx);	
        //------------- GUARDAR SOLICITUD
        $numero = num_nomina('PAGO DE CESTATICKETS', $nomina, $anno);
        //-------------
        $consultx = "INSERT INTO nomina_solicitudes(patria, tipo_pago, numero, nomina, anno, fecha, descripcion, desde, hasta, estatus, lote, usuario) VALUES (1, '002', $numero, '$nomina', '$anno', '$fecha', 'PAGO DE CESTATICKETS', '$desde_m', '$hasta', 0, 1, '".$_SESSION['CEDULA_USUARIO']."');";
        $_SESSION['conexionsql']->query($consultx);
        //-------------	
        $consultax = "SELECT LAST_INSERT_ID() as id;";
        $tablax = $_SESSION['conexionsql']->query($consultax);	
        $registrox = $tablax->fetch_object();
        $id_solicitud_tickets = $registrox->id;
        //-------------	FIN SOLICITUD
    }
    //-------------
    if ($msg_vacaciones<>'no' and $_POST['OQUINCENA']<>'01' and $_POST['ovacaciones']==1)
    {
        $consultx = "DELETE FROM nomina WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND hasta='$hasta' AND tipo_pago = '003' and lote=1;"; 
        $_SESSION['conexionsql']->query($consultx);
        $consultx = "DELETE FROM nomina_solicitudes WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND hasta='$hasta' AND tipo_pago = '003' and lote=1;"; 
        $_SESSION['conexionsql']->query($consultx);	
        //------------- GUARDAR SOLICITUD
        $numero = num_nomina('PAGO DE VACACIONES', $nomina, $anno);
        //-------------
        $consultx = "INSERT INTO nomina_solicitudes(patria, tipo_pago, numero, nomina, anno, fecha, descripcion, desde, hasta, estatus, lote, usuario) VALUES (1, '003', $numero, '$nomina', '$anno', '$fecha', 'PAGO DE VACACIONES', '$desde_m', '$hasta', 0, 1, '".$_SESSION['CEDULA_USUARIO']."');";
        $_SESSION['conexionsql']->query($consultx);
        //-------------	
        $consultax = "SELECT LAST_INSERT_ID() as id;";
        $tablax = $_SESSION['conexionsql']->query($consultax);	
        $registrox = $tablax->fetch_object();
        $id_solicitud_vaca = $registrox->id;
        //-------------	FIN SOLICITUD
        $consultx = "DELETE FROM nomina WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND hasta='$hasta' AND tipo_pago = '003' and lote=1;"; 
        $_SESSION['conexionsql']->query($consultx);	
    }
    //-------------
    $consultx = "DELETE FROM nomina_descuentos WHERE id_nomina NOT IN (SELECT id FROM nomina);";
    $_SESSION['conexionsql']->query($consultx);	
    $consultx = "DELETE FROM nomina_asignaciones WHERE id_nomina NOT IN (SELECT id FROM nomina);";
    $_SESSION['conexionsql']->query($consultx);	
    //-------------
    $num_nomina = nomina_sig();
    $num_nominac = $num_nomina+1;
    $num_nominav = $num_nomina+2;
    
    //------------- ARRAYS PARA INSERT MASIVO
    $valores_nomina = array();
    $valores_asignaciones = array();
    $valores_descuentos = array();
    $cedulas_empleados = array();
    
    //------------- OBTENER EMPLEADOS
    $consultx = "SELECT * FROM rac WHERE TRIM(cuenta)<>'' AND temporal=0 AND suspendido=0 AND nomina='$nomina' ORDER BY ubicacion, cedula;";
    $tablx = $_SESSION['conexionsql']->query($consultx);
    
    $contador_empleados = 0;
    
    while ($registro = $tablx->fetch_object())
    {
        $contador_empleados++;
        $cedula = $registro->cedula;
        $sueldo = $registro->sueldo;
        $sueldo_mensual = $registro->sueldo;
        $quincena = $registro->sueldo/2;
        $cargo = $registro->cargo;
        $des_sueldo = $registro->des_sueldo;
        $des_tickets = $registro->des_tickets;
        $ubicacion = $registro->ubicacion;
        $hijos = $registro->hijos/2;
        $profesion = $registro->profesion;
        $tickets = $registro->tickets;
        $ayuda = $registro->ayuda;
        $vacacion = $registro->suspendidov;
        $profesion1 = ($registro->prof);
        $ingreso1 = ($registro->fecha_ingreso);
        $anno_ing = anno($registro->fecha_ingreso);
        $mes_ing = mes($registro->fecha_ingreso);
        $dia_ing = dia($registro->fecha_ingreso);
        $annos = annos_exacto($anno_ing, $mes_ing , $dia_ing , anno($hasta), mes($hasta), dia($hasta));
        $anos_servicio = intval($annos) + intval($registro->anos_servicio);
        
        //--------------  AJUSTE POR FECHA DE INGRESO
        $fecha_desde = fecha_a_numero($desde);
        $fecha_hasta = fecha_a_numero($hasta);
        $fecha_ingreso = fecha_a_numero($registro->fecha_ingreso);
        if ($fecha_ingreso>$fecha_desde)
        {
            if ($fecha_ingreso<=$fecha_hasta)
            {
                $dias_trabajo = ($fecha_hasta-$fecha_ingreso) / 86400; 
                $sueldo = ($sueldo * ($dias_trabajo+1)) / ((($fecha_hasta-$fecha_desde)/86400)+1) ;
                $quincena = $sueldo/2;
            }
        }
        if ($des_sueldo>0)
        {
            $quincena = ((15-$des_sueldo)*($sueldo/2)) / 15 ;
        }
        
        //-------------- QUINCENA (TIPO PAGO 001)
        if ($msg_sueldo<>'no' and $_POST['oquincena']==1)
        {
            //------------- CALCULAR TOTALES ANTES DEL INSERT
            $total_asignaciones = 0;
            $total_descuentos = 0;
            
            //------------- DESCUENTOS
            $descuentos_empleado = array();
            
            if ($registro->sus_sso>0)
            {
                $lunes = lunes($anno, $mes);
                $monto = ((($sueldo_mensual*12/52)*$registro->sus_sso/100)*$lunes)/2;
                $monto2 = ((($sueldo_mensual*12/52)*9/100)*$lunes)/2;
                $total_descuentos += $monto;
                $descuentos_empleado[] = array(
                    'categoria' => $categoria_descuentos,
                    'partida' => $partida_sso,
                    'id_descuento' => '1',
                    'descuento' => $monto,
                    'patrono' => $monto2
                );
            }
            
            if ($registro->sus_pfo>0)
            {
                $lunes = lunes($anno, $mes);
                $monto = ((($sueldo_mensual*12/52)*$registro->sus_pfo/100)*$lunes)/2;
                $total_descuentos += $monto;
                $descuentos_empleado[] = array(
                    'categoria' => $categoria_descuentos,
                    'partida' => $partida_pfo,
                    'id_descuento' => '2',
                    'descuento' => $monto,
                    'patrono' => $monto*4
                );
            }
            
            if ($registro->sus_lph>0)
            {
                $monto = ((($sueldo_mensual+(($hijos*2)*$_SESSION['prima_hijos'])+(($sueldo_mensual*($_SESSION['prima_prof'][intval($profesion)]))/100)+((($_SESSION['prima_anno'][intval($anos_servicio)])*$sueldo_mensual)/100))*$registro->sus_lph)/100)/2;
                $total_descuentos += $monto;
                $descuentos_empleado[] = array(
                    'categoria' => $categoria_descuentos,
                    'partida' => $partida_lph,
                    'id_descuento' => '3',
                    'descuento' => $monto,
                    'patrono' => $monto*2
                );
            }
            
            if ($registro->sus_fej>0)
            {
                $monto = (($sueldo_mensual*$registro->sus_fej)/100)/2;
                $total_descuentos += $monto;
                $descuentos_empleado[] = array(
                    'categoria' => $categoria_descuentos,
                    'partida' => $partida_fej,
                    'id_descuento' => '4',
                    'descuento' => $monto,
                    'patrono' => $monto
                );
            }
            
            //------------ ASIGNACIONES
            $asignaciones_empleado = array();
            
            // QUINCENA
            $total_asignaciones += $quincena;
            $asignaciones_empleado[] = array(
                'categoria' => $categoria,
                'partida' => $partida,
                'id_asignacion' => '1',
                'asignaciones' => $quincena
            );
            
            //------------ HIJOS
            if ($hijos>0)
            {
                $var1 = ($hijos*$_SESSION['prima_hijos']);
                $total_asignaciones += $var1;
                $asignaciones_empleado[] = array(
                    'categoria' => $categoria,
                    'partida' => $partida_hijos,
                    'id_asignacion' => '2',
                    'asignaciones' => $var1
                );
            }
            
            //------------ PROFESIÓN
            if ($profesion>0)
            {
                $var2 = ($quincena*($_SESSION['prima_prof'][intval($profesion)])/100);
                $total_asignaciones += $var2;
                $asignaciones_empleado[] = array(
                    'categoria' => $categoria,
                    'partida' => $partida_profesion,
                    'id_asignacion' => '3',
                    'asignaciones' => $var2
                );
            }
            
            //------------ ANTIGÜEDAD
            if ($anos_servicio>0 and ($codigo_nomina<>'005' and $codigo_nomina<>'006'))
            {
                $var3 = ($quincena*($_SESSION['prima_anno'][intval($anos_servicio)])/100);
                $total_asignaciones += $var3;
                $asignaciones_empleado[] = array(
                    'categoria' => $categoria,
                    'partida' => $partida_antiguedad,
                    'id_asignacion' => '4',
                    'asignaciones' => $var3
                );
            }
            
            //------------ AYUDA/BONO
            if ($ayuda>0)
            {
                $bono = bono($codigo_nomina, $cargo);
                $total_asignaciones += ($bono/2);
                $asignaciones_empleado[] = array(
                    'categoria' => $categoria,
                    'partida' => $partida,
                    'id_asignacion' => '14',
                    'asignaciones' => ($bono/2)
                );
            }
            
            // CALCULAR TOTAL NETO
            $total_neto = $total_asignaciones - $total_descuentos;
            
            // Agregar a array de nómina CON TOTALES CALCULADOS
            $valores_nomina[] = "('".$_SESSION['conexionsql']->real_escape_string($profesion1)."', '".$_SESSION['conexionsql']->real_escape_string($ingreso1)."', '$id_solicitud_sueldo', 1, '$sueldo_mensual', '$num_nomina', '$tipo_pago', '".$_SESSION['conexionsql']->real_escape_string($cargo)."', '$categoria', '".$_SESSION['conexionsql']->real_escape_string($ubicacion)."', '".$_SESSION['conexionsql']->real_escape_string($nomina)."', '$partida', '$cedula', ".anno($fecha).", '$fecha', 'PAGO DE QUINCENA', '$desde', '$hasta', $total_asignaciones, $total_descuentos, $total_neto, 0, '".$_SESSION['CEDULA_USUARIO']."')";
            
            // Guardar índice y cédula para este empleado
            $idx_empleado = count($valores_nomina) - 1;
            $cedulas_empleados[$idx_empleado] = $cedula;
            
            // Guardar asignaciones para este empleado
            if (!isset($valores_asignaciones[$idx_empleado])) {
                $valores_asignaciones[$idx_empleado] = array();
            }
            $valores_asignaciones[$idx_empleado] = $asignaciones_empleado;
            
            // Guardar descuentos para este empleado
            if (!empty($descuentos_empleado)) {
                if (!isset($valores_descuentos[$idx_empleado])) {
                    $valores_descuentos[$idx_empleado] = array();
                }
                $valores_descuentos[$idx_empleado] = $descuentos_empleado;
            }
        }
        
        //------------ VACACIONES (TIPO PAGO 003)
        if ($msg_vacaciones<>'no' and $vacacion<>1 and $_POST['OQUINCENA']<>'01' and $_POST['ovacaciones']==1 and ($codigo_nomina=='003' or $codigo_nomina=='004' or $codigo_nomina=='002' or $codigo_nomina=='001'))
        { 
            if ($anos_servicio>0 and $mes_ing==$mes)
            {
                $sueldo  = $quincena * 2;
                $dias = dias_vacaciones($anos_servicio);
                //----------
                $prima_prof = ($sueldo*($_SESSION['prima_prof'][intval($profesion)])/100);
                $prima_antiguedad = ($sueldo*($_SESSION['prima_anno'][intval($anos_servicio)])/100);
                $prima_hijos = (($hijos*2)*$_SESSION['prima_hijos']);
                $vacaciones = ($sueldo + $prima_prof + $prima_antiguedad + $prima_hijos)/30*$dias;
                $sueldo_mes = ($sueldo + $prima_prof + $prima_antiguedad + $prima_hijos);
                
                //---------
                if ($vacaciones>0)
                {
                    // Calcular totales para vacaciones
                    $total_asignaciones_vac = $vacaciones;
                    $total_descuentos_vac = 0;
                    $total_neto_vac = $vacaciones;
                    
                    // Insertar con la misma estructura que quincena (23 columnas)
                    $valores_nomina[] = "('', '', '$id_solicitud_vaca', 1, '$sueldo_mensual', '$num_nominav', '003', '".$_SESSION['conexionsql']->real_escape_string($cargo)."', '$categoria', '".$_SESSION['conexionsql']->real_escape_string($ubicacion)."', '".$_SESSION['conexionsql']->real_escape_string($nomina)."', '$partida_vacaciones', '$cedula', ".anno($fecha).", '$fecha', 'PAGO DE VACACIONES', '$desde_m', '$hasta', $total_asignaciones_vac, $total_descuentos_vac, $total_neto_vac, 0, '".$_SESSION['CEDULA_USUARIO']."')";
                    
                    $idx_vacaciones = count($valores_nomina) - 1;
                    $cedulas_empleados[$idx_vacaciones] = $cedula;
                    
                    // Asignación de vacaciones
                    if (!isset($valores_asignaciones[$idx_vacaciones])) {
                        $valores_asignaciones[$idx_vacaciones] = array();
                    }
                    $valores_asignaciones[$idx_vacaciones][] = array(
                        'categoria' => $categoria,
                        'partida' => $partida_vacaciones,
                        'id_asignacion' => '12',
                        'asignaciones' => $vacaciones
                    );
                }
            }
        }
        
        //------------ CESTATICKETS (TIPO PAGO 002)
        if ($msg_tickets<>'no' and $tickets>0 and $_POST['OQUINCENA']<>'01' and $_POST['otickets']==1 and ($codigo_nomina=='003' or $codigo_nomina=='004' or $codigo_nomina=='002' or $codigo_nomina=='001'))
        {
            $cestatickets = valortickets(); 
            if ($cestatickets>0)
            {
                //----- DESCUENTO DE TICKETS
                if ($des_tickets>0)
                {
                    $cestatickets = ($cestatickets / 30) * (30-$des_tickets) ;
                }
                
                // Calcular totales para tickets
                $total_asignaciones_tickets = $cestatickets;
                $total_descuentos_tickets = 0;
                $total_neto_tickets = $cestatickets;
                
                // Insertar con la misma estructura que quincena (23 columnas)
                $valores_nomina[] = "('', '', '$id_solicitud_tickets', 1, '$sueldo_mensual', '$num_nominac', '002', '".$_SESSION['conexionsql']->real_escape_string($cargo)."', '$categoria', '".$_SESSION['conexionsql']->real_escape_string($ubicacion)."', '".$_SESSION['conexionsql']->real_escape_string($nomina)."', '$partida_tickets', '$cedula', ".anno($fecha).", '$fecha', 'PAGO DE CESTATICKETS', '$desde_m', '$hasta', $total_asignaciones_tickets, $total_descuentos_tickets, $total_neto_tickets, 0, '".$_SESSION['CEDULA_USUARIO']."')";
                
                $idx_tickets = count($valores_nomina) - 1;
                $cedulas_empleados[$idx_tickets] = $cedula;
                
                // Asignación de tickets
                if (!isset($valores_asignaciones[$idx_tickets])) {
                    $valores_asignaciones[$idx_tickets] = array();
                }
                $valores_asignaciones[$idx_tickets][] = array(
                    'categoria' => $categoria_tickets,
                    'partida' => $partida_tickets,
                    'id_asignacion' => '13',
                    'asignaciones' => $cestatickets
                );
            }
        }
    }
    
    //------------- EJECUTAR INSERT MASIVO DE NÓMINA
    if (!empty($valores_nomina)) {
        $sql_nomina = "INSERT INTO nomina (profesion1, ingreso1, id_solicitud, lote, sueldo_mensual, num_nomina, tipo_pago, cargo, categoria, ubicacion, nomina, partida, cedula, anno, fecha, descripcion, desde, hasta, asignaciones, descuentos, total, estatus, usuario) VALUES " . implode(", ", $valores_nomina);
        
        if (!$_SESSION['conexionsql']->query($sql_nomina)) {
            throw new Exception("Error al insertar nóminas: " . $_SESSION['conexionsql']->error);
        }
        
        // Obtener el primer ID insertado
        $primer_id = $_SESSION['conexionsql']->insert_id;
        
        //------------- EJECUTAR INSERT MASIVO DE ASIGNACIONES
        if (!empty($valores_asignaciones)) {
            $valores_asig_sql = array();
            foreach ($valores_asignaciones as $idx => $asignaciones) {
                $id_nomina = $primer_id + $idx;
                $cedula_empleado = $cedulas_empleados[$idx];
                foreach ($asignaciones as $asig) {
                    $valores_asig_sql[] = "($id_nomina, '{$asig['categoria']}', '".$_SESSION['conexionsql']->real_escape_string($nomina)."', '{$asig['partida']}', '$cedula_empleado', {$asig['id_asignacion']}, {$asig['asignaciones']})";
                }
            }
            
            if (!empty($valores_asig_sql)) {
                $sql_asignaciones = "INSERT INTO nomina_asignaciones (id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES " . implode(", ", $valores_asig_sql);
                
                if (!$_SESSION['conexionsql']->query($sql_asignaciones)) {
                    throw new Exception("Error al insertar asignaciones: " . $_SESSION['conexionsql']->error);
                }
            }
        }
        
        //------------- EJECUTAR INSERT MASIVO DE DESCUENTOS
        if (!empty($valores_descuentos)) {
            $valores_desc_sql = array();
            foreach ($valores_descuentos as $idx => $descuentos) {
                $id_nomina = $primer_id + $idx;
                $cedula_empleado = $cedulas_empleados[$idx];
                foreach ($descuentos as $desc) {
                    $valores_desc_sql[] = "($id_nomina, '{$desc['categoria']}', '".$_SESSION['conexionsql']->real_escape_string($nomina)."', '{$desc['partida']}', '$cedula_empleado', {$desc['id_descuento']}, {$desc['descuento']}, {$desc['patrono']})";
                }
            }
            
            if (!empty($valores_desc_sql)) {
                $sql_descuentos = "INSERT INTO nomina_descuentos (id_nomina, categoria, nomina, partida, cedula, id_descuento, descuento, patrono) VALUES " . implode(", ", $valores_desc_sql);
                
                if (!$_SESSION['conexionsql']->query($sql_descuentos)) {
                    throw new Exception("Error al insertar descuentos: " . $_SESSION['conexionsql']->error);
                }
            }
        }
    }
    
    //------------- LIMPIAR ASIGNACIONES EN CERO
    $consultax = "DELETE FROM nomina_asignaciones WHERE asignaciones = 0 or TRIM(partida)='';"; 
    $_SESSION['conexionsql']->query($consultax);
    
	//------------- ACTUALIZAR TOTALES (solo si se insertaron registros)
	if (isset($primer_id) && $primer_id > 0) {
		$consultx = "CALL actualizar_quincenas_new('$primer_id');";
		$tablax = $_SESSION['conexionsql']->query($consultx);
	}

    //------------- RESTAURAR CONFIGURACIONES Y COMMIT
    $_SESSION['conexionsql']->query("SET unique_checks=1");
    $_SESSION['conexionsql']->query("SET foreign_key_checks=1");
    $_SESSION['conexionsql']->query("SET autocommit=1");
    $_SESSION['conexionsql']->commit();
    
    $tiempo_fin = microtime(true);
    $tiempo_total = round($tiempo_fin - $tiempo_inicio, 2);
    
    $mensaje = "Nomina Generada Exitosamente! Procesados: $contador_empleados empleados en $tiempo_total segundos";
    $tipo = 'info';
    
} catch (Exception $e) {
    //------------- ROLLBACK EN CASO DE ERROR
    $_SESSION['conexionsql']->query("SET unique_checks=1");
    $_SESSION['conexionsql']->query("SET foreign_key_checks=1");
    $_SESSION['conexionsql']->query("SET autocommit=1");
    $_SESSION['conexionsql']->rollback();
    $mensaje = "Error al generar nómina: " . $e->getMessage();
    $tipo = 'error';
}

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>