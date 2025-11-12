//--------------------------------
function pesta(evt, nombre) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(nombre).style.display = "block";
  evt.currentTarget.className += " active";
}
//----------------
function validar_campo_entero(campo)
	{
		var texto = $('#'+campo).val();
		if(parseInt(texto) > 0)
			{	$('#'+campo).removeClass('is-invalid');	$('#'+campo).addClass('is-valid');	}
		else
			{	$('#'+campo).addClass('is-invalid');	$(campo).focus();	}
	}
//----------------
function validar_campo(campo)
	{
		var texto = $('#'+campo).val();
		if(texto.length > 0)
			{	$('#'+campo).removeClass('is-invalid');	$('#'+campo).addClass('is-valid');	}
		else
			{	$('#'+campo).addClass('is-invalid');	$(campo).focus();	}
	}
//----------------
function pregunta_anular(){ 
    if (confirm('¿Estas seguro de Anular la Informacion?'))
		{	return true;   	}	 
	else
		{	return false;   	}	 
} 

function pregunta(){ 
    if (confirm('¿Estas seguro de Continuar?'))
		{	return true;   	}	 
	else
		{	return false;   	}	 
} 

function pregunta_eliminar(){ 
    if (confirm('¿Estas seguro de Eliminar la Informacion?'))
		{	return true;   	}	 
	else
		{	return false;   	}	 
} 

function pregunta_guardar(){ 
    if (confirm('¿Estas seguro de Guardar la Informacion?'))
		{	return true;   	}	 
	else
		{	return false;   	}	 
} 

//Incluir esto en el imput onkeypress="return SoloNumero(event,this)"
function SoloNumero(e, field) {
    key = e.keyCode ? e.keyCode : e.which
    // backspace
    if (key == 8) return true
 
    // 0-9 
    if (key > 47 && key < 58) {
        if (field.value == "") return true
        regexp = /[0-9]{20}/
        return !(regexp.test(field.value))
    }
    // other key
    return false
}

//Incluir esto en el imput onkeypress="return SoloMoneda(event,this)"
function SoloMoneda(e, field) {
    key = e.keyCode ? e.keyCode : e.which
    // backspace
    if (key == 8) return true
 
    // 0-9 a partir del .decimal  
    if (field.value != "") {
        if ((field.value.indexOf(".")) > 0) {
            //si tiene un punto valida dos digitos en la parte decimal
            if (key > 47 && key < 58) {
                if (field.value == "") return true
                //regexp = /[0-9]{1,10}[\.][0-9]{1,3}$/
                regexp = /[0-9]{2}$/
                return !(regexp.test(field.value))
            }
        }
    }
    // 0-9 
    if (key > 47 && key < 58) {
        if (field.value == "") return true
        regexp = /[0-9]{9}/
        return !(regexp.test(field.value))
    }
    // .
    if (key == 46) {
        if (field.value == "") return false
        regexp = /^[0-9]+$/
        return regexp.test(field.value)
    }
    // other key
    return false
}
//----------------
function number_format(amount, decimals) {
    amount += ''; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto
    decimals = decimals || 0; // por si la variable no fue fue pasada
    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0) 
        return parseFloat(0).toFixed(decimals);
    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = '' + amount.toFixed(decimals);
    var amount_parts = amount.split('.'),
        regexp = /(\d+)(\d{3})/;
    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + '.' + '$2');
    return amount_parts.join(',');
	}

//---- MARCAR OBJETOS
function marcar(obj,x) { 
    if (obj.checked)
	{ 
		document.getElementById("fila"+x).style.backgroundColor = "lightblue"; 
	}
    else 
        document.getElementById("fila"+x).style.backgroundColor = "";
}
//-----------------------
function cambio_clave()
	{
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('clave.php');
	}
//----------------
function ciudades(valor)
{
	$.ajax({
        type: "POST",
        url: 'js/ciudades.php?valor='+valor,
        data: 'id='+valor,
        success: function(resp){
            $('#ciudad').html(resp);
        }
    });
}
//--------------
function copia_cont()
	{	
	document.getElementById('representante').value = document.getElementById('nombre').value;
	if (document.getElementById('direccion').value=='')	
		{	document.getElementById('direccion').value = "POR ACTUALIZAR";	}
	if (document.getElementById('celular').value=='')	
		{	document.getElementById('celular').value = "0000-0000000";	}
	if (document.getElementById('correo').value=='')	
		{	document.getElementById('correo').value = "ACTUALIZAR@GMAIL.COM";	}
	}
//----------------------- onkeypress="return soloLetras(event)"
function SoloLetra(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key).toLowerCase();
       letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
       especiales = "8-37-39-46";
       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }

        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }
//-----------------------
function puro_numero(campo) {
    var x = document.getElementById(campo).value;
    if (isNaN(x)) {
        document.getElementById(campo).value = "";
    }
}
//-----------------------
// onkeyup="saltar(event,'txt_control')"
function saltar(e,id)
{
	// Obtenemos la tecla pulsada
	(e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{
		document.getElementById(id).focus();
		}
}//-----------------------
// onkeyup="saltar(event,'txt_control')"
function saltar2(id)
{
	document.getElementById(id).focus();
}
//-----------------------
function cambia(valor)
	{
	valor = valor.replace(/ /g, '_');
	return valor;
	}
//---------------------
function copia2(valor,objeto)
	{	document.getElementById(objeto).value='01/01/'+valor; document.getElementById('ODESDE').value='01/'+valor;	}
//---------------------
function copia3(valor,objeto)
	{	document.getElementById(objeto).value='01/01/'+valor; document.getElementById('OINICIO').value='01/01/'+valor;	}
//---------------------
function copia(valor,objeto)
	{	document.getElementById(objeto).value='01/'+valor;	}
//---------------------
function copia_igual(valor,objeto)
	{	document.getElementById(objeto).value=valor;	}
//----------------
function copia_valor(valor,objeto)
	{	document.getElementById(objeto).value=  valor.replace('.', ''); ;
		document.getElementById(objeto).value=  document.getElementById(objeto).value.replace(',', '.');	}
//----------------
function evitar() {	return false; }
//---------------------
function oculta_btn(objeto)
	{	
	$('#'+objeto).hide();
	}