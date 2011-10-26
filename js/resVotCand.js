function validar(form){
	
	if(form.corporacion.value == '-'){
		alert("Seleccione una corporación");
		return false;
	}else if(form.departamento.value == '-'){
		alert("Seleccione un departamento");
		return false;
	}else if (form.municipio.value == '-') {
		alert("Seleccione un municipio");
		return false;
	}else if (document.getElementById('divselcomuna').style.display != 'none' && form.comuna.value == '-') {
		alert("Seleccione una comuna");
		return false
	}
	
	document.formPrincipal.corporacion.disabled = false;
	document.formPrincipal.departamento.disabled = false;
	
	return true;
}

function mostrarDepto(sel){

	var selDepto = document.formPrincipal.departamento.value = '-';
	var divDepto = document.getElementById('divseldepto');
	
	if(sel != '-') {
		if(divDepto.style.display == "none")
			divDepto.style.display = "block";
	} else {
		divDepto.style.display = "none";
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
		
		ajax.open("GET", "contenido/cargarComunas.php?divipol="+divipol, true);
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

function mostrar(id){
	var div	= document.getElementById(id);
	div.style.display = "block";
}

function ocultar(id){
	var div	= document.getElementById(id);
	div.style.display = "none";
}


function ocultarIniciar(divId,comboId) {
	if(document.getElementById(divId).style.display=="block") {
		document.getElementById(divId).style.display="none";
		document.getElementById(comboId).value="-";
	}
}

function cargarZonas(sel){}
function comunaCargaPuesto(sel){}