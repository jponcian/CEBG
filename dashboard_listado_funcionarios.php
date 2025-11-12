<?php
session_start();
if (!isset($_SESSION['VERIFICADO']) || $_SESSION['VERIFICADO'] != 'SI') {
    echo 'Sesión no válida';
    exit;
}
include_once "conexion.php";
header('Content-Type: text/html; charset=UTF-8');

// Función para formatear cargos: convierte MAYÚSCULAS a estilo título español conservando acrónimos y sufijos como (E)
function formatearCargo($cargoRaw)
{
    if ($cargoRaw === null) return '';
    $cargo = trim($cargoRaw);
    if ($cargo === '') return '';

    // Separar posible sufijo (E) u otros paréntesis finales para preservarlos tal cual
    $sufijo = '';
    if (preg_match('/\s*(\([^)]*\))\s*$/u', $cargo, $m)) {
        $sufijo = $m[1];
        $cargo = trim(substr($cargo, 0, -strlen($m[0])));
    }

    // Si está mayoritariamente en mayúsculas lo tratamos; si ya tiene mezcla lo dejamos casi igual formateando solo palabras clave
    $mayusculas = preg_replace('/[^A-ZÁÉÍÓÚÜÑ]/u', '', $cargo);
    $minusculas = preg_replace('/[^a-záéíóúüñ]/u', '', $cargo);
    $esMayus = ($mayusculas !== '' && strlen($minusculas) < strlen($mayusculas) * 0.3);

    // Palabras que deben quedar en minúscula (salvo si son la primera) según estilo español
    $minWords = ['de', 'del', 'la', 'las', 'los', 'y', 'e', 'en', 'para', 'por', 'a', 'o', 'u', 'con', 'sin'];
    // Acrónimos que deben permanecer en mayúsculas
    $acronimos = ['TIC', 'TI', 'RRHH', 'RRHH', 'TI', 'TI', 'ODS'];

    $palabras = preg_split('/\s+/u', mb_strtolower($cargo, 'UTF-8'));
    $resultado = [];
    foreach ($palabras as $idx => $p) {
        if ($p === '') continue;
        // Si coincide con acrónimo original en mayúsculas se reestablece
        $pMayus = mb_strtoupper($p, 'UTF-8');
        if (in_array($pMayus, $acronimos, true)) {
            $resultado[] = $pMayus;
            continue;
        }
        if ($idx > 0 && in_array($p, $minWords, true)) {
            $resultado[] = $p; // minúscula
        } else {
            // Capitalizar considerando tildes
            $primera = mb_substr($p, 0, 1, 'UTF-8');
            $resto = mb_substr($p, 1, null, 'UTF-8');
            $resultado[] = mb_strtoupper($primera, 'UTF-8') . $resto;
        }
    }
    $cargoFormateado = implode(' ', $resultado);
    if ($sufijo) {
        $cargoFormateado .= ' ' . $sufijo; // Mantener exactamente el sufijo
    }
    return $cargoFormateado;
}

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$conexion = $_SESSION['conexionsql'];

// Validar tipo
if (!in_array($tipo, ['listos', 'faltantes'])) {
    echo '<div class="alert alert-warning">Tipo no reconocido.</div>';
    exit;
}

// Obtener estatus actual de la evaluación (proyecto activo) si existe función proyecto_actual
$estatus_actual = null;
if (function_exists('proyecto_actual')) {
    $pa = proyecto_actual(); // array(id, estatus, descripcion)
    if (isset($pa[1]) && is_numeric($pa[1])) {
        $estatus_actual = (int)$pa[1];
    }
}
// Fallback si no se obtiene estatus
if ($estatus_actual === null) {
    $estatus_actual = 1;
}

if ($tipo === 'listos') {
    $titulo = 'Funcionarios Listos';
    $condicion = "odis >= $estatus_actual";
} else {
    $titulo = 'Funcionarios Faltantes';
    $condicion = "odis < $estatus_actual";
}

$consulta = "SELECT 
    cedula,
    TRIM(rac.nombre) AS n1,
    TRIM(rac.nombre2) AS n2,
    TRIM(rac.apellido) AS a1,
    TRIM(rac.apellido2) AS a2,
    cargo,
    odis
FROM rac
WHERE evaluar_odis=1 AND $condicion
  AND nomina <> 'EGRESADOS' AND nomina <> 'JUBILADOS' AND nomina <> 'PENSIONADO'
ORDER BY (cedula+0)"; // echo $consulta;
$resultado = $conexion->query($consulta);

$total = $resultado ? $resultado->num_rows : 0;
?>
<h5 class="mb-2 d-flex justify-content-between align-items-center">
    <span><?php echo $titulo; ?> <span class="badge badge-secondary"><?php echo $total; ?></span></span>
    <?php if ($total > 0): ?>
        <span style="flex:0 0 260px;">
            <input type="text" id="filtroListadoFuncionarios" class="form-control form-control-sm" placeholder="Filtrar... (cédula / nombre / cargo)">
        </span>
    <?php endif; ?>
</h5>
<?php if ($total === 0): ?>
    <div class="alert alert-info mb-0">No hay registros.</div>
<?php else: ?>
    <div class="table-responsive" style="max-height:60vh;">
        <table id="tablaListadoFuncionarios" class="table table-sm table-striped table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th style="width:55px;">#</th>
                    <th style="width:90px;">Cédula</th>
                    <th class="sortable" data-campo="nombre" style="cursor:pointer;">Nombre <i class="fas fa-sort fa-xs text-muted"></i></th>
                    <th>Cargo</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                while ($fila = $resultado->fetch_assoc()):
                    // Construcción robusta del nombre evitando valores nulos y espacios extras
                    $partesNombre = [];
                    foreach (['n1', 'n2', 'a1', 'a2'] as $c) {
                        if (isset($fila[$c]) && $fila[$c] !== null) {
                            $val = trim($fila[$c]);
                            if ($val !== '') $partesNombre[] = $val;
                        }
                    }
                    if (!empty($partesNombre)) {
                        $nombreMostrarRaw = implode(' ', $partesNombre);
                    } else {
                        $nombreMostrarRaw = '[Sin nombre]';
                    }
                    // Normalizar espacios múltiples por seguridad
                    $nombreMostrarRaw = preg_replace('/\s+/', ' ', $nombreMostrarRaw);
                    // Formato Title Case con soporte acentos
                    if ($nombreMostrarRaw !== '[Sin nombre]') {
                        $nombreMostrar = mb_convert_case(mb_strtolower($nombreMostrarRaw, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                    } else {
                        $nombreMostrar = $nombreMostrarRaw;
                    }
                ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($fila['cedula']); ?></td>
                        <td><?php echo htmlspecialchars($nombreMostrar); ?></td>
                        <td><?php echo htmlspecialchars(formatearCargo($fila['cargo'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php if ($total > 0): ?>
    <script>
        // Función debounced para rendimiento
        function debounce(fn, delay) {
            let t;
            return function() {
                clearTimeout(t);
                const args = arguments;
                t = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        (function() {
            const input = document.getElementById('filtroListadoFuncionarios');
            const tabla = document.getElementById('tablaListadoFuncionarios');
            if (!input || !tabla) return;
            const tbody = tabla.querySelector('tbody');
            let ordenAsc = true;

            function normaliza(txt) {
                return txt.toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu, '');
            }

            function renumerar() {
                let idx = 1;
                tbody.querySelectorAll('tr').forEach(tr => {
                    if (tr.style.display !== 'none') {
                        const celda = tr.querySelector('td');
                        if (celda) celda.textContent = idx++;
                    }
                });
            }

            const filtrar = debounce(function() {
                const q = normaliza(input.value.trim());
                tbody.querySelectorAll('tr').forEach(tr => {
                    const texto = normaliza(tr.textContent);
                    if (!q || texto.indexOf(q) !== -1) {
                        tr.style.display = '';
                    } else {
                        tr.style.display = 'none';
                    }
                });
                renumerar();
            }, 180);

            input.addEventListener('input', filtrar);

            // Ordenar por nombre
            const thNombre = tabla.querySelector('th.sortable');
            thNombre && thNombre.addEventListener('click', function() {
                const filas = Array.from(tbody.querySelectorAll('tr'));
                filas.sort((a, b) => {
                    const na = normaliza(a.children[2].textContent);
                    const nb = normaliza(b.children[2].textContent);
                    if (na < nb) return ordenAsc ? -1 : 1;
                    if (na > nb) return ordenAsc ? 1 : -1;
                    return 0;
                });
                // Limpiar y reinsertar
                tbody.innerHTML = '';
                filas.forEach(f => tbody.appendChild(f));
                ordenAsc = !ordenAsc;
                // Actualiza icono
                const icon = thNombre.querySelector('i');
                if (icon) {
                    icon.className = 'fas fa-sort-' + (ordenAsc ? 'alpha-down' : 'alpha-up') + ' fa-xs';
                }
                renumerar();
            });
        })();
    </script>
<?php endif; ?>