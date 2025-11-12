<li class="nav-header"><a data-toggle="tooltip" title="INDICE MÓDULO TECNOLOGIA" href="#" onClick="manual_tecnologia();">TECNOLOGÍA (Guía del Módulo)</a></li>

<?php
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'TECNOLOGIA' AND accesos_individual.menu = 'Gestion'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
	<li class="nav-item">

		<a href="#" class="nav-link">
			<i class="nav-icon fa-solid fa-gears"></i>
			<p>
				Gestión
				<i class="right fas fa-angle-left"></i>
			</p>
		</a>

		<ul class="nav nav-treeview">
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(93) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu111(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Usuarios</p>
					</a>
				</li>
			<?php
			}
			?>
			<?php
			if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(103) == 1)
			//-------------
			{
			?>
				<li class="nav-item">
					<a href="#" class="nav-link" onClick="menu121(); ">
						<i class="fas fa-circle nav-icon ml-3"></i>
						<p>Jefatura</p>
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