<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

# Ahorausuario sigue siendo un objeto, con propiedades. 
# Podemos acceder a ellas dependiendo de cómo las hayamos nombrado en el lado del cliente

$id = $data['registro']['id'];
$id_contribuyente = $data['registro']['id_contribuyente'];
$placa = $data['registro']['numero'];
$marca = $data['registro']['marca'];
$modelo = $data['registro']['modelo'];
$anno = $data['registro']['anno'];
$color = $data['registro']['color'];
$usuario = $data['registro']['usuario'];

require __DIR__ . '/vehiculos_rutinas.php';

$vehiculo = new CrudAdminVehiculos();

echo $vehiculo->Editar($id,$id_contribuyente,$placa,$marca,$modelo,$anno,$color,$usuario);
//echo $id.' - '.$numero.' - '.$fecha.' - '.$descripcion.' - '.$direccion.' - '.$representante.' - '.$cedula.' - '.$vencimiento.' - '.$obreros.' - '.$empleados.' - '.$turnos.' - '.$manana.' - '.$tarde.' - '.$nocturnos.' - '.$talento_vivo.' - '.$rockola.' - '.$otro.' - '.$usuario.' - '.$rif;
//echo 'Estas fechas: '.$fecha.' --- '.$vencimiento;

?>