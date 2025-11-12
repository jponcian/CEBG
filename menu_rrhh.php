<li class="nav-header"><a data-toggle="tooltip" title="INDICE MÓDULO TH" href="#" onClick="manual_rrhh();">TH (Guía del Módulo)</a></li>

<?php
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'RRHH' AND accesos_individual.menu = 'Nomina'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">
    <a href="#" class="nav-link">
      <i class="nav-icon fas fa-receipt"></i>
      <p>
        Nomina
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(11) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu1();">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Generar Pago</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(104) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu57(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Generar Aguinaldos</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(12) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu51(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Ajustes</p>
          </a>
        </li>
      <?php
      }
      ?> <?php
          if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(13) == 1)
          //-------------
          {
          ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu2(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Gestion</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'RRHH' AND accesos_individual.menu = 'Pagos Adicionales'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">

    <a href="#" class="nav-link">
      <i class="nav-icon far fa-file-powerpoint"></i>
      <p>
        Pagos Adicionales
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(14) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu43(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Otros Pagos</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'RRHH' AND accesos_individual.menu = 'Empleados'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">

    <a href="#" class="nav-link">
      <i class="nav-icon fas fa-user-friends"></i>
      <p>
        Empleados
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(15) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu6(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Ficha Personal</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(15) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu136(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Carga Familiar</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(114) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu132(); " data-toggle="modal" data-target="#modal_normal">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Recibo de Pago</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(86) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu103(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Vacaciones</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(87) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu105(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Permisos y/o Reposos</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(111) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu129(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Comisiones</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'RRHH' AND accesos_individual.menu = 'Evaluaciones'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">

    <a href="#" class="nav-link">
      <i class="fa-solid fa-arrows-to-eye"></i>
      <p>
        Evaluaciones
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(96) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu113(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Gestion</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(98) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu115(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Asignar Odi</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or 1 == 1) //valida_menu(99)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu116(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Aceptar Odi</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(101) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu118(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Evaluar Supervisado</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or 1 == 1) // valida_menu(102)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu119(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Aceptar Evaluacion</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(97) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu114(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Gestion Odi</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(100) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu117(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Gestion Competencias</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'RRHH' AND accesos_individual.menu = 'Formatos'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">

    <a href="#" class="nav-link">
      <i class="nav-icon far fa-copy"></i>
      <p>
        Formatos
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(84) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu96(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Control de Asistencia</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'RRHH' AND accesos_individual.menu = 'Ajustes'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">

    <a href="#" class="nav-link">
      <i class="nav-icon fa-solid fa-gears"></i>
      <p>
        Ajustes
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(109) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu127(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Areas</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(88) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu106(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Cargos</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(89) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu107(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Bono Ayuda</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(17) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu82(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Sueldo Minimo</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(18) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu83(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>CestaTickets</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(18) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu123(); " data-toggle="modal" data-target="#modal_normal">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Prima por Hijos</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(110) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu128(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Dias Feriados</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(115) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu133(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Horario</p>
          </a>
        </li>
      <?php
      }
      ?>
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(116) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu134(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Asignaciones</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'RRHH' AND accesos_individual.menu = 'Reversar'";
$tabla_x = $_SESSION['conexionsql']->query($consulta_x); //echo $consulta_x;
if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1)
//-------------
{
?>
  <li class="nav-item">

    <a href="#" class="nav-link">
      <i class="nav-icon fas fa-history"></i>
      <p>
        Reversar
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>

    <ul class="nav nav-treeview">
      <?php
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(94) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu112(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Solicitud de Pago</p>
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
$consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM 	accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'RRHH' AND accesos_individual.menu = 'Reportes'";
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
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(85) == 1)
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
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(85) == 1)
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
      if ($_SESSION['ADMINISTRADOR'] == 1 or valida_menu(102) == 1)
      //-------------
      {
      ?>
        <li class="nav-item">
          <a href="#" class="nav-link" onClick="menu120(); ">
            <i class="fas fa-circle nav-icon ml-3"></i>
            <p>Per., Rep. o Vacaciones</p>
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