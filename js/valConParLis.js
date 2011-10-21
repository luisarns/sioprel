/**
* Valida que los datos del formulario se hayan ingresado correctamente 
* antes de enviar los datos al servidor.
*/
function validar(form){

	if(form.corporacion.value == '-'){
		alert("Seleccione una corporación");
		return false;
	}else if(form.departamento.value == '-'){
		alert("Seleccione un departamento");
		return false;
		
	}else if (document.getElementById('divselcomuna').style.display != 'none' && form.comuna.value == '-') {
		alert("Seleccione una comuna");
		return false
	}
	
	form.corporacion.disabled = false;
	form.departamento.disabled = false;
	form.municipio.disabled = false;
	
	form.zona.disabled = false;
	form.comuna.disabled = false;
	
	return true;
}


/*
* Carga los municipios en funcion del departamento seleccionado
*/
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


function cargarZonas(sel) {
	
	document.formPrincipal.departamento.disabled = true;
	var divipol = document.formPrincipal.departamento.value + sel;
	
	var ajax = nuevoAjax();
	var selectDestino = document.getElementById('divselzona');
	
	ajax.open("GET", "contenido/cargarZonas.php?divipol="+divipol, true);
	ajax.onreadystatechange= function () {
		if (ajax.readyState == 4) {
			selectDestino.innerHTML=ajax.responseText;
		}
	}
	ajax.send(null);
	
	var divMuncp = document.getElementById('divselzona');
	if(divMuncp.style.display == "none") {
		mostrar('divselzona');
	}
	
}


function cargarComunas(sel) {
	
	document.formPrincipal.departamento.disabled = true;
	var divipol = document.formPrincipal.departamento.value + sel;
	
	var ajax = nuevoAjax();
	var selectDestino = document.getElementById('selcomuna');
	
	ajax.open("GET", "contenido/cargarComunas.php?opcion="+sel+"&divipol="+divipol, true);
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


function comunaCargaPuesto(idcomuna) {
	document.formPrincipal.municipio.disabled = true;
	var divipol = document.formPrincipal.departamento.value + document.formPrincipal.municipio.value;
	
	var ajax = nuevoAjax();
	var selectDestino = document.getElementById('selpuesto');
	
	ajax.open("GET", "contenido/cargarPuestos.php?idcomuna="+idcomuna+"&divipol="+divipol, true);
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
	
	var selMuncp = document.formPrincipal.puesto.value = '-';
	var divMuncp = document.getElementById('divselpuesto');
	if(divMuncp.style.display == "none") {
		mostrar('divselpuesto');
	}
	
}


function zonaCargaPuesto(zona) {
	
	document.formPrincipal.municipio.disabled = true;
	var divipol = document.formPrincipal.departamento.value + document.formPrincipal.municipio.value;
	
	var ajax = nuevoAjax();
	var selectDestino = document.getElementById('selpuesto');
	
	ajax.open("GET", "contenido/cargarPuestos.php?zona="+zona+"&divipol="+divipol, true);
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
	
	var selMuncp = document.formPrincipal.puesto.value = '-';
	var divMuncp = document.getElementById('divselpuesto');
	if(divMuncp.style.display == "none") {
		mostrar('divselpuesto');
	}
	
}

function cargarMesas(divipol){
	
	//Carga las mesas dada la divipol 
	document.formPrincipal.zona.disabled = true;
	var corpo = document.formPrincipal.corporacion.value;
	
	var ajax = nuevoAjax();
	var selectDestino = document.getElementById('selmesa');
	
	ajax.open("GET", "contenido/cargarMesas.php?divipol="+divipol+"&corporacion="+corpo, true);
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
	
	var divMuncp = document.getElementById('divselmesa');
	if(divMuncp.style.display == "none") {
		mostrar('divselmesa');
	}
	
}

function mostrarOcultarDepto(sel){

	var selDepto = document.formPrincipal.departamento.value = '-';
	var divDepto = document.getElementById('combDepartamento');
	
	if(sel != '-' ) {
		if(divDepto.style.display == "none")
			mostrar('combDepartamento');
	} else {
		ocultar('combDepartamento');
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
