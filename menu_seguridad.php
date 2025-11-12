<li class="nav-header"><a data-toggle="tooltip" title="INDICE MÓDULO SEGURIDAD" href="#" onClick="manual_seguridad();">SEGURIDAD (Guía del Módulo)</a></li>

<?php
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Seguridad' AND accesos_individual.menu = 'Asistencia y Visita'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">

    <a href="#" class="nav-link">
      <i class="nav-icon fa-solid fa-person-walking"></i>
      <p>
        Asistencia y Visitas
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(8) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu86(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Gestion</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu135(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Telefono</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Seguridad' AND accesos_individual.menu = 'Reportes'";
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
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(9) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu92(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Asistencia (Estadistica)</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(9) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu95(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Asistencia (Detalle)</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(10) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu93(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Visitas</p>
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