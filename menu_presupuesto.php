<li class="nav-header"><a data-toggle="tooltip" title="INDICE MÓDULO PRESUPUESTO" href="#" onClick="manual_presupuesto();">PRESUPUESTO (Guía del Módulo)</a></li>

<?php
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Presupuesto' AND accesos_individual.menu = 'Presupuesto'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="far fa-file-powerpoint"></i>
			<p>
				Presupuesto
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>

		<ul class="nav nav-treeview">
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(66) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu28(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Consultar Presupuesto</p>
					</a>
				</li>
			<?php
			}
			?>
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(66) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu39(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Creditos Adicionales</p>
					</a>
				</li>
			<?php
			}
			?>
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(68) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu41(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Traspasos</p>
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
<?php
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Presupuesto' AND accesos_individual.menu = 'Codificacion'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
	<li class="nav-item">

		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-receipt"></i>
			<p>
				Codificacion (Crear)
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>

		<ul class="nav nav-treeview">
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(69) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu44(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Actividades y Partidas</p>
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
<?php
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Presupuesto' AND accesos_individual.menu = 'Reportes'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
	<li class="nav-item">

		<a href="#" class="nav-link">
			<i class="nav-icon far fa-copy"></i>
			<p>
				Reportes
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>

		<ul class="nav nav-treeview">
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(70) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu37(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Libro de Partidas</p>
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