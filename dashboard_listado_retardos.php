<?php
session_start();
if (!isset($_SESSION['VERIFICADO']) || $_SESSION['VERIFICADO'] != 'SI') {
    echo 'Sesión no válida';
    exit;
}
include_once "conexion.php";
header('Content-Type: text/html; charset=UTF-8');

$conexion = $_SESSION['conexionsql'];
// La fecha llegará siempre desde el dashboard (click en barra) o por defecto hoy
$fecha = isset($_GET['fecha']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

// Función reutilizada para capitalizar cargos estilo título
if (!function_exists('formatearCargo')) {
    function formatearCargo($cargoRaw)
    {
        if ($cargoRaw === null) return '';
        $cargo = trim($cargoRaw);
        if ($cargo === '') return '';
        $sufijo = '';
        if (preg_match('/\s*(\([^)]*\))\s*$/u', $cargo, $m)) {
            $sufijo = $m[1];
            $cargo = trim(substr($cargo, 0, -strlen($m[0])));
        }
        $minWords = ['de', 'del', 'la', 'las', 'los', 'y', 'e', 'en', 'para', 'por', 'a', 'o', 'u', 'con', 'sin'];
        $acronimos = ['TIC', 'TI', 'RRHH', 'ODS'];
        $palabras = preg_split('/\s+/u', mb_strtolower($cargo, 'UTF-8'));
        $res = [];
        foreach ($palabras as $i => $p) {
            if ($p === '') continue;
            $pm = mb_strtoupper($p, 'UTF-8');
            if (in_array($pm, $acronimos, true)) {
                $res[] = $pm;
                continue;
            }
            if ($i > 0 && in_array($p, $minWords, true)) {
                $res[] = $p;
            } else {
                $res[] = mb_strtoupper(mb_substr($p, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($p, 1, null, 'UTF-8');
            }
        }
        $cargoForm = implode(' ', $res);
        if ($sufijo) {
            $cargoForm .= ' ' . $sufijo;
        }
        return $cargoForm;
    }
}

// Seleccionar retardos (estatus=1, tipo ENTRADA)
$consulta = "SELECT a.cedula, a.horario, a.hora, a.cargo, 
    TRIM(rac.nombre) AS n1, TRIM(rac.nombre2) AS n2, TRIM(rac.apellido) AS a1, TRIM(rac.apellido2) AS a2
    FROM asistencia_diaria a
    LEFT JOIN rac ON rac.cedula = a.cedula
    WHERE a.fecha = '$fecha' AND a.tipo='ENTRADA' AND a.estatus=1
    ORDER BY a.hora ASC"; // echo $consulta;
$resultado = $conexion->query($consulta);
$total = $resultado ? $resultado->num_rows : 0;
?>
<?php $fecha_mostrar = date('d-m-Y', strtotime($fecha)); ?>
<h5 class="mb-2 d-flex justify-content-between align-items-center">
    <span> Llegadas Tarde <small class="text-muted">(<?php echo htmlspecialchars($fecha_mostrar); ?>)</small> <span class="badge badge-danger"><?php echo $total; ?></span></span>
    <?php if ($total > 0): ?>
        <span style="flex:0 0 220px;" class="text-right">
            <input type="text" id="filtroRetardos" placeholder="Filtrar..." class="form-control form-control-sm">
        </span>
    <?php endif; ?>
</h5>
<?php if ($total === 0): ?>
    <div class="alert alert-info mb-0">No hay llegadas tarde registradas para la fecha.</div>
<?php else: ?>
    <div class="table-responsive" style="max-height:60vh;">
        <table class="table table-sm table-striped table-hover mb-0" id="tablaRetardos">
            <thead class="thead-light">
                <tr>
                    <th style="width:55px;">#</th>
                    <th style="width:90px;">Cédula</th>
                    <th class="sortable" style="cursor:pointer;">Nombre <i class="fas fa-sort fa-xs text-muted"></i></th>
                    <th>Cargo</th>
                    <th style="width:90px;">Horario</th>
                    <th style="width:90px;">Hora</th>
                    <th style="width:70px;">Min+</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                while ($fila = $resultado->fetch_assoc()):
                    $partes = [];
                    foreach (['n1', 'n2', 'a1', 'a2'] as $c) {
                        if (isset($fila[$c]) && $fila[$c] !== null && trim($fila[$c]) !== '') {
                            $partes[] = trim($fila[$c]);
                        }
                    }
                    $nombre = !empty($partes) ? implode(' ', $partes) : '[Sin nombre]';
                    if ($nombre !== '[Sin nombre]') {
                        $nombre = mb_convert_case(mb_strtolower($nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                    }
                    $cargo = isset($fila['cargo']) ? formatearCargo($fila['cargo']) : '';
                    $minTarde = '';
                    if (!empty($fila['hora']) && !empty($fila['horario'])) {
                        $t1 = strtotime($fila['horario']);
                        $t2 = strtotime($fila['hora']);
                        if ($t1 !== false && $t2 !== false) {
                            $diff = ($t2 - $t1) / 60;
                            if ($diff < 0) {
                                $diff = 0;
                            }
                            $minTarde = (int)$diff;
                        }
                    }
                ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($fila['cedula']); ?></td>
                        <td><?php echo htmlspecialchars($nombre); ?></td>
                        <td><?php echo htmlspecialchars($cargo); ?></td>
                        <td><?php echo htmlspecialchars(substr($fila['horario'], 0, 5)); ?></td>
                        <td><?php echo htmlspecialchars(substr($fila['hora'], 0, 5)); ?></td>
                        <td><?php echo htmlspecialchars($minTarde); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php if ($total > 0): ?>
    <script>
        (function() {
            function debounce(fn, ms) {
                let t;
                return function() {
                    clearTimeout(t);
                    const a = arguments;
                    t = setTimeout(() => fn.apply(this, a), ms);
                }
            };
            const input = document.getElementById('filtroRetardos');
            const tabla = document.getElementById('tablaRetardos');
            if (!tabla) return;
            const tbody = tabla.querySelector('tbody');

            function normaliza(txt) {
                return txt.toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu, '');
            }

            function renumerar() {
                let idx = 1;
                tbody.querySelectorAll('tr').forEach(tr => {
                    if (tr.style.display !== 'none') {
                        tr.querySelector('td').textContent = idx++;
                    }
                });
            }
            if (input) {
                input.addEventListener('input', debounce(function() {
                    const q = normaliza(input.value.trim());
                    tbody.querySelectorAll('tr').forEach(tr => {
                        const t = normaliza(tr.textContent);
                        tr.style.display = (!q || t.indexOf(q) !== -1) ? '' : 'none';
                    });
                    renumerar();
                }, 160));
            }
            // Ordenar por nombre
            const thNombre = tabla.querySelector('th.sortable');
            let ordenAsc = true;
            thNombre && thNombre.addEventListener('click', function() {
                const filas = [...tbody.querySelectorAll('tr')];
                filas.sort((a, b) => {
                    const na = normaliza(a.children[2].textContent);
                    const nb = normaliza(b.children[2].textContent);
                    if (na < nb) return ordenAsc ? -1 : 1;
                    if (na > nb) return ordenAsc ? 1 : -1;
                    return 0;
                });
                tbody.innerHTML = '';
                filas.forEach(f => tbody.appendChild(f));
                ordenAsc = !ordenAsc;
                const icon = thNombre.querySelector('i');
                if (icon) {
                    icon.className = 'fas fa-sort-' + (ordenAsc ? 'alpha-down' : 'alpha-up') + ' fa-xs';
                }
                renumerar();
            });
        })();
    </script>
<?php endif; ?>