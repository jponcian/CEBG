<li class="nav-header"><a data-toggle="tooltip" title="INDICE MÓDULO COMPRAS" href="#" onClick="manual_compras();">COMPRA (Guía del Módulo)</a></li>

<?php
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'compras' AND accesos_individual.menu = 'Compromiso'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">
    <a href="#" class="nav-link">
      <i class="nav-icon far fa-credit-card"></i>
      <p>
        Compromiso
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(19) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu10(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Presupuesto</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(20) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu15(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Compra y/o Servicio</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'compras' AND accesos_individual.menu = 'Modificaciones'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">

    <a href="#" class="nav-link">
      <i class="nav-icon far fa-edit"></i>
      <p>
        Modificaciones
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(21) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu16(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Orden</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(22) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu81(); " data-toggle="modal" data-target="#modal_largo">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Firmas</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'compras' AND accesos_individual.menu = 'Consultas'";
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
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(43) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu60(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Presupuesto</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(44) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu11(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Compra y/o Servicio</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'compras' AND accesos_individual.menu = 'Opciones'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">

    <a href="#" class="nav-link">
      <i class="nav-icon fas fa-history"></i>
      <p>
        Opciones
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(45) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu18(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Anular</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(46) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu24(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Reversar</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(47) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu87(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Eliminar</p>
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