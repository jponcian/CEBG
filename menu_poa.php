<li class="nav-header"><a data-toggle="tooltip" title="INDICE MÓDULO POAI" href="#" onClick="manual_poa();">POAI (Guía del Módulo)</a></li>

<?php
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'POA' AND accesos_individual.menu = 'Registro'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
	<li class="nav-item">

		<a href="#" class="nav-link">
			<i class="nav-icon far fa-edit"></i>
			<p>
				Registro
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>

		<ul class="nav nav-treeview">
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(62) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu99(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Poai & Proyecto</p>
					</a>
				</li>
			<?php
			}
			?>
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(63) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu100(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Metas & Actividades</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'POA' AND accesos_individual.menu = 'Gestion'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
	<li class="nav-item">

		<a href="#" class="nav-link">
			<i class="nav-icon fa-solid fa-list-check"></i>
			<p>
				Gestion
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>

		<ul class="nav nav-treeview">
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(73) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu101(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Actividades</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'POA' AND accesos_individual.menu = 'Consultas'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
	<li class="nav-item">

		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-search"></i>
			<p>
				Consultas
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>

		<ul class="nav nav-treeview">
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(90) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu108(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Balance</p>
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