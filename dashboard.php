<?php
session_start();
include_once "conexion.php";
include_once "funciones/auxiliar_php.php";

/**
 * Obtiene las estadísticas de asistencia para un rango de fechas.
 * @param string $fecha_inicio La fecha de inicio en formato 'Y-m-d'.
 * @param string $fecha_fin La fecha de fin en formato 'Y-m-d'.
 * @param mysqli $conexion El objeto de conexión a la base de datos.
 * @return array Un array con las estadísticas por día.
 */
function obtenerEstadisticasSemana($fecha_inicio, $fecha_fin, $conexion)
{
    $datos_semana = [];
    $fecha_actual = new DateTime($fecha_inicio);
    $fecha_final = new DateTime($fecha_fin);

    // Formateador para obtener nombres de días en español
    $formateador = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'America/Caracas', IntlDateFormatter::GREGORIAN, 'EEEE');

    while ($fecha_actual <= $fecha_final) {
        $dia_semana = $fecha_actual->format('N'); // 1 (para lunes) hasta 7 (para domingo)
        if ($dia_semana < 6) { // Omitir sábado (6) y domingo (7)
            $fecha = $fecha_actual->format('Y-m-d');
            $nombre_dia = ucfirst($formateador->format($fecha_actual));
            $dia_mes = $fecha_actual->format('d');
            $etiqueta = "$nombre_dia $dia_mes";

            // Inicializar estadísticas para el día
            $stats = [
                'a_tiempo' => 0,
                'tarde' => 0,
            ];

            // Contar llegadas a tiempo
            $consulta_tiempo = "SELECT COUNT(DISTINCT cedula) as cantidad FROM asistencia_diaria WHERE fecha = '$fecha' AND estatus = 0 AND tipo = 'ENTRADA';";
            $resultado_tiempo = $conexion->query($consulta_tiempo);
            if ($fila = $resultado_tiempo->fetch_assoc()) {
                $stats['a_tiempo'] = (int)$fila['cantidad'];
            }

            // Contar llegadas tarde
            $consulta_tarde = "SELECT COUNT(DISTINCT cedula) as cantidad FROM asistencia_diaria WHERE fecha = '$fecha' AND estatus = 1 AND tipo = 'ENTRADA';";
            $resultado_tarde = $conexion->query($consulta_tarde);
            if ($fila = $resultado_tarde->fetch_assoc()) {
                $stats['tarde'] = (int)$fila['cantidad'];
            }

            $datos_semana[$etiqueta] = $stats;
        }
        $fecha_actual->modify('+1 day');
    }

    return $datos_semana;
}

$fecha_fin_semana = date('Y-m-d');
$fecha_inicio_semana = date('Y-m-d', strtotime('-14 days'));

$datos_grafico = obtenerEstadisticasSemana($fecha_inicio_semana, $fecha_fin_semana, $_SESSION['conexionsql']);

// Conteo de llegadas tarde del día actual (estatus=1, tipo ENTRADA)
$fecha_hoy = date('Y-m-d');
$consulta_tarde_hoy = "SELECT COUNT(DISTINCT cedula) AS c FROM asistencia_diaria WHERE fecha = '$fecha_hoy' AND estatus = 1 AND tipo='ENTRADA';";
$tarde_hoy = 0;
$rt_hoy = $_SESSION['conexionsql']->query($consulta_tarde_hoy);
if ($rt_hoy && ($fx = $rt_hoy->fetch_assoc())) {
    $tarde_hoy = (int)$fx['c'];
}

// Preparar los datos para Chart.js
$labels = array_keys($datos_grafico);
$datos_a_tiempo = array_column($datos_grafico, 'a_tiempo');
$datos_tarde = array_column($datos_grafico, 'tarde');

// --- Lógica para obtener la última evaluación ---
function obtenerDatosEvaluacion($conexion)
{
    // Estructura base con nuevos campos para estatus
    $datos = [
        'listos' => 0,
        'faltantes' => 0,
        'total' => 0,
        'titulo' => 'Sin evaluación activa',
        'estatus' => null,
        'estatus_texto' => 'Sin proceso'
    ];

    // 1. Obtener la última evaluación (si se prefiere solo activa usar WHERE estatus<10)
    $id_evaluacion = 0;
    $consulta_eval = "SELECT id, descripcion, estatus FROM evaluaciones ORDER BY id DESC LIMIT 1;";
    $resultado_eval = $conexion->query($consulta_eval);
    if ($resultado_eval && $fila_eval = $resultado_eval->fetch_assoc()) {
        $id_evaluacion = (int)$fila_eval['id'];
        $datos['titulo'] = $fila_eval['descripcion'];
        $datos['estatus'] = is_numeric($fila_eval['estatus']) ? (int)$fila_eval['estatus'] : null;
        if ($datos['estatus'] !== null && isset($_SESSION['estatus_odi'][$datos['estatus']])) {
            $datos['estatus_texto'] = $_SESSION['estatus_odi'][$datos['estatus']];
        }
    }

    if ($id_evaluacion > 0) {
        // 2. Total de funcionarios evaluables
        $consulta_total = "SELECT COUNT(cedula) as cantidad FROM rac WHERE evaluar_odis=1 AND nomina <> 'EGRESADOS' AND nomina <> 'JUBILADOS' AND nomina <> 'PENSIONADO';";
        $resultado_total = $conexion->query($consulta_total);
        if ($resultado_total && $fila_total = $resultado_total->fetch_assoc()) {
            $datos['total'] = (int)$fila_total['cantidad'];
        }

        // Si no se obtuvo estatus numérico, asumir 1 para evitar división por cero
        $estatus_actual = ($datos['estatus'] !== null) ? (int)$datos['estatus'] : 1;

        // 3. Funcionarios listos: odis >= estatus actual (avance alcanzado o superado)
        $consulta_listos = "SELECT COUNT(cedula) as cantidad FROM rac WHERE evaluar_odis=1 AND odis >= $estatus_actual AND nomina <> 'EGRESADOS' AND nomina <> 'JUBILADOS' AND nomina <> 'PENSIONADO';";
        $resultado_listos = $conexion->query($consulta_listos);
        if ($resultado_listos && $fila_listos = $resultado_listos->fetch_assoc()) {
            $datos['listos'] = (int)$fila_listos['cantidad'];
        }

        // 4. Funcionarios faltantes: odis < estatus actual
        $consulta_falt = "SELECT COUNT(cedula) as cantidad FROM rac WHERE evaluar_odis=1 AND odis < $estatus_actual AND nomina <> 'EGRESADOS' AND nomina <> 'JUBILADOS' AND nomina <> 'PENSIONADO';";
        $resultado_falt = $conexion->query($consulta_falt);
        if ($resultado_falt && $fila_falt = $resultado_falt->fetch_assoc()) {
            $datos['faltantes'] = (int)$fila_falt['cantidad'];
        } else {
            // Fallback si falla la consulta directa
            $datos['faltantes'] = max(0, $datos['total'] - $datos['listos']);
        }
    }

    return $datos;
}

$datos_evaluacion = obtenerDatosEvaluacion($_SESSION['conexionsql']);
$labels_eval = ['Listos', 'Faltantes'];
$valores_eval = [$datos_evaluacion['listos'], $datos_evaluacion['faltantes']];

// --- Lógica para obtener las últimas órdenes de compra ---
function obtenerUltimasCompras($conexion)
{
    $compras = [];
    $fecha_semana = date('Y-m-d', strtotime('-14 days'));
    // Volver a JOIN directo (rif se usará como índice luego)
    $consulta = "SELECT 
                    os.id,
                    os.numero,
                    os.anno,
                    os.tipo_orden,
                    os.fecha,
                    os.id_contribuyente,
                    c.rif,
                    c.nombre AS razon_social,
                    os.descripcion AS concepto,
                    os.estatus,
                    os.asignaciones AS monto
                FROM orden_solicitudes AS os
                JOIN contribuyente AS c ON c.id = os.id_contribuyente
                WHERE os.fecha >= '$fecha_semana' AND os.tipo_orden IN ('CD','CP','CC')
                ORDER BY os.fecha DESC, os.numero DESC
                LIMIT 10;";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $compras[] = $fila;
        }
    }
    return $compras;
}

$ultimas_compras = obtenerUltimasCompras($_SESSION['conexionsql']);

// --- Lógica para obtener las últimas órdenes de pago ---
function obtenerUltimasOrdenesPago($conexion)
{
    $ordenes_pago = [];
    $fecha_semana = date('Y-m-d', strtotime('-14 days'));
    $consulta = "SELECT 
                    o.id,
                    o.numero,
                    YEAR(o.fecha) AS anno,
                    o.tipo_solicitud AS tipo_orden,
                    o.fecha,
                    o.id_contribuyente,
                    c.rif,
                    c.nombre AS razon_social,
                    o.descripcion AS concepto,
                    o.estatus,
                    o.total AS monto
                FROM ordenes_pago AS o
                JOIN contribuyente AS c ON c.id = o.id_contribuyente
                WHERE o.fecha >= '$fecha_semana' AND o.numero > 0
                ORDER BY o.fecha DESC, o.numero DESC
                LIMIT 10;";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $ordenes_pago[] = $fila;
        }
    }
    return $ordenes_pago;
}

$ultimas_ordenes_pago = obtenerUltimasOrdenesPago($_SESSION['conexionsql']);

// ====================
// SECCIÓN: PRESUPUESTO (Año actual)
// ====================
$anio_actual = date('Y');

function obtenerResumenPresupuestoAnual($conexion, $anio)
{
    $tabla = 'a_presupuesto_' . intval($anio);
    $res = [
        'vigente' => 0.0,
        'comprometido' => 0.0,
        'causado' => 0.0,
        'pagado' => 0.0,
        'disponible' => 0.0,
        'dona' => [
            'pagado' => 0.0,
            'causado_no_pagado' => 0.0,
            'comprometido_no_causado' => 0.0,
            'disponible' => 0.0
        ]
    ];
    // Solo partidas madre (categoria NULL o vacía) para evitar sumar dos veces (madre + hijas)
    // Alinear con 1b_tabla.php: Total Asignación = original + creditos + ingreso - egreso
    $sql = "SELECT SUM(original) AS original, SUM(creditos) AS creditos, SUM(ingreso) AS ingreso, SUM(egreso) AS egreso,\n                   SUM(compromiso) AS compromiso, SUM(causado) AS causado, SUM(pagado) AS pagado\n            FROM `$tabla` WHERE activo = 1 AND (categoria IS NULL OR categoria = '')";
    if ($rs = $conexion->query($sql)) {
        if ($row = $rs->fetch_assoc()) {
            $original = (float)($row['original'] ?? 0);
            $creditos = (float)($row['creditos'] ?? 0);
            $ingreso = (float)($row['ingreso'] ?? 0);
            $egreso = (float)($row['egreso'] ?? 0);
            $compromiso = (float)($row['compromiso'] ?? 0);
            $causado = (float)($row['causado'] ?? 0);
            $pagado = (float)($row['pagado'] ?? 0);

            // Alinear con "Total Asignación" mostrado en 1b_tabla.php
            $vigente = $original + $creditos + $ingreso - $egreso;
            // Consistencia
            $compromiso = max(0, min($compromiso, $vigente));
            $causado = max(0, min($causado, $compromiso));
            $pagado = max(0, min($pagado, $causado));
            $disponible = max(0, $vigente - $compromiso);

            $res['vigente'] = $vigente;
            $res['comprometido'] = $compromiso;
            $res['causado'] = $causado;
            $res['pagado'] = $pagado;
            $res['disponible'] = $disponible;

            $res['dona']['pagado'] = $pagado;
            $res['dona']['causado_no_pagado'] = max(0, $causado - $pagado);
            $res['dona']['comprometido_no_causado'] = max(0, $compromiso - $causado);
            $res['dona']['disponible'] = $disponible;
        }
    }
    return $res;
}

function obtenerTopPartidasEjecucion($conexion, $anio, $limite = 10)
{
    // Agregación por Título (categoria) con métricas comparables
    // - Vigente: tomado de la partida madre (p)
    // - Comprometido y Pagado: suma de partidas hijas (t)
    $tabla = 'a_presupuesto_' . intval($anio);
    $top = [];
    $sql = "SELECT 
                t.categoria AS codigo,
                COALESCE(p.descripcion, CONCAT('Título ', t.categoria)) AS descripcion,
                MAX(p.original + p.creditos + p.ingreso - p.egreso) AS vigente,
                SUM(t.compromiso) AS comprometido,
                SUM(t.pagado) AS pagado
            FROM `$tabla` t
            LEFT JOIN `$tabla` p ON p.codigo = t.categoria AND (p.categoria IS NULL OR p.categoria = '')
            WHERE t.activo = 1 AND t.categoria IS NOT NULL AND t.categoria <> ''
            GROUP BY t.categoria
            HAVING (pagado > 0 OR comprometido > 0 OR vigente > 0)
            ORDER BY pagado DESC
            LIMIT " . intval($limite);
    if ($rs = $conexion->query($sql)) {
        while ($row = $rs->fetch_assoc()) {
            // Consistencias por grupo
            $vig = (float)($row['vigente'] ?? 0);
            $com = (float)($row['comprometido'] ?? 0);
            $pag = (float)($row['pagado'] ?? 0);
            $com = max(0, min($com, $vig));
            $pag = max(0, min($pag, $com));
            $row['vigente'] = $vig;
            $row['comprometido'] = $com;
            $row['pagado'] = $pag;
            $top[] = $row;
        }
    }
    return $top;
}

function obtenerAlertasDisponibilidadPresupuesto($conexion, $anio, $umbral = 10, $limite = 5)
{
    $tabla = 'a_presupuesto_' . intval($anio);
    $alertas = [];
    // Alinear con Total Asignación del módulo de presupuesto
    $sql = "SELECT codigo, descripcion, original, creditos, ingreso, egreso, compromiso FROM `$tabla` WHERE activo = 1 AND (categoria IS NULL OR categoria = '')";
    if ($rs = $conexion->query($sql)) {
        while ($r = $rs->fetch_assoc()) {
            $orig = (float)($r['original'] ?? 0);
            $cred = (float)($r['creditos'] ?? 0);
            $ingr = (float)($r['ingreso'] ?? 0);
            $egr = (float)($r['egreso'] ?? 0);
            $comp = (float)($r['compromiso'] ?? 0);
            $vig = $orig + $cred + $ingr - $egr;
            if ($vig <= 0) continue;
            $disp = max(0, $vig - $comp);
            $porc = ($vig > 0) ? ($disp * 100.0 / $vig) : 0;
            if ($porc <= $umbral) {
                $alertas[] = [
                    'codigo' => $r['codigo'],
                    'descripcion' => $r['descripcion'],
                    'vigente' => $vig,
                    'disponible' => $disp,
                    'porc' => $porc
                ];
            }
        }
    }
    usort($alertas, function ($a, $b) {
        if ($a['porc'] == $b['porc']) return 0;
        return ($a['porc'] < $b['porc']) ? -1 : 1;
    });
    return array_slice($alertas, 0, $limite);
}

$resumen_presupuesto = obtenerResumenPresupuestoAnual($_SESSION['conexionsql'], $anio_actual);
// Top por categoría (títulos): agrega el causado de partidas hijas agrupadas por el código del título (campo `categoria`)
function obtenerTopCategoriasEjecucion($conexion, $anio, $limite = 10)
{
    $tabla = 'a_presupuesto_' . intval($anio);
    $top = [];
    // t = partidas hijas (categoria NOT NULL). p = fila del título (categoria NULL) para obtener su descripción y vigente
    $sql = "SELECT 
                t.categoria AS codigo,
                COALESCE(p.descripcion, CONCAT('Título ', t.categoria)) AS descripcion,
                MAX(p.original + p.creditos + p.ingreso - p.egreso) AS vigente,
                SUM(t.compromiso) AS comprometido,
                SUM(t.pagado) AS pagado
            FROM `$tabla` t
            LEFT JOIN `$tabla` p ON p.codigo = t.categoria AND (p.categoria IS NULL OR p.categoria = '')
            WHERE t.activo = 1 AND t.categoria IS NOT NULL AND t.categoria <> ''
            GROUP BY t.categoria
            HAVING (pagado > 0 OR comprometido > 0 OR vigente > 0)
            ORDER BY pagado DESC
            LIMIT " . intval($limite);
    if ($rs = $conexion->query($sql)) {
        while ($row = $rs->fetch_assoc()) {
            $vig = (float)($row['vigente'] ?? 0);
            $com = (float)($row['comprometido'] ?? 0);
            $pag = (float)($row['pagado'] ?? 0);
            $com = max(0, min($com, $vig));
            $pag = max(0, min($pag, $com));
            $row['vigente'] = $vig;
            $row['comprometido'] = $com;
            $row['pagado'] = $pag;
            $top[] = $row;
        }
    }
    return $top;
}

// Usar agregación por títulos/categorías para el gráfico de barras
$top_partidas = obtenerTopCategoriasEjecucion($_SESSION['conexionsql'], $anio_actual, 10);

// ====================
// RESUMEN: Partidas madre por prefijo (primeros 3 dígitos)
// ====================
function obtenerResumenPorPartidaMadre($conexion, $anio)
{
    $tabla = 'a_presupuesto_' . intval($anio);
    $out = [];
    // Listado de TODAS las partidas madre (sin agrupar por prefijo)
    $sql = "SELECT 
                codigo,
                (original + creditos + ingreso - egreso) AS vigente,
                compromiso,
                causado,
                pagado
            FROM `$tabla`
            WHERE activo = 1 AND (categoria IS NULL OR categoria = '') AND codigo IS NOT NULL AND codigo <> ''
            ORDER BY codigo ASC";
    if ($rs = $conexion->query($sql)) {
        while ($r = $rs->fetch_assoc()) {
            $vig = (float)($r['vigente'] ?? 0);
            $com = (float)($r['compromiso'] ?? 0);
            $cau = (float)($r['causado'] ?? 0);
            $pag = (float)($r['pagado'] ?? 0);
            // Consistencias por fila
            $com = max(0, min($com, $vig));
            $cau = max(0, min($cau, $com));
            $pag = max(0, min($pag, $cau));
            $disp = max(0, $vig - $com);
            $out[] = [
                'codigo' => $r['codigo'],
                'vigente' => $vig,
                'compromiso' => $com,
                'causado' => $cau,
                'pagado' => $pag,
                'disponible' => $disp
            ];
        }
    }
    return $out;
}

// Prefijos (primeros 3 dígitos) de partidas hijas pertenecientes a una categoría madre (codigo madre)
function obtenerPrefijosPorCategoria($conexion, $anio, $codigoMadre)
{
    $tabla = 'a_presupuesto_' . intval($anio);
    $out = [];
    $codigoMadre = $conexion->real_escape_string($codigoMadre);
    $sql = "SELECT 
                SUBSTRING(codigo,1,3) AS prefijo,
                SUM(original + creditos + ingreso - egreso) AS vigente,
                SUM(compromiso) AS compromiso,
                SUM(causado) AS causado,
                SUM(pagado) AS pagado
            FROM `$tabla`
            WHERE activo = 1 AND categoria = '$codigoMadre' AND codigo IS NOT NULL AND codigo <> ''
            GROUP BY SUBSTRING(codigo,1,3)
            ORDER BY prefijo ASC";
    if ($rs = $conexion->query($sql)) {
        while ($r = $rs->fetch_assoc()) {
            $vig = (float)($r['vigente'] ?? 0);
            $com = (float)($r['compromiso'] ?? 0);
            $cau = (float)($r['causado'] ?? 0);
            $pag = (float)($r['pagado'] ?? 0);
            $com = max(0, min($com, $vig));
            $cau = max(0, min($cau, $com));
            $pag = max(0, min($pag, $cau));
            $disp = max(0, $vig - $com);
            $out[] = [
                'prefijo' => $r['prefijo'],
                'vigente' => $vig,
                'compromiso' => $com,
                'causado' => $cau,
                'pagado' => $pag,
                'disponible' => $disp
            ];
        }
    }
    return $out;
}

$resumen_madres = obtenerResumenPorPartidaMadre($_SESSION['conexionsql'], $anio_actual);
$alertas_pres = obtenerAlertasDisponibilidadPresupuesto($_SESSION['conexionsql'], $anio_actual, 10, 5);

// ====================
// SECCIÓN: RESUMEN DE ALMACÉN
// ====================
$fecha_inicio_almacen = date('Y-m-d', strtotime('-14 days'));
$fecha_fin_almacen = date('Y-m-d');

function obtenerSalidasPorArea($conexion, $desde, $hasta)
{
    // Contabiliza la cantidad de solicitudes (documentos) por área en el rango dado
    $areas = [];
    $sql = "SELECT COALESCE(d.direccion, 'Sin área') AS area, s.division, COUNT(DISTINCT sd.id_solicitud) AS total
            FROM bn_solicitudes_detalle sd
            JOIN bn_solicitudes s ON s.id = sd.id_solicitud
            LEFT JOIN a_direcciones d ON d.id = s.division
            WHERE sd.estatus = 10 AND s.fecha >= '$desde' AND s.fecha <= '$hasta'
            GROUP BY s.division
            ORDER BY total DESC
            LIMIT 10;";
    if ($rs = $conexion->query($sql)) {
        while ($row = $rs->fetch_assoc()) {
            $areas[] = $row;
        }
    }
    return $areas;
}

function obtenerTopArticulosSalida($conexion, $desde, $hasta, $limite = 7)
{
    $articulos = [];
    $sql = "SELECT m.descripcion_bien AS articulo, m.unidad, sd.id_bien, SUM(sd.cant_aprobada) AS total
            FROM bn_solicitudes_detalle sd
            JOIN bn_materiales m ON m.id_bien = sd.id_bien
            WHERE sd.estatus = 10 AND sd.fecha >= '$desde' AND sd.fecha <= '$hasta'
            GROUP BY sd.id_bien
            ORDER BY total DESC
            LIMIT $limite;";
    if ($rs = $conexion->query($sql)) {
        while ($row = $rs->fetch_assoc()) {
            $articulos[] = $row;
        }
    }
    return $articulos;
}

function obtenerUltimosMovimientosAlmacen($conexion, $limite = 20, $desde = null, $hasta = null)
{
    if ($desde === null) {
        $desde = date('Y-m-d', strtotime('-6 days'));
    }
    if ($hasta === null) {
        $hasta = date('Y-m-d');
    }
    $movs = [];
    // Resumen de Salidas por documento (bn_solicitudes)
    $sql_sal = "SELECT s.id, s.fecha, s.numero, d.direccion AS area, SUM(sd.cant_aprobada) AS total_cantidad, COUNT(sd.id_detalle) AS renglones
                FROM bn_solicitudes s
                JOIN a_direcciones d ON d.id = s.division
                JOIN bn_solicitudes_detalle sd ON sd.id_solicitud = s.id
                WHERE sd.estatus = 10 AND s.fecha >= '$desde' AND s.fecha <= '$hasta'
                GROUP BY s.id
                ORDER BY s.fecha DESC, s.numero DESC";
    if ($rs = $conexion->query($sql_sal)) {
        while ($r = $rs->fetch_assoc()) {
            $movs[] = [
                'tipo' => 'SALIDA',
                'id' => (int)$r['id'],
                'fecha' => $r['fecha'],
                'numero' => $r['numero'],
                'area' => $r['area'],
                'total_cantidad' => (float)$r['total_cantidad'],
                'renglones' => (int)$r['renglones']
            ];
        }
    }
    // Resumen de Ingresos por documento (bn_ingresos)
    $sql_ent = "SELECT i.id, i.fecha, i.numero, d.direccion AS area, SUM(di.cantidad) AS total_cantidad, COUNT(di.id) AS renglones
                FROM bn_ingresos i
                JOIN a_direcciones d ON d.id = i.division
                JOIN bn_ingresos_detalle di ON di.id_ingreso = i.id
                WHERE di.estatus = 10 AND i.fecha >= '$desde' AND i.fecha <= '$hasta'
                GROUP BY i.id
                ORDER BY i.fecha DESC, i.numero DESC";
    if ($rs = $conexion->query($sql_ent)) {
        while ($r = $rs->fetch_assoc()) {
            $movs[] = [
                'tipo' => 'INGRESO',
                'id' => (int)$r['id'],
                'fecha' => $r['fecha'],
                'numero' => $r['numero'],
                'area' => $r['area'],
                'total_cantidad' => (float)$r['total_cantidad'],
                'renglones' => (int)$r['renglones']
            ];
        }
    }
    // Ordenar por fecha descendente, y como tie-breaker por numero desc
    usort($movs, function ($a, $b) {
        $cmp = strcmp($b['fecha'], $a['fecha']);
        if ($cmp !== 0) return $cmp;
        return (int)$b['numero'] <=> (int)$a['numero'];
    });
    return array_slice($movs, 0, $limite);
}

$salidas_por_area = obtenerSalidasPorArea($_SESSION['conexionsql'], $fecha_inicio_almacen, $fecha_fin_almacen);
$top_articulos_salida = obtenerTopArticulosSalida($_SESSION['conexionsql'], $fecha_inicio_almacen, $fecha_fin_almacen, 7);
$ultimos_movs_almacen = obtenerUltimosMovimientosAlmacen($_SESSION['conexionsql'], 20, $fecha_inicio_almacen, $fecha_fin_almacen);
?>

<div id="dashboard-root" class="container-fluid pt-3 px-0">
    <div class="row section-row">
        <div class="col-lg-6 col-asistencia">
            <div class="card h-100">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Asistencia de la Semana</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-3">
                        <canvas id="sales-chart" height="180"></canvas>
                    </div>
                    <div class="d-flex flex-row justify-content-end small">
                        <span class="mr-2">
                            <i class="fas fa-square" style="color:#2f6fab;"></i> A Tiempo
                        </span>
                        <span>
                            <i class="fas fa-square" style="color:#adb5bd;"></i> Tarde
                        </span>
                    </div>
                    <div class="mt-3">
                        <div class="alert alert-danger py-2 mb-0 cursor-pointer listado-retardos text-center" data-fecha="<?php echo $fecha_hoy; ?>" style="cursor:pointer; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:6px;">
                            <span style="font-size:14px; font-weight:600; text-decoration:underline; display:flex; align-items:center; gap:6px;">
                                <i class="far fa-clock"></i>
                                Llegadas tarde (Hoy)
                            </span>
                            <span style="font-size:22px; font-weight:700; line-height:1;">&nbsp;<?php echo $tarde_hoy; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-evaluacion">
            <div class="card h-100">
                <div class="card-header border-0">
                    <h3 class="card-title"><?php echo $datos_evaluacion['titulo']; ?></h3>
                </div>
                <div class="card-body">
                    <!-- Contenedor relativo para centrar el título sobre el canvas -->
                    <div class="position-relative d-flex justify-content-center align-items-center mb-2" style="min-height: 220px;">
                        <canvas id="donut-chart" style="min-height: 220px; height: 220px; max-height: 220px; max-width: 100%;"></canvas>
                        <div class="position-absolute text-center" style="pointer-events:none;">
                            <div style="font-weight:700; font-size:1rem; line-height:1.2; white-space:nowrap;">&nbsp;<?php echo htmlspecialchars($datos_evaluacion['estatus_texto']); ?>&nbsp;</div>
                        </div>
                    </div>
                    <!-- Fila de contadores en una sola línea debajo del gráfico -->
                    <div class="d-flex flex-row justify-content-center align-items-stretch" style="gap:12px;">
                        <div class="alert alert-success text-center py-2 mb-0 cursor-pointer listado-func" data-tipo="listos" style="cursor:pointer; min-width:180px;">
                            <strong style="font-size:13px; text-decoration:underline; display:block;">Listos</strong>
                            <h2 class="mb-0" style="font-size:28px; font-weight:700;">&nbsp;<?php echo $datos_evaluacion['listos']; ?></h2>
                        </div>
                        <div class="alert alert-warning text-center py-2 mb-0 cursor-pointer listado-func" data-tipo="faltantes" style="cursor:pointer; min-width:180px;">
                            <strong style="font-size:13px; text-decoration:underline; display:block;">Faltantes</strong>
                            <h2 class="mb-0" style="font-size:28px; font-weight:700;">&nbsp;<?php echo $datos_evaluacion['faltantes']; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row section-row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Órdenes de Compra (Últimos 15 días)</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0" id="tabla-compras">
                            <thead>
                                <tr>
                                    <th>Nro. Orden</th>
                                    <th>Fecha</th>
                                    <th>RIF</th>
                                    <th>Proveedor</th>
                                    <th>Concepto</th>
                                    <th>Estado</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($ultimas_compras)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No hay compras recientes.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $idx_row = 0;
                                    foreach ($ultimas_compras as $compra):
                                        $nro_orden = htmlspecialchars($compra['tipo_orden'] . '-' . str_pad($compra['numero'], 3, '0', STR_PAD_LEFT) . '-' . $compra['anno']);
                                        $id_encriptado = encriptar($compra['id']);
                                        $rif_compra = isset($compra['rif']) ? trim($compra['rif']) : '';
                                        $fecha_compra = isset($compra['fecha']) ? date('d/m/Y', strtotime($compra['fecha'])) : '';
                                    ?>
                                        <tr class="fila-compras" <?php echo ($idx_row++ >= 5) ? 'style="display:none;"' : ''; ?>>
                                            <td><a href="#" onclick="imprimirCompra('<?php echo $id_encriptado; ?>', '<?php echo htmlspecialchars($compra['tipo_orden']); ?>'); return false;"><?php echo $nro_orden; ?></a></td>
                                            <td><?php echo $fecha_compra !== '' ? htmlspecialchars($fecha_compra) : '—'; ?></td>
                                            <td><?php echo $rif_compra !== '' ? htmlspecialchars($rif_compra) : '—'; ?></td>
                                            <td class="resalta-proveedor"><?php echo isset($compra['razon_social']) && trim($compra['razon_social']) !== '' ? htmlspecialchars($compra['razon_social']) : '—'; ?></td>
                                            <td><?php echo htmlspecialchars($compra['concepto']); ?></td>
                                            <td>
                                                <?php
                                                $estatus = (int)$compra['estatus'];
                                                $badge_class = 'badge-secondary';
                                                $texto_estatus = 'Desconocido';
                                                switch ($estatus) {
                                                    case 0:
                                                        $badge_class = 'badge-secondary';
                                                        $texto_estatus = 'En Proceso';
                                                        break;
                                                    case 5:
                                                        $badge_class = 'badge-info';
                                                        $texto_estatus = 'Comprometido';
                                                        break;
                                                    case 7:
                                                        $badge_class = 'badge-primary';
                                                        $texto_estatus = 'Causado';
                                                        break;
                                                    case 10:
                                                        $badge_class = 'badge-success';
                                                        $texto_estatus = 'Pagado';
                                                        break;
                                                    case 99:
                                                        $badge_class = 'badge-danger';
                                                        $texto_estatus = 'Anulado';
                                                        break;
                                                }
                                                echo "<span class='badge " . $badge_class . "'>" . $texto_estatus . "</span>";
                                                ?>
                                            </td>
                                            <td class="text-right resalta-monto"><strong><?php echo number_format($compra['monto'], 2, ',', '.'); ?>&nbsp;Bs.</strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if (!empty($ultimas_compras) && count($ultimas_compras) > 5): ?>
                            <div class="text-center py-2">
                                <button type="button" id="btn-mas-compras" class="btn btn-sm btn-outline-primary">Mostrar más</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row section-row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Órdenes de Pago (Últimos 15 días)</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0" id="tabla-pagos">
                            <thead>
                                <tr>
                                    <th>Nro. Orden de Pago</th>
                                    <th>Fecha</th>
                                    <th>RIF</th>
                                    <th>Proveedor</th>
                                    <th>Concepto</th>
                                    <th>Estado</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($ultimas_ordenes_pago)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No hay órdenes de pago recientes.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $idx_row2 = 0;
                                    foreach ($ultimas_ordenes_pago as $pago):
                                        $nro_orden_pago = htmlspecialchars(str_pad($pago['numero'], 6, '0', STR_PAD_LEFT) . '-' . $pago['anno']);
                                        $id_pago_encriptado = encriptar($pago['id']);
                                        $rif_pago = isset($pago['rif']) ? trim($pago['rif']) : '';
                                        $fecha_pago = isset($pago['fecha']) ? date('d/m/Y', strtotime($pago['fecha'])) : '';
                                    ?>
                                        <tr class="fila-pagos" <?php echo ($idx_row2++ >= 5) ? 'style="display:none;"' : ''; ?>>
                                            <td><a href="#" onclick="imprimirOrdenPago('<?php echo $id_pago_encriptado; ?>', '<?php echo htmlspecialchars($pago['tipo_orden']); ?>'); return false;"><?php echo $nro_orden_pago; ?></a></td>
                                            <td><?php echo $fecha_pago !== '' ? htmlspecialchars($fecha_pago) : '—'; ?></td>
                                            <td><?php echo $rif_pago !== '' ? htmlspecialchars($rif_pago) : '—'; ?></td>
                                            <td class="resalta-proveedor"><?php echo isset($pago['razon_social']) && trim($pago['razon_social']) !== '' ? htmlspecialchars($pago['razon_social']) : '—'; ?></td>
                                            <td><?php echo htmlspecialchars($pago['concepto']); ?></td>
                                            <td>
                                                <?php
                                                $estatus_pago = (int)$pago['estatus'];
                                                $badge_class_pago = 'badge-secondary';
                                                $texto_estatus_pago = 'Desconocido';
                                                switch ($estatus_pago) {
                                                    case 0:
                                                        $badge_class_pago = 'badge-secondary';
                                                        $texto_estatus_pago = 'En Proceso';
                                                        break;
                                                    case 5:
                                                        $badge_class_pago = 'badge-info';
                                                        $texto_estatus_pago = 'Comprometido';
                                                        break;
                                                    case 7:
                                                        $badge_class_pago = 'badge-primary';
                                                        $texto_estatus_pago = 'Causado';
                                                        break;
                                                    case 10:
                                                        $badge_class_pago = 'badge-success';
                                                        $texto_estatus_pago = 'Pagado';
                                                        break;
                                                    case 99:
                                                        $badge_class_pago = 'badge-danger';
                                                        $texto_estatus_pago = 'Anulado';
                                                        break;
                                                }
                                                echo "<span class='badge " . $badge_class_pago . "'>" . $texto_estatus_pago . "</span>";
                                                ?>
                                            </td>
                                            <td class="text-right resalta-monto"><strong><?php echo number_format($pago['monto'], 2, ',', '.'); ?>&nbsp;Bs.</strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if (!empty($ultimas_ordenes_pago) && count($ultimas_ordenes_pago) > 5): ?>
                            <div class="text-center py-2">
                                <button type="button" id="btn-mas-pagos" class="btn btn-sm btn-outline-primary">Mostrar más</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Paleta sobria/ejecutiva global (acento + grises)
        window.PALETTE = window.PALETTE || {
            accent: '#2f6fab', // azul sobrio
            accentSoft: '#6b8bbd', // variante suave
            grey: '#adb5bd', // gris medio
            greyLight: '#ced4da', // gris claro
            greyDark: '#6c757d' // gris oscuro
        };
        // Helper global: renderizar barras con esquinas redondeadas solo durante la creación del gráfico
        function withRoundedBars(radius, createChartFn) {
            var originalDraw = Chart.elements.Rectangle.prototype.draw;
            var r = (typeof radius === 'number' && radius >= 0) ? radius : 6;
            var RoundedRectangle = function(ctx, x, y, width, height, rad) {
                var rr = Math.min(rad, Math.abs(width) / 2, Math.abs(height) / 2);
                var left = x;
                var right = x + width;
                var top = y;
                var bottom = y + height;
                ctx.beginPath();
                ctx.moveTo(left + rr, top);
                ctx.lineTo(right - rr, top);
                ctx.quadraticCurveTo(right, top, right, top + rr);
                ctx.lineTo(right, bottom - rr);
                ctx.quadraticCurveTo(right, bottom, right - rr, bottom);
                ctx.lineTo(left + rr, bottom);
                ctx.quadraticCurveTo(left, bottom, left, bottom - rr);
                ctx.lineTo(left, top + rr);
                ctx.quadraticCurveTo(left, top, left + rr, top);
                ctx.closePath();
            };
            Chart.elements.Rectangle.prototype.draw = function() {
                var ctx = this._chart.ctx;
                var vm = this._view;
                var left, right, top, bottom, borderWidth = vm.borderWidth || 0;
                if (!vm.horizontal) {
                    left = vm.x - vm.width / 2;
                    right = vm.x + vm.width / 2;
                    top = vm.y;
                    bottom = vm.base;
                } else {
                    left = Math.min(vm.x, vm.base);
                    right = Math.max(vm.x, vm.base);
                    top = vm.y - vm.height / 2;
                    bottom = vm.y + vm.height / 2;
                }
                if (borderWidth) {
                    var barSize = Math.min(Math.abs(left - right), Math.abs(top - bottom));
                    borderWidth = borderWidth > barSize ? barSize : borderWidth;
                    var halfStroke = borderWidth / 2;
                    left += halfStroke;
                    right -= halfStroke;
                    top += halfStroke;
                    bottom -= halfStroke;
                }
                ctx.save();
                ctx.fillStyle = vm.backgroundColor;
                ctx.strokeStyle = vm.borderColor;
                ctx.lineWidth = borderWidth;
                RoundedRectangle(ctx, left, Math.min(top, bottom), right - left, Math.abs(bottom - top), r);
                ctx.fill();
                if (borderWidth) ctx.stroke();
                ctx.restore();
            };
            try {
                return createChartFn();
            } finally {
                Chart.elements.Rectangle.prototype.draw = originalDraw;
            }
        }

        // Plugin global (Chart.js v2) para dibujar etiquetas sin parpadeo al hacer hover
        if (typeof Chart !== 'undefined' && Chart.pluginService) {
            Chart.pluginService.register({
                afterDatasetsDraw: function(chart, easing) {
                    var opts = (chart.config && chart.config.options) ? chart.config.options : {};
                    var cl = opts.customLabels;
                    if (!cl) return;
                    var ctx = chart.ctx;

                    function toRGB(color) {
                        if (!color) return {
                            r: 0,
                            g: 0,
                            b: 0
                        };
                        if (typeof color === 'string') {
                            var c = color.trim();
                            if (c[0] === '#') {
                                if (c.length === 4) return {
                                    r: parseInt(c[1] + c[1], 16),
                                    g: parseInt(c[2] + c[2], 16),
                                    b: parseInt(c[3] + c[3], 16)
                                };
                                if (c.length >= 7) return {
                                    r: parseInt(c.substr(1, 2), 16),
                                    g: parseInt(c.substr(3, 2), 16),
                                    b: parseInt(c.substr(5, 2), 16)
                                };
                            }
                            var m = c.match(/rgba?\((\d+)\s*,\s*(\d+)\s*,\s*(\d+)/i);
                            if (m) return {
                                r: +m[1],
                                g: +m[2],
                                b: +m[3]
                            };
                        } else if (Array.isArray(color) && color.length >= 3) {
                            return {
                                r: +color[0],
                                g: +color[1],
                                b: +color[2]
                            };
                        }
                        return {
                            r: 0,
                            g: 0,
                            b: 0
                        };
                    }

                    function luminance(rgb) {
                        return (0.299 * rgb.r + 0.587 * rgb.g + 0.114 * rgb.b) / 255;
                    }
                    if (cl.type === 'asistencia-percent') {
                        // Muestra porcentajes dentro de los segmentos apilados (stack1)
                        var data = chart.config.data || {};
                        var dsets = data.datasets || [];
                        var labels = data.labels || [];
                        if (!dsets.length || !labels.length) return;
                        ctx.save();
                        ctx.font = 'bold 11px sans-serif';
                        ctx.fillStyle = '#111';
                        ctx.textAlign = 'center';
                        for (var i = 0; i < labels.length; i++) {
                            // identificar índices de datasets que pertenecen al stack1 (los que queremos mostrar en %)
                            var stackIdx = [];
                            for (var s = 0; s < dsets.length; s++) {
                                if (dsets[s] && dsets[s].stack && dsets[s].stack === 'stack1') stackIdx.push(s);
                            }
                            if (!stackIdx.length) continue;
                            var total = 0;
                            for (var si = 0; si < stackIdx.length; si++) {
                                var idx = stackIdx[si];
                                total += Number((dsets[idx].data && dsets[idx].data[i]) ? dsets[idx].data[i] : 0) || 0;
                            }
                            if (!total) continue;
                            for (var si2 = 0; si2 < stackIdx.length; si2++) {
                                var idx2 = stackIdx[si2];
                                var meta = chart.getDatasetMeta(idx2);
                                if (!meta || !meta.data || !meta.data[i]) continue;
                                var model = meta.data[i]._model || meta.data[i];
                                var valor = Number((dsets[idx2].data && dsets[idx2].data[i]) ? dsets[idx2].data[i] : 0) || 0;
                                var porc = Math.round((valor / total) * 100);
                                if (porc <= 0) continue;
                                // colocar el texto centrado dentro del segmento (promedio entre base y x)
                                var startX = (typeof model.base !== 'undefined') ? model.base : 0;
                                var midX = (model.x + startX) / 2;
                                // contraste de color según fondo
                                var bg = (dsets[idx2].backgroundColor && (Array.isArray(dsets[idx2].backgroundColor) ? dsets[idx2].backgroundColor[i] : dsets[idx2].backgroundColor)) || '#666';
                                var rgb = toRGB(bg);
                                var lum = luminance(rgb);
                                var textColor = (lum < 0.5) ? '#fff' : '#111';
                                ctx.fillStyle = textColor;
                                ctx.fillText(porc + '%', midX, model.y - 6);
                            }
                        }
                        ctx.restore();
                    } else if (cl.type === 'salidas-valores') {
                        var meta0 = chart.getDatasetMeta(0);
                        if (!meta0) return;
                        var ds0 = chart.config.data && chart.config.data.datasets ? chart.config.data.datasets[0] : null;
                        if (!ds0) return;
                        var area = chart.chartArea;
                        ctx.save();
                        ctx.font = '600 12px sans-serif';
                        ctx.textBaseline = 'middle';
                        var margin = 6;
                        for (var k = 0; k < meta0.data.length; k++) {
                            var model2 = meta0.data[k]._model || meta0.data[k];
                            var value2 = Number(ds0.data[k]) || 0;
                            if (!value2) continue;
                            var text = value2.toLocaleString('es-ES');
                            var textW = ctx.measureText(text).width;
                            var barLen = Math.abs(model2.x - model2.base);
                            var bg = (ds0.backgroundColor && (Array.isArray(ds0.backgroundColor) ? ds0.backgroundColor[k] : ds0.backgroundColor)) ||
                                (meta0 && meta0.controller && typeof meta0.controller.getStyle === 'function' ? meta0.controller.getStyle(k).backgroundColor : null) || '#6c757d';
                            var rgb = toRGB(bg);
                            var lum = luminance(rgb);
                            var placeInside = barLen >= textW + 10;
                            var x, y = model2.y;
                            var textColor = '#111';
                            var strokeColor = 'rgba(255,255,255,0)';
                            ctx.textAlign = 'left';
                            if (placeInside) {
                                x = model2.x - 4;
                                ctx.textAlign = 'right';
                                if (lum < 0.5) {
                                    textColor = '#fff';
                                    strokeColor = 'rgba(0,0,0,0.35)';
                                } else {
                                    textColor = '#111';
                                    strokeColor = 'rgba(255,255,255,0.55)';
                                }
                            } else {
                                x = model2.x + margin;
                                ctx.textAlign = 'left';
                                textColor = '#111';
                                strokeColor = 'rgba(255,255,255,0)';
                            }
                            if (x + (ctx.textAlign === 'left' ? textW : 0) > area.right - 2) x = area.right - 2 - (ctx.textAlign === 'left' ? textW : 0);
                            if (x - (ctx.textAlign === 'right' ? textW : 0) < area.left + 2) x = area.left + 2 + (ctx.textAlign === 'right' ? textW : 0);
                            if (strokeColor !== 'rgba(255,255,255,0)') {
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = strokeColor;
                                ctx.strokeText(text, x, y);
                            }
                            ctx.fillStyle = textColor;
                            ctx.fillText(text, x, y);
                        }
                        ctx.restore();
                    } else if (cl.type === 'donut-percent') {
                        var ds1 = chart.config.data && chart.config.data.datasets ? chart.config.data.datasets[0] : null;
                        if (!ds1) return;
                        var total2 = ds1.data.reduce(function(a, b) {
                            return a + (Number(b) || 0);
                        }, 0) || 0;
                        if (!total2) return;
                        var minP = (typeof cl.minPercent === 'number') ? cl.minPercent : 3;
                        ctx.save();
                        ctx.font = 'bold 12px sans-serif';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        var meta1 = chart.getDatasetMeta(0);
                        var bgColors = ds1.backgroundColor || [];
                        for (var h = 0; h < ds1.data.length; h++) {
                            var value3 = Number(ds1.data[h]) || 0;
                            if (!value3) continue;
                            var perc = Math.round((value3 / total2) * 100);
                            if (perc < minP) continue;
                            var m = meta1.data[h]._model || meta1.data[h];
                            var mid = (m.startAngle + m.endAngle) / 2;
                            var r = (m.outerRadius + m.innerRadius) / 2;
                            var x2 = m.x + Math.cos(mid) * r;
                            var y2 = m.y + Math.sin(mid) * r;
                            var bgc = bgColors[h] || (meta1.controller.getStyle(h) && meta1.controller.getStyle(h).backgroundColor) || '#666';
                            var rgbc = toRGB(bgc);
                            var lumc = luminance(rgbc);
                            var fill = lumc < 0.5 ? '#fff' : '#111';
                            var stroke = lumc < 0.5 ? 'rgba(0,0,0,0.35)' : 'rgba(255,255,255,0.6)';
                            ctx.lineWidth = 3;
                            ctx.strokeStyle = stroke;
                            ctx.strokeText(perc + '%', x2, y2);
                            ctx.fillStyle = fill;
                            ctx.fillText(perc + '%', x2, y2);
                        }
                        ctx.restore();
                    }
                }
            });
        }

        $(function() {
            'use strict'

            // Usar la paleta global
            var PALETTE = window.PALETTE;

            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            }

            var mode = 'index'
            var intersect = true

            var $salesChart = $('#sales-chart')
            // Array paralelo de fechas (días hábiles) para mapear índice de barra -> fecha real
            window.__fechasMapAsistencia = <?php
                                            $fechasMap = [];
                                            $fecha_iter = new DateTime($fecha_inicio_semana);
                                            $fecha_fin_it = new DateTime($fecha_fin_semana);
                                            while ($fecha_iter <= $fecha_fin_it) {
                                                $dow = $fecha_iter->format('N');
                                                if ($dow < 6) {
                                                    $fechasMap[] = $fecha_iter->format('Y-m-d');
                                                }
                                                $fecha_iter->modify('+1 day');
                                            }
                                            echo json_encode($fechasMap);
                                            ?>;
            var salesChart = withRoundedBars(8, function() {
                return new Chart($salesChart, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                                backgroundColor: PALETTE.accent,
                                borderColor: PALETTE.accent,
                                data: <?php echo json_encode($datos_a_tiempo); ?>
                            },
                            {
                                backgroundColor: PALETTE.greyLight,
                                borderColor: PALETTE.greyLight,
                                data: <?php echo json_encode($datos_tarde); ?>
                            }
                        ]
                    },
                    options: {
                        customLabels: {
                            type: 'asistencia-percent'
                        },
                        animation: {
                            duration: 300
                        },
                        onClick: function(evt) {
                            // Obtener elementos en posición del click (Chart.js v2)
                            var puntos = this.getElementAtEvent(evt);
                            if (!puntos || !puntos.length) return;
                            var element = puntos[0];
                            if (typeof element._datasetIndex === 'undefined') return;
                            if (element._datasetIndex !== 1) return; // Solo dataset de retardos (tarde)
                            var idx = element._index;
                            var fechasMap = window.__fechasMapAsistencia || [];
                            var fecha = fechasMap[idx];
                            if (fecha) {
                                abrirListadoRetardos(fecha);
                            }
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            mode: mode,
                            intersect: intersect
                        },
                        hover: {
                            animationDuration: 0,
                            mode: mode,
                            intersect: intersect
                        },
                        legend: {
                            display: false
                        },
                        layout: {
                            padding: {
                                top: 12
                            }
                        },
                        scales: {
                            yAxes: [{
                                // display: false,
                                gridLines: {
                                    display: true,
                                    lineWidth: '1px',
                                    color: 'rgba(0, 0, 0, .06)',
                                    zeroLineColor: 'transparent'
                                },
                                ticks: $.extend({
                                    beginAtZero: true,
                                    // Include a dollar sign in the ticks
                                    callback: function(value) {
                                        if (value >= 1000) {
                                            value /= 1000
                                            value += 'k'
                                        }
                                        return value
                                    }
                                }, ticksStyle)
                            }],
                            xAxes: [{
                                display: true,
                                gridLines: {
                                    display: false
                                },
                                ticks: ticksStyle
                            }]
                        }
                    }
                })
            })

            //-------------
            //- DONUT CHART -
            //-------------
            var donutChartCanvas = $('#donut-chart').get(0).getContext('2d')
            var donutData = {
                labels: <?php echo json_encode($labels_eval); ?>,
                datasets: [{
                    data: <?php echo json_encode($valores_eval); ?>,
                    backgroundColor: [PALETTE.accent, PALETTE.greyLight],
                }]
            }
            var donutOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    position: 'top'
                },
                customLabels: {
                    type: 'donut-percent',
                    minPercent: 3
                },
                animation: {
                    duration: 400
                },
                hover: {
                    animationDuration: 0
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var ds = data.datasets[tooltipItem.datasetIndex] || {
                                data: []
                            };
                            var value = Number(ds.data[tooltipItem.index]) || 0;
                            var total = ds.data.reduce(function(a, b) {
                                return a + (Number(b) || 0);
                            }, 0) || 0;
                            var perc = total ? Math.round((value / total) * 100) : 0;
                            return data.labels[tooltipItem.index] + ': ' + value + ' (' + perc + '%)';
                        }
                    }
                }
            }
            new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions
            })

        })

        // Mostrar más/menos en tablas
        $(function() {
            $('#btn-mas-compras').on('click', function() {
                var ocultas = $('#tabla-compras tbody tr.fila-compras[style*="display:none"]');
                if (ocultas.length) {
                    ocultas.show();
                    $(this).text('Mostrar menos');
                } else {
                    var filas = $('#tabla-compras tbody tr.fila-compras');
                    filas.each(function(i) {
                        $(this).toggle(i < 5);
                    });
                    $(this).text('Mostrar más');
                }
            });

            $('#btn-mas-pagos').on('click', function() {
                var ocultas = $('#tabla-pagos tbody tr.fila-pagos[style*="display:none"]');
                if (ocultas.length) {
                    ocultas.show();
                    $(this).text('Mostrar menos');
                } else {
                    var filas = $('#tabla-pagos tbody tr.fila-pagos');
                    filas.each(function(i) {
                        $(this).toggle(i < 5);
                    });
                    $(this).text('Mostrar más');
                }
            });

            $('#btn-mas-movs').on('click', function() {
                var ocultas = $('table tbody tr.fila-movs[style*="display:none"]');
                if (ocultas.length) {
                    ocultas.show();
                    $(this).text('Mostrar menos');
                } else {
                    var filas = $('table tbody tr.fila-movs');
                    filas.each(function(i) {
                        $(this).toggle(i < 5);
                    });
                    $(this).text('Mostrar más');
                }
            });
        });

        // =====================
        // Resumen de Presupuesto (gráficos)
        // =====================
        $(function() {
            var PALETTE = window.PALETTE || {
                accent: '#2f6fab',
                greyLight: '#ced4da'
            };
            var resumen = <?php echo json_encode($resumen_presupuesto); ?>;
            var topPart = <?php echo json_encode($top_partidas); ?>;

            // Dona de ejecución
            var ctxDonutPres = document.getElementById('presu-ejecucion-donut');
            if (ctxDonutPres) {
                // Trabajar en porcentajes relativos al Vigente total (vigente = 100%)
                var vigTotal = Number(resumen.dona.vigente || 0) || 0;
                var pag = Number(resumen.dona.pagado || 0);
                var cau = Number(resumen.dona.causado_no_pagado || 0);
                var com = Number(resumen.dona.comprometido_no_causado || 0);
                var disp = Number(resumen.dona.disponible || 0);
                // Usar Comprometido y Disponible en la dona; la suma de ambos = 100%
                var absCom = Number(resumen.comprometido || 0);
                var absDisp = Number(resumen.disponible || 0);
                var dataDona = [0, 0];
                var labelsDona = ['Comprometido', 'Disponible'];
                var colorsDona = ['#e06b6b', '#28a745'];
                var totalCD = absCom + absDisp;
                if (totalCD > 0) {
                    var comPerc = Math.round(absCom * 100 / totalCD);
                    var dispPerc = 100 - comPerc; // asegurar suma 100
                    dataDona = [comPerc, dispPerc];
                } else {
                    dataDona = [0, 0];
                }
                new Chart(ctxDonutPres.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: labelsDona,
                        datasets: [{
                            data: dataDona,
                            backgroundColor: colorsDona
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        legend: {
                            position: 'top'
                        },
                        customLabels: {
                            type: 'donut-percent',
                            minPercent: 3
                        },
                        animation: {
                            duration: 400
                        },
                        hover: {
                            animationDuration: 0
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var idx = tooltipItem.index;
                                    var label = data.labels[idx] || '';
                                    var perc = Number(data.datasets[0].data[idx]) || 0;
                                    // Valores absolutos originales
                                    var absVals = [absCom, absDisp];
                                    var abs = absVals[idx] || 0;
                                    return label + ': ' + perc + '% (' + abs.toLocaleString('es-ES') + ' Bs.)';
                                }
                            }
                        }
                    }
                });
            }

            // Top partidas (barras horizontales) con comparación Vigente, Comprometido y Pagado
            var ctxTopPart = document.getElementById('presu-top-partidas');
            if (ctxTopPart && topPart && topPart.length) {
                var labels = topPart.map(function(x) {
                    var cod = (x.codigo || '').toString();
                    var des = (x.descripcion || '');
                    var full = (cod + ' — ' + des).trim();
                    return full.length > 42 ? (full.substr(0, 41) + '…') : full;
                });
                // Datos en valores absolutos
                var dataVig = topPart.map(function(x) {
                    return Number(x.vigente) || 0;
                });
                var dataCom = topPart.map(function(x) {
                    return Number(x.comprometido) || 0;
                });
                var dataDisp = topPart.map(function(x) {
                    var v = Number(x.vigente) || 0;
                    var c = Number(x.comprometido) || 0;
                    return Math.max(0, v - c);
                });

                // Convertir a porcentajes relativos a Vigente (vigente = 100%) para mostrar en barras apiladas
                var pctCom = [];
                var pctDisp = [];
                for (var i = 0; i < dataVig.length; i++) {
                    var v = dataVig[i] || 0;
                    if (!v) {
                        pctCom.push(0);
                        pctDisp.push(0);
                    } else {
                        pctCom.push(Math.round((dataCom[i] || 0) * 100 / v));
                        // asegurar que suma a 100 (ajustar por redondeo en disponible)
                        var dPerc = Math.round((dataDisp[i] || 0) * 100 / v);
                        pctDisp.push(dPerc);
                    }
                }

                var colorVig = PALETTE.accent; // azul oscuro para Vigente
                var colorCom = '#e06b6b'; // danger suave (rojo claro) para comprometido
                var colorDisp = '#28a745'; // verde (badge-success) para disponible

                withRoundedBars(8, function() {
                    return new Chart(ctxTopPart.getContext('2d'), {
                        type: 'horizontalBar',
                        data: {
                            labels: labels,
                            datasets: [{
                                    label: 'Vigente (Total año)',
                                    // Mantener valores absolutos en dataset Vigente para tooltips pero ocultaremos el eje con ticks
                                    data: dataVig,
                                    backgroundColor: colorVig,
                                    borderColor: colorVig,
                                    stack: 'stack0'
                                },
                                {
                                    label: 'Comprometido',
                                    // dataset para la barra apilada: usamos porcentaje
                                    data: pctCom,
                                    backgroundColor: colorCom,
                                    borderColor: colorCom,
                                    stack: 'stack1'
                                },
                                {
                                    label: 'Disponible',
                                    data: pctDisp,
                                    backgroundColor: colorDisp,
                                    borderColor: colorDisp,
                                    stack: 'stack1'
                                }
                            ]
                        },
                        options: {
                            // Etiquetas dinámicas no necesarias aquí; dejamos los valores en tooltip
                            animation: {
                                duration: 400
                            },
                            hover: {
                                animationDuration: 0
                            },
                            maintainAspectRatio: false,
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            scales: {
                                xAxes: [{
                                    // Eje de porcentaje: mostrar 0..100
                                    stacked: true,
                                    ticks: {
                                        beginAtZero: true,
                                        min: 0,
                                        max: 100,
                                        callback: function(value) {
                                            return value + '%';
                                        }
                                    },
                                    gridLines: {
                                        display: true,
                                        drawBorder: false
                                    }
                                }],
                                yAxes: [{
                                    // El eje categórico mantiene las categorías separadas
                                    stacked: false,
                                    ticks: {
                                        display: true,
                                        autoSkip: false,
                                        padding: 4
                                    },
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    }
                                }]
                            },
                            tooltips: {
                                mode: 'nearest',
                                intersect: false,
                                callbacks: {
                                    label: function(tooltipItem, data) {
                                        var ds = data.datasets[tooltipItem.datasetIndex];
                                        var label = ds.label || '';
                                        var idx = tooltipItem.index;
                                        // Si es Vigente mostramos valor absoluto y 100%
                                        if (ds.stack === 'stack0') {
                                            var valAbs = Number(ds.data[idx]) || 0;
                                            return label + ': ' + valAbs.toLocaleString('es-ES') + ' Bs. (100%)';
                                        }
                                        // Para comprometido/disponible mostramos porcentaje y valor absoluto calculado
                                        var pct = Number(ds.data[idx]) || 0;
                                        var vigAbs = Number(data.datasets[0].data[idx]) || 0;
                                        var absVal = Math.round(pct * vigAbs / 100);
                                        return label + ': ' + pct + '% (' + absVal.toLocaleString('es-ES') + ' Bs.)';
                                    }
                                }
                            }
                        }
                    });
                });
            }
        });

        function imprimirCompra(id, tipo) {
            var url = 'compras/formatos/10_orden.php?p=1&id=' + id + '&tipo=' + tipo;
            window.open(url, '_blank');
        }

        function imprimirOrdenPago(id, tipo) {
            var url = '';
            if (tipo === 'FINANCIERA') {
                url = 'administracion/formatos/1b_orden_pago.php?id=' + id;
            } else if (tipo === 'ORDEN' || tipo === 'MANUAL') {
                url = 'administracion/formatos/1a_orden_pago.php?id=' + id;
            } else if (tipo === 'NOMINA') {
                url = 'administracion/formatos/1c_orden_pago.php?id=' + id;
            } else if (tipo === 'PATRIA') {
                url = 'administracion/formatos/1_orden_pago.php?id=' + id;
            } else {
                // fallback genérico
                url = 'administracion/formatos/1_orden_pago.php?id=' + id;
            }
            window.open(url, '_blank');
        }

        // Click en listos/faltantes => abrir listado
        $(document).on('click', '.listado-func', function() {
            var tipo = $(this).data('tipo');
            var titulo = (tipo === 'listos' ? 'Funcionarios Listos' : 'Funcionarios Faltantes');

            // 1. Modal Bootstrap (preferido)
            if ($('#modal_lg').length && $('#modal_largo').length) {
                $('#modal_lg').html('<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x text-info"></i><br><small>Cargando listado...</small></div>');
                $('#modal_largo').modal('show');
                $.ajax({
                    url: 'dashboard_listado_funcionarios.php',
                    data: {
                        tipo: tipo
                    },
                    success: function(html) {
                        if ($.trim(html) === '') {
                            $('#modal_lg').html('<div class="alert alert-warning mb-0">Sin contenido recibido.</div>');
                        } else {
                            // El contenido ya trae su propio encabezado <h5>, no duplicar
                            $('#modal_lg').html(html);
                        }
                    },
                    error: function() {
                        $('#modal_lg').html('<div class="alert alert-danger mb-0">Error cargando datos.</div>');
                    }
                });
                return;
            }

            // 2. SweetAlert2 si no existe modal bootstrap
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: titulo,
                    width: '850px',
                    allowOutsideClick: true,
                    showConfirmButton: false,
                    didOpen: () => {
                        let cont = Swal.getHtmlContainer();
                        cont.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x text-info"></i><br><small>Cargando...</small></div>';
                        $.ajax({
                            url: 'dashboard_listado_funcionarios.php',
                            data: {
                                tipo: tipo
                            },
                            success: function(html) {
                                cont.innerHTML = (html && $.trim(html) !== '') ? html : '<div class="alert alert-warning mb-0">Sin contenido recibido.</div>';
                            },
                            error: function() {
                                cont.innerHTML = '<div class="alert alert-danger mb-0">Error cargando datos.</div>';
                            }
                        });
                    }
                });
                return;
            }

            // 3. Último recurso: nueva pestaña
            window.open('dashboard_listado_funcionarios.php?tipo=' + tipo, '_blank');
        });


        // Click en llegadas tarde
        $(document).on('click', '.listado-retardos', function() {
            var fecha = $(this).data('fecha');
            abrirListadoRetardos(fecha);
        });

        // Función reutilizable para abrir listado de retardos
        function abrirListadoRetardos(fecha) {
            var titulo = 'Llegadas Tarde';
            if (!fecha) {
                fecha = '<?php echo $fecha_hoy; ?>';
            }
            if ($('#modal_lg').length && $('#modal_largo').length) {
                $('#modal_lg').html('<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x text-info"></i><br><small>Cargando retardos...</small></div>');
                $('#modal_largo').modal('show');
                $.ajax({
                    url: 'dashboard_listado_retardos.php',
                    data: {
                        fecha: fecha
                    },
                    success: function(html) {
                        $('#modal_lg').html(html && $.trim(html) !== '' ? html : '<div class="alert alert-warning mb-0">Sin contenido recibido.</div>');
                    },
                    error: function() {
                        $('#modal_lg').html('<div class="alert alert-danger mb-0">Error cargando datos.</div>');
                    }
                });
                return;
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: titulo,
                    width: '900px',
                    showConfirmButton: false,
                    didOpen: () => {
                        const cont = Swal.getHtmlContainer();
                        cont.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x text-info"></i><br><small>Cargando...</small></div>';
                        $.ajax({
                            url: 'dashboard_listado_retardos.php',
                            data: {
                                fecha: fecha
                            },
                            success: function(html) {
                                cont.innerHTML = (html && $.trim(html) !== '') ? html : '<div class="alert alert-warning mb-0">Sin contenido recibido.</div>';
                            },
                            error: function() {
                                cont.innerHTML = '<div class="alert alert-danger mb-0">Error cargando datos.</div>';
                            }
                        });
                    }
                });
                return;
            }
            window.open('dashboard_listado_retardos.php?fecha=' + fecha, '_blank');
        }

        // ====================
        // Charts de Almacén
        // ====================
        $(function() {
            // Datos PHP -> JS
            var PALETTE = window.PALETTE || {
                accent: '#2f6fab',
                greyLight: '#ced4da'
            };
            var salidasAreasLabels = <?php echo json_encode(array_map(function ($x) {
                                            return $x['area'];
                                        }, $salidas_por_area)); ?>;
            var salidasAreasData = <?php echo json_encode(array_map(function ($x) {
                                        return (float)$x['total'];
                                    }, $salidas_por_area)); ?>;

            var topArticulosLabels = <?php echo json_encode(array_map(function ($x) {
                                            return $x['articulo'];
                                        }, $top_articulos_salida)); ?>;
            var topArticulosData = <?php echo json_encode(array_map(function ($x) {
                                        return (float)$x['total'];
                                    }, $top_articulos_salida)); ?>;

            // Colores helper
            function generarColores(n, palette) {
                var base = palette || ['#007bff', '#17a2b8', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#20c997', '#fd7e14', '#6c757d', '#6610f2'];
                var out = [];
                for (var i = 0; i < n; i++) {
                    out.push(base[i % base.length]);
                }
                return out;
            }

            // Bar: Salidas por Área
            var ctxArea = document.getElementById('almacen-salidas-area');
            if (ctxArea && salidasAreasLabels && salidasAreasLabels.length) {
                // Monocromático sobrio para todas las barras
                var coloresAreas = salidasAreasData.map(function() {
                    return PALETTE.accent;
                });

                // Limpiar prefijo "DIRECCION DE ..." y acortar etiquetas para eje X
                function quitarPrefijoDireccion(s) {
                    if (!s) return '';
                    return s.replace(/^\s*direcci[oó]n\s+de(l| la| los| las)?\s+/i, '');
                }

                function acortar(s, max) {
                    max = max || 18;
                    if (!s) return '';
                    return s.length > max ? (s.substr(0, max - 1) + '…') : s;
                }
                var salidasAreasLabelsFull = salidasAreasLabels.slice();
                var salidasAreasLabelsNice = salidasAreasLabelsFull.map(function(lbl) {
                    return acortar(quitarPrefijoDireccion(lbl), 28);
                });

                withRoundedBars(8, function() {
                    return new Chart(ctxArea.getContext('2d'), {
                        type: 'horizontalBar',
                        data: {
                            labels: salidasAreasLabelsNice,
                            datasets: [{
                                label: 'Solicitudes',
                                backgroundColor: coloresAreas,
                                borderColor: coloresAreas,
                                data: salidasAreasData
                            }]
                        },
                        options: {
                            customLabels: {
                                type: 'salidas-valores'
                            },
                            animation: {
                                duration: 400
                            },
                            hover: {
                                animationDuration: 0
                            },
                            maintainAspectRatio: false,
                            legend: {
                                display: false
                            },
                            layout: {
                                padding: {
                                    top: 8
                                }
                            },
                            scales: {
                                xAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    },
                                    gridLines: {
                                        display: true,
                                        drawBorder: false
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        display: true,
                                        autoSkip: false,
                                        padding: 4
                                    },
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    }
                                }]
                            },
                            tooltips: {
                                intersect: true,
                                mode: 'index',
                                callbacks: {
                                    title: function(tooltipItems, data) {
                                        var idx = tooltipItems && tooltipItems.length ? tooltipItems[0].index : 0;
                                        return salidasAreasLabelsFull[idx] || '';
                                    },
                                    label: function(tooltipItem, data) {
                                        var val = data.datasets[0].data[tooltipItem.index] || 0;
                                        return 'Solicitudes: ' + val;
                                    }
                                }
                            }
                        }
                    });
                });
            }

            // Donut: Top artículos
            var ctxDonut = document.getElementById('almacen-top-articulos');
            if (ctxDonut && topArticulosLabels && topArticulosLabels.length) {
                // Calcular totales para porcentajes
                var totalTop = (topArticulosData || []).reduce(function(a, b) {
                    return a + (Number(b) || 0);
                }, 0) || 0;

                function pct(v) {
                    return totalTop ? Math.round((Number(v) / totalTop) * 100) : 0;
                }
                // Paleta sobria: acento para el mayor, grises para el resto
                var idxMax = 0,
                    maxVal = -Infinity;
                for (var i = 0; i < topArticulosData.length; i++) {
                    var val = Number(topArticulosData[i]) || 0;
                    if (val > maxVal) {
                        maxVal = val;
                        idxMax = i;
                    }
                }
                var greyShades = ['#e9ecef', '#dee2e6', '#ced4da', '#adb5bd', '#cfd4da', '#d6d8db'];
                var topColors = topArticulosData.map(function(v, i) {
                    return (i === idxMax) ? PALETTE.accent : greyShades[i % greyShades.length];
                });
                new Chart(ctxDonut.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: topArticulosLabels,
                        datasets: [{
                            data: topArticulosData,
                            backgroundColor: topColors
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12,
                                padding: 14,
                                generateLabels: function(chart) {
                                    var data = chart.data;
                                    if (!data.labels || !data.labels.length) return [];
                                    var ds = data.datasets[0] || {
                                        data: []
                                    };
                                    var meta = chart.getDatasetMeta(0);
                                    return data.labels.map(function(label, i) {
                                        var value = Number(ds.data[i]) || 0;
                                        var style = meta.controller.getStyle(i);
                                        return {
                                            text: label + ' (' + value + ')',
                                            fillStyle: style.backgroundColor,
                                            strokeStyle: style.borderColor,
                                            lineWidth: style.borderWidth,
                                            hidden: isNaN(value) || meta.data[i].hidden,
                                            index: i
                                        };
                                    });
                                }
                            }
                        },
                        customLabels: {
                            type: 'donut-percent',
                            minPercent: 3
                        },
                        animation: {
                            duration: 400
                        },
                        hover: {
                            animationDuration: 0
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var ds = data.datasets[tooltipItem.datasetIndex] || {
                                        data: []
                                    };
                                    var value = Number(ds.data[tooltipItem.index]) || 0;
                                    var total = ds.data.reduce(function(a, b) {
                                        return a + (Number(b) || 0);
                                    }, 0) || 0;
                                    var perc = total ? Math.round((value / total) * 100) : 0;
                                    return data.labels[tooltipItem.index] + ': ' + value + ' (' + perc + '%)';
                                }
                            }
                        }
                    }
                });
            }

            // Click en fila de movimiento => abrir modal detalle
            $(document).on('click', '.almacen-mov', function() {
                var id = $(this).data('id');
                var tipo = $(this).data('tipo');
                var titulo = (tipo === 'INGRESO' ? 'Detalle de Ingreso' : 'Detalle de Salida');
                // 1) Usar modal bootstrap si existe
                if ($('#modal_lg').length && $('#modal_largo').length) {
                    $('#modal_lg').html('<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x text-info"></i><br><small>Cargando detalle...</small></div>');
                    $('#modal_largo').modal('show');
                    $.ajax({
                        url: 'dashboard_almacen_movimiento_detalle.php',
                        data: {
                            id: id,
                            tipo: tipo
                        },
                        success: function(html) {
                            $('#modal_lg').html(html && $.trim(html) !== '' ? html : '<div class="alert alert-warning mb-0">Sin contenido recibido.</div>');
                        },
                        error: function() {
                            $('#modal_lg').html('<div class="alert alert-danger mb-0">Error cargando datos.</div>');
                        }
                    });
                    return;
                }
                // 2) SweetAlert2 como fallback
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: titulo,
                        width: '900px',
                        showConfirmButton: false,
                        didOpen: () => {
                            const cont = Swal.getHtmlContainer();
                            cont.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x text-info"></i><br><small>Cargando...</small></div>';
                            $.ajax({
                                url: 'dashboard_almacen_movimiento_detalle.php',
                                data: {
                                    id: id,
                                    tipo: tipo
                                },
                                success: function(html) {
                                    cont.innerHTML = (html && $.trim(html) !== '') ? html : '<div class="alert alert-warning mb-0">Sin contenido recibido.</div>';
                                },
                                error: function() {
                                    cont.innerHTML = '<div class="alert alert-danger mb-0">Error cargando datos.</div>';
                                }
                            });
                        }
                    });
                    return;
                }
                // 3) Último recurso: nueva pestaña
                window.open('dashboard_almacen_movimiento_detalle.php?id=' + id + '&tipo=' + tipo, '_blank');
            });
        });

        // Botón: actualizar partidas (Presupuesto)
        $(function() {
            $('#btn-act-presu').on('click', function() {
                var $btn = $(this);
                if ($btn.data('loading')) return;
                var orig = $btn.html();
                $btn.data('loading', true)
                    .prop('disabled', true)
                    .removeClass('text-muted')
                    .addClass('text-info')
                    .html('<i class="fas fa-sync fa-spin"></i> Actualizando…');
                $.ajax({
                    url: 'dashboard_presupuesto_actualizar.php',
                    method: 'POST',
                    dataType: 'json'
                }).done(function(resp) {
                    if (resp && resp.ok) {
                        // Refrescar para ver cifras actualizadas
                        location.reload();
                    } else {
                        var msg = (resp && resp.error) ? resp.error : 'No se pudo actualizar';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: msg
                            });
                        } else {
                            alert('Error: ' + msg);
                        }
                    }
                }).fail(function(xhr) {
                    var msg = xhr && xhr.responseText ? xhr.responseText : 'Error de red';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    } else {
                        alert('Error: ' + msg);
                    }
                }).always(function() {
                    $btn.data('loading', false)
                        .prop('disabled', false)
                        .removeClass('text-info')
                        .addClass('text-muted')
                        .html(orig);
                });
            });
        });
    </script>
    <style>
        /* Forzar margen superior si otras hojas lo eliminan */
        #dashboard-root {
            margin-top: 18px !important;
        }

        /* Resaltar proveedor y monto en tablas */
        #dashboard-root .resalta-proveedor {
            font-weight: 700;
            color: #000;
        }

        #dashboard-root .resalta-monto {
            font-weight: 700;
            color: #000;
        }

        /* Evitar que el símbolo de moneda salte de línea */
        #dashboard-root td.resalta-monto strong {
            white-space: nowrap;
        }

        /* Separación vertical uniforme entre secciones */
        .section-row {
            /* margen único de sección a sección (evita dobles espacios) */
            margin-top: 12px !important;
            margin-bottom: 0 !important;
        }

        /* Anular margen inferior por defecto de las tarjetas dentro de secciones */
        .section-row .card {
            margin-bottom: 0 !important;
        }

        /* Espacio adicional al final para no pegar con el pie de página */
        .section-row:last-of-type,
        .section-row:last-child {
            margin-bottom: 20px !important;
        }

        /* Ajustar cards compactas si deseas menos espacio interno (descomenta)
        #dashboard-root .card-body { padding: 0.75rem 0.75rem; }
        */

        /* Resaltado de títulos de tarjetas (incluye Almacén) */
        #dashboard-root .card-header .card-title,
        .titulo-almacen {
            position: relative;
            display: inline-block;
            font-weight: 700;
            padding: 4px 10px 6px 10px;
            margin: 0;
            font-size: 1.05rem;
            letter-spacing: .5px;
            background: linear-gradient(90deg, #ffe08a 0%, #ffc107 60%, #ffdd57 100%);
            color: #343a40;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
        }

        /* Modo oscuro (si se usa AdminLTE dark) */
        .dark-mode #dashboard-root .card-header .card-title,
        .dark-mode .titulo-almacen {
            background: linear-gradient(90deg, #17a2b8 0%, #0d6efd 100%);
            color: #fff;
        }

        /* Línea decorativa inferior */
        #dashboard-root .card-header .card-title:after,
        .titulo-almacen:after {
            content: '';
            position: absolute;
            left: 8px;
            bottom: 2px;
            height: 2px;
            width: calc(100% - 16px);
            background: rgba(0, 0, 0, 0.25);
            border-radius: 2px;
        }

        /* Ancho 60/40 en desktop: asistencia más ancha (+20%) */
        @media (min-width: 992px) {
            #dashboard-root .col-asistencia {
                -ms-flex: 0 0 60%;
                flex: 0 0 60%;
                max-width: 60%;
            }

            #dashboard-root .col-evaluacion {
                -ms-flex: 0 0 40%;
                flex: 0 0 40%;
                max-width: 40%;
            }

            /* Almacén: 60%/40% */
            .col-alm-salidas {
                -ms-flex: 0 0 60%;
                flex: 0 0 60%;
                max-width: 60%;
            }

            .col-alm-donut {
                -ms-flex: 0 0 40%;
                flex: 0 0 40%;
                max-width: 40%;
            }
        }

        /* (Hereda el estilo completo arriba mediante .titulo-almacen) */

        /* Ocultar contenedor de badges (los nombres van en el eje X ahora) */
        #almacen-salidas-badges {
            display: none;
        }
    </style>
</div>

<!-- Resumen de Presupuesto (Año actual) -->
<div class="row section-row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-transparent d-flex justify-content-between align-items-center">
                <h3 class="card-title titulo-almacen mb-0">Resumen de Presupuesto <?php echo htmlspecialchars($anio_actual); ?></h3>
                <button id="btn-act-presu" class="btn btn-sm btn-light text-muted border-0" title="Actualizar partidas (en caliente)" style="padding:2px 8px; font-size:.8rem;">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>
            <div class="card-body">
                <!-- KPIs -->
                <div class="row text-center mb-3 justify-content-center">
                    <div class="col-sm-6 col-md-4 col-lg-2 mb-3">
                        <div class="alert alert-secondary py-2 mb-0">
                            <div style="font-weight:600;">Vigente</div>
                            <div style="font-size:1.2rem; font-weight:700;"><?php echo number_format($resumen_presupuesto['vigente'], 2, ',', '.'); ?>&nbsp;Bs.</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-2 mb-3">
                        <div class="alert alert-info py-2 mb-0">
                            <div style="font-weight:600;">Comprometido</div>
                            <div style="font-size:1.2rem; font-weight:700;"><?php echo number_format($resumen_presupuesto['comprometido'], 2, ',', '.'); ?>&nbsp;Bs.</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-2 mb-3">
                        <div class="alert alert-primary py-2 mb-0">
                            <div style="font-weight:600;">Causado</div>
                            <div style="font-size:1.2rem; font-weight:700;"><?php echo number_format($resumen_presupuesto['causado'], 2, ',', '.'); ?>&nbsp;Bs.</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-2 mb-3">
                        <div class="alert alert-warning py-2 mb-0">
                            <div style="font-weight:600;">Pagado</div>
                            <div style="font-size:1.2rem; font-weight:700;"><?php echo number_format($resumen_presupuesto['pagado'], 2, ',', '.'); ?>&nbsp;Bs.</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-2 mb-3">
                        <div class="alert alert-success py-2 mb-0">
                            <div style="font-weight:600;">Disponible</div>
                            <div style="font-size:1.2rem; font-weight:700;"><?php echo number_format($resumen_presupuesto['disponible'], 2, ',', '.'); ?>&nbsp;Bs.</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="position-relative" style="height:260px;">
                            <canvas id="presu-top-partidas" height="260"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative" style="height:260px;">
                            <canvas id="presu-ejecucion-donut" height="260"></canvas>
                        </div>
                    </div>
                </div>

                <?php if (!empty($alertas_pres)): ?>
                    <div class="mt-3">
                        <div class="alert alert-warning mb-2 py-2" style="font-weight:600;">Partidas con baja disponibilidad (≤ 10%)</div>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th class="text-right">Vigente</th>
                                        <th class="text-right">Disponible</th>
                                        <th class="text-right">% Disp.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alertas_pres as $a): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($a['codigo']); ?></td>
                                            <td><?php echo htmlspecialchars($a['descripcion']); ?></td>
                                            <td class="text-right"><strong><?php echo number_format($a['vigente'], 2, ',', '.'); ?>&nbsp;Bs.</strong></td>
                                            <td class="text-right"><strong><?php echo number_format($a['disponible'], 2, ',', '.'); ?>&nbsp;Bs.</strong></td>
                                            <td class="text-right">
                                                <?php $p = round($a['porc']); ?>
                                                <span class="badge <?php echo ($p <= 5 ? 'badge-danger' : 'badge-warning'); ?>"><?php echo $p; ?>%</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($resumen_madres)): ?>
                    <div class="mt-3">
                        <div class="alert alert-secondary py-2 mb-2" style="font-weight:600;">Desglose por Actividad:</div>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:120px;">Código</th>
                                        <th class="text-right">Vigente</th>
                                        <th class="text-right">Comprometido</th>
                                        <th class="text-right">Causado</th>
                                        <th class="text-right">Pagado</th>
                                        <th class="text-right">Disponible</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Nueva lógica: listar cada partida madre y debajo sus prefijos agregados (partidas hijas)
                                    // Nota: Los totales del pie sólo consideran las partidas madre para evitar doble conteo.
                                    $t_v = $t_c = $t_ca = $t_p = $t_d = 0.0;
                                    foreach ($resumen_madres as $row) {
                                        // Acumular totales globales (solo madres)
                                        $t_v += $row['vigente'];
                                        $t_c += $row['compromiso'];
                                        $t_ca += $row['causado'];
                                        $t_p += $row['pagado'];
                                        $t_d += $row['disponible'];

                                        // Fila de la partida madre
                                        echo '<tr style="background:#f2f4f7;">';
                                        echo '<td><strong>' . htmlspecialchars($row['codigo']) . '</strong></td>';
                                        echo '<td class="text-right">' . number_format($row['vigente'], 2, ',', '.') . '&nbsp;Bs.</td>';
                                        echo '<td class="text-right">' . number_format($row['compromiso'], 2, ',', '.') . '&nbsp;Bs.</td>';
                                        echo '<td class="text-right">' . number_format($row['causado'], 2, ',', '.') . '&nbsp;Bs.</td>';
                                        echo '<td class="text-right">' . number_format($row['pagado'], 2, ',', '.') . '&nbsp;Bs.</td>';
                                        echo '<td class="text-right">' . number_format($row['disponible'], 2, ',', '.') . '&nbsp;Bs.</td>';
                                        echo '</tr>';

                                        // Prefijos agregados de sus partidas hijas
                                        $prefijos = obtenerPrefijosPorCategoria($_SESSION['conexionsql'], $anio_actual, $row['codigo']);
                                        if (!empty($prefijos)) {
                                            foreach ($prefijos as $p) {
                                                echo '<tr style="background:#ffffff;font-size:95%">';
                                                echo '<td style="padding-left:22px;">&rsaquo; Partida ' . htmlspecialchars($p['prefijo']) . '</td>';
                                                echo '<td class="text-right">' . number_format($p['vigente'], 2, ',', '.') . '&nbsp;Bs.</td>';
                                                echo '<td class="text-right">' . number_format($p['compromiso'], 2, ',', '.') . '&nbsp;Bs.</td>';
                                                echo '<td class="text-right">' . number_format($p['causado'], 2, ',', '.') . '&nbsp;Bs.</td>';
                                                echo '<td class="text-right">' . number_format($p['pagado'], 2, ',', '.') . '&nbsp;Bs.</td>';
                                                echo '<td class="text-right">' . number_format($p['disponible'], 2, ',', '.') . '&nbsp;Bs.</td>';
                                                echo '</tr>';
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th class="text-right"><?php echo number_format($t_v, 2, ',', '.'); ?>&nbsp;Bs.</th>
                                        <th class="text-right"><?php echo number_format($t_c, 2, ',', '.'); ?>&nbsp;Bs.</th>
                                        <th class="text-right"><?php echo number_format($t_ca, 2, ',', '.'); ?>&nbsp;Bs.</th>
                                        <th class="text-right"><?php echo number_format($t_p, 2, ',', '.'); ?>&nbsp;Bs.</th>
                                        <th class="text-right"><?php echo number_format($t_d, 2, ',', '.'); ?>&nbsp;Bs.</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Resumen de Almacén: Gráficos (movido al final) -->
<div class="row section-row">
    <div class="col-lg-8 col-alm-salidas">
        <div class="card h-100">
            <div class="card-header border-0">
                <h3 class="card-title titulo-almacen">Salida por Direcciones (Últimos 15 días)</h3>
            </div>
            <div class="card-body">
                <div id="almacen-salidas-badges" class="mb-2"></div>
                <div class="position-relative" style="height:260px;">
                    <canvas id="almacen-salidas-area" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-alm-donut">
        <div class="card h-100">
            <div class="card-header border-0">
                <h3 class="card-title titulo-almacen">Artículos con más movimiento (Últimos 15 días)</h3>
            </div>
            <div class="card-body">
                <div class="position-relative d-flex justify-content-center align-items-center" style="height:260px;">
                    <canvas id="almacen-top-articulos" style="width: 100% !important; height: 100% !important;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resumen de Almacén: Últimos movimientos (resumen por documento) (movido al final) -->
<div class="row section-row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title titulo-almacen">Últimos movimientos de almacén (Últimos 15 días)</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Documento</th>
                                <th>Área</th>
                                <th class="text-center">Artículo</th>
                                <th class="text-center">Cant. Artículos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ultimos_movs_almacen)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Sin movimientos recientes.</td>
                                </tr>
                            <?php else: ?>
                                <?php $idx_mov = 0;
                                foreach ($ultimos_movs_almacen as $mov): ?>
                                    <tr class="almacen-mov cursor-pointer fila-movs" data-tipo="<?php echo $mov['tipo']; ?>" data-id="<?php echo (int)$mov['id']; ?>" <?php echo ($idx_mov++ >= 5) ? 'style="display:none;"' : ''; ?>>
                                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($mov['fecha']))); ?></td>
                                        <td>
                                            <?php if ($mov['tipo'] === 'INGRESO'): ?>
                                                <span class="badge badge-success">Ingreso</span>
                                            <?php else: ?>
                                                <span class="badge badge-primary">Salida</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo ($mov['tipo'] === 'INGRESO' ? 'Ing. ' : 'Sal. ') . htmlspecialchars(str_pad($mov['numero'], 4, '0', STR_PAD_LEFT)); ?></td>
                                        <td><?php echo isset($mov['area']) && trim((string)$mov['area']) !== '' ? htmlspecialchars($mov['area']) : '—'; ?></td>
                                        <td class="text-center"><?php echo number_format((int)$mov['renglones'], 0, ',', '.'); ?></td>
                                        <td class="text-center"><?php echo number_format((float)$mov['total_cantidad'], 2, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <?php if (!empty($ultimos_movs_almacen) && count($ultimos_movs_almacen) > 5): ?>
                        <div class="text-center py-2">
                            <button type="button" id="btn-mas-movs" class="btn btn-sm btn-outline-primary">Mostrar más</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>