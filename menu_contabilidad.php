<li class="nav-header"><a data-toggle="tooltip" title="INDICE MÓDULO CONTABILIDAD" href="#" onClick="manual_contabilidad();">CONTABILIDAD (Guía del Módulo)</a></li>

<?php
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'contabilidad' AND accesos_individual.menu = 'Retenciones'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
	<li class="nav-item">
		<a href="#" class="nav-link">
			<i class="nav-icon fas fa-user-lock"></i>
			<p>
				Retenciones
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>

		<ul class="nav nav-treeview">
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(74) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu31(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Orden de Pago</p>
					</a>
				</li>
			<?php
			}
			?>
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(75) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu38(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Generar TXT y XML</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'contabilidad' AND accesos_individual.menu = 'Libros'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
	<li class="nav-item">

		<a href="#" class="nav-link">
			<i class="nav-icon fa-solid fa-book"></i>
			<p>
				Libros
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>

		<ul class="nav nav-treeview">
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(106) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu125(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Libro Banco</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'contabilidad' AND accesos_individual.menu = 'Codificacion'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
	<li class="nav-item">

		<a href="#" class="nav-link">
			<i class="nav-icon fa-regular fa-pen-to-square"></i>
			<p>
				Modificaciones
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>

		<ul class="nav nav-treeview">
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(76) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu29(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Cuentas</p>
					</a>
				</li>
			<?php
			}
			?>
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(77) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu30(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Chequeras</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'contabilidad' AND accesos_individual.menu = 'Consultas'";
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
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(78) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu75(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Descuentos TH</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'contabilidad' AND accesos_individual.menu = 'Reportes'";
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
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(79) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu35(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Retenciones</p>
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