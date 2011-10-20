function validar(form){
	return false;
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
	
}

function cargarMunicipios(sel){
	
	if(sel != '-') {
		
		//Desactivo el campo de la corporacion
		document.formPrincipal.corporacion.disabled = true;
		var corpo = document.formPrincipal.corporacion.value;
		
		var ajax = nuevoAjax();
		var selectDestino = document.getElementById('selmunicipio');
		
		ajax.open("GET", "contenido/cargarMunicipios.php?opcion="+sel+"&corporacion="+corpo, true);
		ajax.onreadystatechange= function () {
			if (ajax.readyState == 1) {
				selectDestino.length=0;
				var nuevaOpcion=document.createElement("option"); 
				nuevaOpcion.value=0; 
				nuevaOpcion.innerHTML="Cargando...";
				selectDestino.appendChild(nuevaOpcion); 
				selectDestino.disabled=true;
			}
			if (ajax.readyState == 4) {
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
		ajax.send(null);
		
		//muestra el div del municipio
		var selMuncp = document.formPrincipal.municipio.value = '-';
		var divMuncp = document.getElementById('divselmunicipio');
		if(divMuncp.style.display == "none") {
			mostrar('divselmunicipio');
		}
		
	} else {
		ocultar('divselmunicipio');
	}
	
}

function cargarComunas(sel) {
	
	document.formPrincipal.departamento.disabled = true;
	var divipol = document.formPrincipal.departamento.value + sel;
	
	var ajax = nuevoAjax();
	var selectDestino = document.getElementById('selcomuna');
	
	ajax.open("GET", "contenido/cargarComunas.php?divipol="+divipol, true);
	ajax.onreadystatechange= function () {
		if (ajax.readyState == 1) {
			selectDestino.length=0;
			var nuevaOpcion=document.createElement("option"); 
			nuevaOpcion.value=0; 
			nuevaOpcion.innerHTML="Cargando...";
			selectDestino.appendChild(nuevaOpcion); 
			selectDestino.disabled=true;
		}
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
	
}

function mostrar(id){
	var div	= document.getElementById(id);
	div.style.display = "block";
}

function ocultar(id){
	var div	= document.getElementById(id);
	div.style.display = "none";
}

function cargarZonas(sel){}
function comunaCargaPuesto(sel){}