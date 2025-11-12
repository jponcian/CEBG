<li class="nav-header"><a data-toggle="tooltip" title="INDICE MÓDULO ARCHIVO" href="#" onClick="manual_archivo();">ARCHIVO (Guía del Módulo)</a></li>

<?php
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Archivo' AND accesos_individual.menu = 'Expedientes'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="far fa-file-powerpoint"></i>
			<p>
				Expedientes
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>

		<ul class="nav nav-treeview">
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(112) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu130(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Registro</p>
					</a>
				</li>
			<?php
			}
			?>
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(113) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu131(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Prestamo</p>
					</a>
				</li>
			<?php
			}
			?>
		</ul>

	</li>
<?php
}
?>