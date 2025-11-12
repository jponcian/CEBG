<li class="nav-header"><a data-toggle="tooltip" title="INDICE MÓDULO ORDENACION DE PAGOS" href="#" onClick="manual_admon();">ORDENACION DE PAGOS (Guía del Módulo)</a></li>

<?php
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Ordenacion de Pagos' AND accesos_individual.menu = 'Compromiso'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">
    <a href="#" class="nav-link">
      <i class="far fa-credit-card"></i>
      <p>
        Compromiso
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(23) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu25(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Solicitud de Pago</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(24) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu26(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Orden Financiera</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Ordenacion de Pagos' AND accesos_individual.menu = 'Orden de Pago'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">

    <a href="#" class="nav-link">
      <i class="nav-icon far fa-file-powerpoint"></i>
      <p>
        Orden de Pago
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(25) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu12(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Generar OP (Aprobar)</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(26) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu13(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>OP (Retenciones)</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(27) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu14(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Comprobante de Pago</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Ordenacion de Pagos' AND accesos_individual.menu = 'Pagos TH'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">

    <a href="#" class="nav-link">
      <i class="nav-icon fas fa-user-friends"></i>
      <p>
        Pagos (TH)
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(29) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu4(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Nomina Pendiente</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(30) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu9(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>OP y Comprobante de Pago</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Ordenacion de Pagos' AND accesos_individual.menu = 'Pagos (Viaticos)'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">

    <a href="#" class="nav-link">
      <i class="nav-icon fa-solid fa-taxi"></i>
      <p>
        Pagos (Viaticos)
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(71) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu18(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Viatico Pendiente</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(72) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu24(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>OP y Comprobante de Pago</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Ordenacion de Pagos' AND accesos_individual.menu = 'Modificaciones'";
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
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(31) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu56(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Solicitud</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(32) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu34(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Concepto (Orden Pago)</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(33) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu80(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Numeración (Orden Pago)</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(34) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu48(); " data-toggle="modal" data-target="#modal_normal">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Apartar Orden Pago</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(35) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu59(); " data-toggle="modal" data-target="#modal_normal">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Apartar Orden Financiera</p>
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
            <p>Modificar Firmas</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Ordenacion de Pagos' AND accesos_individual.menu = 'Consultas'";
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
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(36) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu19(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Orden de Pago</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(37) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu45(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Orden Financiera</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(38) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu20(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Comprobante de Pago</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Ordenacion de Pagos' AND accesos_individual.menu = 'Opciones'";
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
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(39) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu22(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Reversar</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(40) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu17(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Anular</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Ordenacion de Pagos' AND accesos_individual.menu = 'Reportes'";
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
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(61) == 1)
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