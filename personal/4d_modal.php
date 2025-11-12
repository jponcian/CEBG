<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=15;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT * FROM rac WHERE rac = ".$_GET['id'].";";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
?>

<form id="form999" name="form999" method="post" onSubmit="return evitar();">
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center" >
		<div class="tab modal-title w-100 font-weight-bold py-2" style="background-color:#0275d8; color:#FFFFFF" >
			<button class="tablinks text-white" id="tab1b" onclick="pesta(event, 'tab1')">B&aacute;sicos</button>
			<button class="tablinks text-white" onclick="pesta(event, 'tab2')">Personales</button>
			<button class="tablinks text-white" onclick="pesta(event, 'tab6')">Direcciones</button>
			<button class="tablinks text-white" onclick="pesta(event, 'tab3')">Formacion</button>
			<button class="tablinks text-white" onclick="pesta(event, 'tab4')">Capacitación</button>
			<button class="tablinks text-white" onclick="pesta(event, 'tab5')">Experiencia</button>
			<span class="close" ><button type="button" class="close" data-dismiss="modal">X</button></span>
		</div>
<!--	<div align="right" style="background-color:#0275d8; color:#FFFFFF"><button type="button" class="close" data-dismiss="modal">&times;</button></div>-->
    <input type="hidden" id="oid" name="oid" value="<?php echo $registro->rac; ?>"/>
</div>
<!-- Modal body -->
			

<div id="tab1" class="tabcontent">
	<div>
	<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group-text"><?php echo $registro->cedula.' - '.$registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2; ?></div>
				</div>
			</div>
			
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group">
					<input placeholder="1er Nombre" onChange="validar_campo('txt_nombre1');" id="txt_nombre1" name="txt_nombre1" class="form-control" value="<?php echo ($registro->nombre); ?>" type="text" style="text-align:center" />
					<input placeholder="2do Nombre" onChange="validar_campo('txt_nombre2');" id="txt_nombre2" name="txt_nombre2" class="form-control" value="<?php echo ($registro->nombre2); ?>" type="text" style="text-align:center" />
					<input placeholder="1er Apellido" onChange="validar_campo('txt_apellido1');" id="txt_apellido1" name="txt_apellido1" class="form-control" value="<?php echo ($registro->apellido); ?>" type="text" style="text-align:center" />
					<input placeholder="2do Apellido" onChange="validar_campo('txt_apellido2');" id="txt_apellido2" name="txt_apellido2" class="form-control" value="<?php echo ($registro->apellido2); ?>" type="text" style="text-align:center" />
					
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-sm-3">
					<div class="input-group">
						<div class="input-group-text">Codigo</div>
						<input onChange="validar_campo('txt_codigo');" type="text" style="text-align:center" class="form-control " name="txt_codigo" id="txt_codigo" placeholder="Codigo"  minlength="1" maxlength="5" value="<?php  echo ($registro->codigo);?>" >
					</div>
				</div>	
				
				<div class="form-group col-sm-5">
					<div class="input-group">
						<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Nacimiento</div>
						<input onChange="validar_campo('txt_nacimiento');" type="text" style="text-align:center" class="form-control " name="txt_nacimiento" id="txt_nacimiento" placeholder="Fecha de Ingreso"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_nacimiento);?>" >
					</div>
				</div>	
				
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-venus-mars mr-2"></i></div>
						<select class="custom-select" style="font-size: 14px" name="txt_sexo" id="txt_sexo" onchange="">
						<option value="0"> -SELECCIONE- </option>
						<?php
						//--------------------
						echo '<option ';
						if ($registro->sexo=='F')	{echo ' selected="selected" ';}
						echo ' value="F">Femenino</option>';
						//--------------------
						echo '<option ';
						if ($registro->sexo=='M')	{echo ' selected="selected" ';}
						echo ' value="M">Masculino</option>';
						?>
						</select>
					</div>
				</div>

		</div>
	
			<div class="row">

				<div class="form-group col-sm-4">
					<div class="input-group"><div class="input-group-text"><i class="fas fa-mobile-alt mr-2"></i></div><input onChange="validar_campo('txt_telefono');" id="txt_telefono" name="txt_telefono" class="form-control" value="<?php echo ($registro->telefono); ?>" type="text" maxlength="30" style="text-align:center" />
					
				</div>
				</div>

					<div class="form-group col-sm-4">
						<div class="input-group">
							<div class="input-group-text"><i class="far fa-envelope mr-2"></i></div>
							<input onChange="validar_campo('txt_correo');" id="txt_correo" name="txt_correo" class="form-control" value="<?php echo ($registro->correo); ?>" type="email" maxlength="50" style="text-align:center" placeholder="Correo electrónico" />
						</div>
					</div>
				
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-graduation-cap mr-2"></i></div>
					
					<select class="custom-select" style="font-size: 14px" name="txt_profesion" id="txt_profesion" onchange="">
					<option value="0"> -SELECCIONE- </option>
					<?php
					//--------------------
					echo '<option ';
					if ($registro->profesion=='0' or $registro->profesion=='1')	{echo ' selected="selected" ';}
					echo ' value="0">Sin Profesion</option>';
					//--------------------
					echo '<option ';
					if ($registro->profesion=='2')	{echo ' selected="selected" ';}
					echo ' value="2">TSU</option>';
					//--------------------
					echo '<option ';
					if ($registro->profesion=='3')	{echo ' selected="selected" ';}
					echo ' value="3">Universitario</option>';
					//--------------------
					echo '<option ';
					if ($registro->profesion=='4')	{echo ' selected="selected" ';}
					echo ' value="4">Especialista</option>';
					//--------------------
					echo '<option ';
					if ($registro->profesion=='5')	{echo ' selected="selected" ';}
					echo ' value="5">Maestria</option>';
					//--------------------
					echo '<option ';
					if ($registro->profesion=='6')	{echo ' selected="selected" ';}
					echo ' value="6">Doctorado</option>';
					?>

					</select>
					
				</div>
			</div>
		</div>
		
			<div class="row">
				<div class="form-group col-sm-5">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-university mr-2"></i></div>
					<input onChange="validar_campo('txt_cuenta');" id="txt_cuenta" data-placement="top" title="Cuenta Bancaria" placeholder="Cuenta Bancaria" name="txt_cuenta" class="form-control" value="<?php echo ($registro->cuenta); ?>" type="text" style="text-align:center" />
					
				</div>
				</div>
				
				<div class="form-group col-sm-2">
					<div class="input-group">
						<div class="input-group-text"><i class="far fa-building mr-2"></i></div>
					<input onChange="validar_campo('txt_annos');" data-toggle="tooltip" data-placement="top" title="A&ntilde;os de Servicio en otros Organismos Publicos" id="txt_annos" placeholder="A&ntilde;os de Servicio" name="txt_annos" class="form-control" value="<?php echo ($registro->anos_servicio); ?>" type="text" maxlength="2" style="text-align:center" />
					
					</div>
				</div>

			</div>
			
			<div class="row">
		
				<div class="form-group col-sm-5">
					<div class="input-group">
						<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Ingreso</div>
						<input onChange="validar_campo('txt_ingreso');" type="text" style="text-align:center" class="form-control " name="txt_ingreso" id="txt_ingreso" placeholder="Fecha de Ingreso"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_ingreso);?>" >
					</div>
				</div>	
				
				<div class="form-group col-sm-5">
					<div class="input-group">
						<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Contrato</div>
						<input onChange="validar_campo('txt_contrato');" type="text" style="text-align:center" class="form-control " name="txt_contrato" id="txt_contrato" placeholder="Fecha de Ingreso"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_contrato);?>" >
					</div>
				</div>	
				
			</div>
	</div>
</div>
	
<div id="tab2" class="tabcontent">
	<div>
	<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group-text"><?php echo $registro->cedula.' - '.$registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2; ?></div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text">Grupo Sanguíneo</div>
						<input onChange="validar_campo('txt_sangre');" type="text" style="text-align:center" class="form-control " name="txt_sangre" id="txt_sangre" placeholder="Tipo"  minlength="1" maxlength="5" value="<?php  echo ($registro->sangre);?>" >
					</div>
				</div>	
				
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text"><i class="fa-solid fa-weight-scale mr-2"></i>Peso</div>
						<input onChange="validar_campo('txt_peso');" type="text" style="text-align:center" class="form-control " name="txt_peso" id="txt_peso" placeholder="Peso"  minlength="1" maxlength="5" value="<?php  echo ($registro->peso);?>" >
					</div>
				</div>	
				
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text"><i class="fa-solid fa-text-height mr-2"></i>Estatura</div>
						<input onChange="validar_campo('txt_estatura');" type="text" style="text-align:center" class="form-control " name="txt_estatura" id="txt_estatura" placeholder="Mts"  minlength="1" maxlength="5" value="<?php  echo ($registro->estatura);?>" >
					</div>
				</div>	

		</div>

		<div class="input-group-text" style="text-align: center"><strong><< TALLAS >></strong></div>
		
		<div class="row mt-0">
			<div class="form-group col-sm-3">
					<div class="input-group">
						<div class="input-group-text"><i class="fa-solid fa-shirt mr-2"></i>Camisa</div>
						<input onChange="validar_campo('txt_tallac');" type="text" style="text-align:center" class="form-control " name="txt_tallac" id="txt_tallac" placeholder="Talla"  minlength="1" maxlength="5" value="<?php  echo ($registro->tallac);?>" >
					</div>
				</div>	
				
				<div class="form-group col-sm-3">
					<div class="input-group">
						<div class="input-group-text">Pantalon</div>
						<input onChange="validar_campo('txt_tallap');" type="text" style="text-align:center" class="form-control " name="txt_tallap" id="txt_tallap" placeholder="Talla"  minlength="1" maxlength="5" value="<?php  echo ($registro->tallap);?>" >
					</div>
				</div>	
				
				<div class="form-group col-sm-3">
					<div class="input-group">
						<div class="input-group-text">Calzado</div>
						<input onChange="validar_campo('txt_tallaz');" type="text" style="text-align:center" class="form-control " name="txt_tallaz" id="txt_tallaz" placeholder="Talla"  minlength="1" maxlength="5" value="<?php  echo ($registro->tallaz);?>" >
					</div>
				</div>	
				
				<div class="form-group col-sm-3">
					<div class="input-group">
						<div class="input-group-text">Keepy</div>
						<input onChange="validar_campo('txt_tallak');" type="text" style="text-align:center" class="form-control " name="txt_tallak" id="txt_tallak" placeholder="Talla"  minlength="1" maxlength="5" value="<?php  echo ($registro->tallak);?>" >
					</div>
				</div>	
		</div>
	
			<div class="row">
				<div class="form-group col-sm-5">
					<div class="input-group">
						<div class="input-group-text"><i class="fa-regular fa-face-grin-wide mr-2"></i>Estado Civil</div>
						<select class="custom-select" style="font-size: 14px" name="txt_civil" id="txt_civil" onchange="">
						<option value="0">Seleccione</option>
						<?php
						//--------------------
						echo '<option ';
						if ($registro->civil=='Soltero/a')	{echo ' selected="selected" ';}
						echo ' value="Soltero/a">Soltero/a</option>';
						//--------------------
						echo '<option ';
						if ($registro->civil=='Casado/a')	{echo ' selected="selected" ';}
						echo ' value="Casado/a">Casado/a</option>';
						//--------------------
						echo '<option ';
						if ($registro->civil=='Divorciado/a')	{echo ' selected="selected" ';}
						echo ' value="Divorciado/a">Divorciado/a</option>';
						//--------------------
						echo '<option ';
						if ($registro->civil=='Viudo/a')	{echo ' selected="selected" ';}
						echo ' value="Viudo/a">Viudo/a</option>';
						?>
						</select>
					</div>
				</div>

				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text"><i class="fa-solid fa-glasses mr-2"></i>USA LENTES</div>
						<select class="custom-select" style="font-size: 14px" name="txt_lentes" id="txt_lentes" onchange="">
						<option value="0">Seleccione</option>
						<?php
						//--------------------
						echo '<option ';
						if ($registro->lentes=='SI')	{echo ' selected="selected" ';}
						echo ' value="SI">Si</option>';
						//--------------------
						echo '<option ';
						if ($registro->lentes=='NO')	{echo ' selected="selected" ';}
						echo ' value="NO">No</option>';
						?>
						</select>
					</div>
				</div>

		</div>
	
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group">
						<div class="input-group-text">Deporte que Practica</div>
						<input onChange="validar_campo('txt_deporte');" type="text" style="text-align:left" class="form-control " name="txt_deporte" id="txt_deporte" placeholder=""  minlength="1" maxlength="255" value="<?php  echo ($registro->deporte);?>" >
					</div>
				</div>	
		</div>
	
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group">
						<div class="input-group-text">Destrezas u Oficio</div>
						<input onChange="validar_campo('txt_destreza');" type="text" style="text-align:left" class="form-control " name="txt_destreza" id="txt_destreza" placeholder=""  minlength="1" maxlength="255" value="<?php  echo ($registro->destreza);?>" >
					</div>
				</div>	

		</div>
		
	</div>
</div>
	
<div id="tab6" class="tabcontent">
	<div>
		<div class="row">
			<div class="form-group col-sm-12">
				<div class="input-group-text"><?php echo $registro->cedula.' - '.$registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2; ?></div>
			</div>
		</div>
		
		<div class="input-group-text"><strong>LUGAR DE NACIMIENTO</strong></div>

			<div class="row mt-1">
				<div class="form-group col-sm-4">
					<div class="input-group">
						<input onChange="validar_campo('txt_pais');" type="text" class="form-control " name="txt_pais" id="txt_pais" placeholder="País"  minlength="1" value="<?php  echo ($registro->nac_pais);?>" >
					</div>
				</div>	

				<div class="form-group col-sm-4">
					<div class="input-group">
						<input onChange="validar_campo('txt_estado');" type="text" class="form-control " name="txt_estado" id="txt_estado" placeholder="Estado"  minlength="1" value="<?php  echo ($registro->nac_estado);?>" >
					</div>
				</div>	

				<div class="form-group col-sm-4">
					<div class="input-group">
						<input onChange="validar_campo('txt_municipio');" type="text" class="form-control " name="txt_municipio" id="txt_municipio" placeholder="Municipio"  minlength="1" value="<?php  echo ($registro->nac_municipio);?>" >
					</div>
				</div>	
			</div>

			<div class="row">
				<div class="form-group col-sm-4">
					<div class="input-group">
						<input onChange="validar_campo('txt_parroquia');" type="text" class="form-control " name="txt_parroquia" id="txt_parroquia" placeholder="Parroquia"  minlength="1" value="<?php  echo ($registro->nac_parroquia);?>" >
					</div>
				</div>	

				<div class="form-group col-sm-5">
					<div class="input-group">
						<input onChange="validar_campo('txt_ciudad');" type="text" class="form-control " name="txt_ciudad" id="txt_ciudad" placeholder="Ciudad"  minlength="1" value="<?php  echo ($registro->nac_ciudad);?>" >
					</div>
				</div>	
			</div>		
		<div class="input-group-text"><strong>DIRECCIÓN DE HABITACIÓN ACTUAL</strong></div>

			<div class="row mt-1">

				<div class="form-group col-sm-4">
					<div class="input-group">
						<input onChange="validar_campo('txt_estadod');" type="text" class="form-control " name="txt_estadod" id="txt_estadod" placeholder="Estado"  minlength="1" value="<?php  echo ($registro->dir_estado);?>" >
					</div>
				</div>	

				<div class="form-group col-sm-4">
					<div class="input-group">
						<input onChange="validar_campo('txt_municipiod');" type="text" class="form-control " name="txt_municipiod" id="txt_municipiod" placeholder="Municipio"  minlength="1" value="<?php  echo ($registro->dir_municipio);?>" >
					</div>
				</div>	
			
				<div class="form-group col-sm-4">
					<div class="input-group">
						<input onChange="validar_campo('txt_parroquiad');" type="text" class="form-control " name="txt_parroquiad" id="txt_parroquiad" placeholder="Parroquia"  minlength="1" value="<?php  echo ($registro->dir_parroquia);?>" >
					</div>
				</div>	

			</div>

			<div class="row">	
				<div class="form-group col-sm-4">
					<div class="input-group">
						<input onChange="validar_campo('txt_ciudadd');" type="text" class="form-control " name="txt_ciudadd" id="txt_ciudadd" placeholder="Ciudad"  minlength="1" value="<?php  echo ($registro->dir_ciudad);?>" >
					</div>
				</div>	
				<div class="form-group col-sm-8">
					<div class="input-group">
						<input onChange="validar_campo('txt_direccion');" type="text" class="form-control " name="txt_direccion" id="txt_direccion" placeholder="Direccion de Habitacion"  minlength="1" value="<?php  echo ($registro->direccion_habitacion);?>" >
					</div>
				</div>	
			</div>

	</div>
</div>

<div id="tab3" class="tabcontent">
<div>
		<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group-text"><?php echo $registro->cedula.' - '.$registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2; ?></div>
				</div>
		</div>

	<div class="row">

		<div class="form-group col-sm-4">
			<div class="input-group">
				<input type="text" class="form-control " onChange="validar_campo('txt_prof');" name="txt_prof" id="txt_prof" placeholder="Profesion" >
			</div>
		</div>	

		<div class="form-group col-sm-4">
			<div class="input-group">
				<input type="text" class="form-control " onChange="validar_campo('txt_especialidad');" name="txt_especialidad" id="txt_especialidad" placeholder="Especialidad" >
			</div>
		</div>	

		<div class="form-group col-sm-4">
			<div class="input-group">
				<input type="text" style="text-align:center" onChange="validar_campo('txt_fechar');" class="form-control " name="txt_fechar" id="txt_fechar" placeholder="Fecha Registro"  minlength="1" maxlength="10" value="" >
			</div>
		</div>	

	</div>

	<div class="row">

		<div class="form-group col-sm-4">
			<div class="input-group">
				<input type="text" class="form-control " onChange="validar_campo('txt_colegio');" name="txt_colegio" id="txt_colegio" placeholder="Colegio o Registro" >
			</div>
		</div>	

		<div class="form-group col-sm-4">
			<div class="input-group">
				<input type="text" class="form-control " onChange="validar_campo('txt_numero');" name="txt_numero" id="txt_numero" placeholder="N° Colegio o Registro" >
			</div>
		</div>	

		<div class="form-group col-sm-4">
			<div class="input-group">
				<input type="text" class="form-control " onChange="validar_campo('txt_tomo');" name="txt_tomo" id="txt_tomo" placeholder="Tomo o Folio"  minlength="1" value="" >
			</div>
		</div>	

	</div>

		<div align="center">
			<button id="boton1" type="button" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar_titulo(<?php echo $registro->rac; ?>);"><i class="fas fa-plus prefix grey-text mr-1"></i> Agregar</button>
		</div>
</div>
<br>
	<div id="div2">
	</div>
	
</div>

<div id="tab4" class="tabcontent">
<div>
		<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group-text"><?php echo $registro->cedula.' - '.$registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2; ?></div>
				</div>
		</div>

	<div class="row">

		<div class="form-group col-sm-7">
			<div class="input-group">
				<input type="text" class="form-control " onChange="validar_campo('txt_curso');" name="txt_curso" id="txt_curso" placeholder="Curso, Taller Foro, Seminario" >
			</div>
		</div>	

		<div class="form-group col-sm-5">
			<div class="input-group">
				<input type="text" class="form-control " onChange="validar_campo('txt_instituto');" name="txt_instituto" id="txt_instituto" placeholder="Instituto o Instructor" >
			</div>
		</div>	

	</div>

	<div class="row">

		<div class="form-group col-sm-4">
			<div class="input-group">
				<input type="text" style="text-align:center" class="form-control " onChange="validar_campo('txt_desde');" name="txt_desde" id="txt_desde" placeholder="Desde" >
			</div>
		</div>	

		<div class="form-group col-sm-4">
			<div class="input-group">
				<input type="text" style="text-align:center" class="form-control " onChange="validar_campo('txt_hasta');" name="txt_hasta" id="txt_hasta" placeholder="Hasta" >
			</div>
		</div>	

		<div class="form-group col-sm-4">
			<div class="input-group">
				<input type="text" style="text-align:center" onChange="validar_campo('txt_duracion');" class="form-control " name="txt_duracion" id="txt_duracion" placeholder="Duración / Horas"  minlength="1" value="" >
			</div>
		</div>	

	</div>

	<div class="row">

		<div class="form-group col-sm-12">
			<div class="input-group">
				<input type="text" class="form-control " onChange="validar_campo('txt_observacion');" name="txt_observacion" id="txt_observacion" placeholder="Observación"  minlength="1" value="" >
			</div>
		</div>	

	</div>

		<div align="center">
			<button id="boton2" type="button" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar_capacitacion(<?php echo $registro->rac; ?>);"><i class="fas fa-plus prefix grey-text mr-1"></i> Agregar</button>
		</div>
</div>
<br>
	<div id="div3">
	</div>
	
</div>

<div id="tab5" class="tabcontent">
<div>
		<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group-text"><?php echo $registro->cedula.' - '.$registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2; ?></div>
				</div>
		</div>

	<div class="row">

		<div class="form-group col-sm-7">
			<div class="input-group">
				<input type="text" class="form-control " onChange="validar_campo('txt_institucion');" name="txt_institucion" id="txt_institucion" placeholder="Institución" >
			</div>
		</div>	

		<div class="form-group col-sm-5">
			<div class="input-group">
				<input type="text" class="form-control " onChange="validar_campo('txt_cargo');" name="txt_cargo" id="txt_cargo" placeholder="Cargo Desempeñado" >
			</div>
		</div>	

	</div>

	<div class="row">

		<div class="form-group col-sm-4">
			<div class="input-group">
				<input type="text" style="text-align:center" class="form-control " onChange="validar_campo('txt_desde1');" name="txt_desde1" id="txt_desde1" placeholder="Desde" >
			</div>
		</div>	

		<div class="form-group col-sm-4">
			<div class="input-group">
				<input type="text" style="text-align:center" class="form-control " onChange="validar_campo('txt_hasta1');" name="txt_hasta1" id="txt_hasta1" placeholder="Hasta" >
			</div>
		</div>	

	</div>

	<div class="row">

		<div class="form-group col-sm-12">
			<div class="input-group">
				<input type="text" class="form-control " onChange="validar_campo('txt_motivo');" name="txt_motivo" id="txt_motivo" placeholder="Motivo de Renuncia"  minlength="1" value="" >
			</div>
		</div>	

	</div>

		<div align="center">
			<button id="boton3" type="button" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar_experiencia(<?php echo $registro->rac; ?>);"><i class="fas fa-plus prefix grey-text mr-1"></i> Agregar</button>
		</div>
</div>
<br>
	<div id="div4">
	</div>
	
</div>
	
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar(1)" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cambios</button>
</div>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2({
	});
//	setTimeout(function()	{
//		$('#txt_cedula').focus();
//		},1000)	;
	//-----------
	$("#txt_desde1").datepicker();
	$("#txt_hasta1").datepicker();
	$("#txt_desde").datepicker();
	$("#txt_hasta").datepicker();
	$("#txt_fechar").datepicker();
	$("#txt_nacimiento").datepicker();
	$("#txt_ingreso").datepicker();
	$("#txt_contrato").datepicker();
	tablaf(<?php echo $registro->rac; ?>);
	tablac(<?php echo $registro->rac; ?>);
	tablae(<?php echo $registro->rac; ?>);
	//------------------------
	document.getElementById("tab1b").click();
});
//------------------------------ PARA ELIMINAR
function eliminar_experiencia(id, id_rep, parentesco)
	{
	Swal.fire({
		title: 'Estas seguro de eliminar el Registro?',
		text: "Esta acción no se puede revertir!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, borrar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			var parametros = "id=" + id+"&parentesco=" + parentesco;
				$.ajax({
				url: "personal/4n_eliminar.php?tipo=3",
				type: "POST",
				data: parametros,
				success: function(r) {
				alertify.success('Registro Eliminado Correctamente');
				//--------------
				tablae(id_rep);
				}
				});
			//-----------------------
			}
	})
}
//------------------------
function agregar_experiencia(id_rep)
{
//	if (validar()==0)
//		{
		$('#boton3').hide();
		var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'personal/4m_guardar.php?tipo=3',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	tablae(id_rep); $('#boton3').show();}
				else
					{	alertify.alert(data.msg);	}
				//--------------
				} 
			});
//		}
}//------------------------------ PARA ELIMINAR
function eliminar_capacitacion(id, id_rep, parentesco)
	{
	Swal.fire({
		title: 'Estas seguro de eliminar el Registro?',
		text: "Esta acción no se puede revertir!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, borrar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			var parametros = "id=" + id+"&parentesco=" + parentesco;
				$.ajax({
				url: "personal/4n_eliminar.php?tipo=2",
				type: "POST",
				data: parametros,
				success: function(r) {
				alertify.success('Registro Eliminado Correctamente');
				//--------------
				tablac(id_rep);
				}
				});
			//-----------------------
			}
	})
}
//------------------------
function agregar_capacitacion(id_rep)
{
//	if (validar()==0)
//		{
		$('#boton2').hide();
		var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'personal/4m_guardar.php?tipo=2',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	tablac(id_rep); $('#boton2').show();}
				else
					{	alertify.alert(data.msg);	}
				//--------------
				} 
			});
//		}
}
//------------------------------ PARA ELIMINAR
function eliminar_titulo(id, id_rep, parentesco)
	{
	Swal.fire({
		title: 'Estas seguro de eliminar el Registro?',
		text: "Esta acción no se puede revertir!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, borrar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			var parametros = "id=" + id+"&parentesco=" + parentesco;
				$.ajax({
				url: "personal/4n_eliminar.php?tipo=1",
				type: "POST",
				data: parametros,
				success: function(r) {
				alertify.success('Registro Eliminado Correctamente');
				//--------------
				tablaf(id_rep);
				}
				});
			//-----------------------
			}
	})
}
//------------------------
function agregar_titulo(id_rep)
{
//	if (validar()==0)
//		{
		$('#boton1').hide();
		var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'personal/4m_guardar.php?tipo=1',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	tablaf(id_rep); $('#boton1').show();}
				else
					{	alertify.alert(data.msg);	}
				//--------------
				} 
			});
//		}
}
//------------------------
function tablae(id)
{ $('#div4').load('personal/4l3_tabla.php?id='+id); }
//------------------------
function tablac(id)
{ $('#div3').load('personal/4l2_tabla.php?id='+id); }
//------------------------
function tablaf(id)
{ $('#div2').load('personal/4l_tabla.php?id='+id); }
</script>