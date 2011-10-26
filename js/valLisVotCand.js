/**
* Valida que los datos del formulario se hayan ingresado correctamente 
* antes de enviar los datos al servidor.
*/
function validar(form){
	if(form.corporacion.value == '-') {
		alert("Seleccione una corporación");
		return false;
	}else if(form.departamento.value == '-') {
		alert("Seleccione un departamento");
		return false;
	}else if(form.corporacion.value==5) {
		if(form.municipio.value=="-") {
			alert("Seleccione un municipio");
			return false;
		}else if(form.comuna.value=="-") {
			alert("Seleccione una comuna");
			return false;
		}
	}
	return true;
}

function mostrarOcultarDepto(sel){

	var selDepto = document.formPrincipal.departamento.value = '-';
	var divDepto = document.getElementById('divseldepto');
	
	if(sel != '-' ) {
		if(divDepto.style.display == "none")
			mostrar('divseldepto');
	} else {
		ocultar('divseldepto');
	}

	ocultarIniciar('divselmunicipio','selmunicipio');
	ocultarIniciar('divselcomuna','selcomuna');
}

function cargarMunicipios(sel){
	if(sel != '-') {
		var corpo = document.formPrincipal.corporacion.value;
		
		var ajax = nuevoAjax();
		var selectDestino = document.getElementById('selmunicipio');
		
		ajax.open("GET", "contenido/cargarMunicipios.php?opcion="+sel+"&corporacion="+corpo, true);
		ajax.onreadystatechange= function () {
			if (ajax.readyState == 4) {
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
		ajax.send(null);
		
		var selMuncp = document.formPrincipal.municipio.value = '-';
		var divMuncp = document.getElementById('divselmunicipio');
		if(divMuncp.style.display == "none") {
			mostrar('divselmunicipio');
		}
		
	} else {
		ocultarIniciar('divselmunicipio','selmunicipio');
	}
	ocultarIniciar('divselcomuna','selcomuna');
}

function cargarComunas(sel) {
	if(sel != '-') {
		var divipol = document.formPrincipal.departamento.value + sel;
		
		var ajax = nuevoAjax();
		var selectDestino = document.getElementById('selcomuna');
		
		ajax.open("GET", "contenido/cargarComunas.php?opcion="+sel+"&divipol="+divipol, true);
		ajax.onreadystatechange= function () {
			if (ajax.readyState == 4) {
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
		ajax.send(null);
		
		var selMuncp = document.formPrincipal.comuna.value = '-';
		var divMuncp = document.getElementById('divselcomuna');
		if(divMuncp.style.display == "none") {
			mostrar('divselcomuna');
		}
	} else {
		ocultarIniciar('divselcomuna','selcomuna');
	}
}

function comunaCargaPuesto(idcomuna) {}
function cargarZonas(sel) {}