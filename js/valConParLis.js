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
		
	}else if(form.corporacion.value==5) {
		if(form.municipio.value=="-") {
			alert("Seleccione un municipio");
			return false;
		}else if(form.comuna.value=="-") {
			alert("Seleccione una comuna");
			return false;
		}
	}
	
	var param = "?corporacion="+form.corporacion.value;
	param += "&departamento="+form.departamento.value;
	param += "&municipio="+form.municipio.value;
	param += "&comuna="+form.comuna.value;
	param += "&partido="+form.partido.value;
	param += "&puesto="+form.puesto.value;
	param += "&mesa="+form.mesa.value;
	
	if(form.detallado.checked){
		param += "&detallado="+form.detallado.value;
	}
	if(document.getElementById('selcomuna').style.display=="block"){
		param += "&zona="+form.zona.value;
	}
	
	var ajax = nuevoAjax();
	ajax.open("GET", "contenido/tablaConParList.php"+param, true);
	ajax.onreadystatechange= function () {
		if (ajax.readyState == 4) {
			document.getElementById('tbConParList').innerHTML=ajax.responseText;
		}
	}
	ajax.send(null);
	return false;
}


/**
* Carga los municipios en funcion del departamento seleccionado
* Oculta todos los campos que dependen del municipio y 
* cambia la seleccion de estos campos por ninguna.
* @update 26-10-2011 (dd-mm-yyyy)
*/
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
	ocultarIniciar('divselzona','selzona');
	ocultarIniciar('divselcomuna','selcomuna');
	ocultarIniciar('divselpuesto','selpuesto');
	ocultarIniciar('divselmesa','selmesa');
}

/**
* Carga los zonas en funcion del municipio seleccionado
* Oculta todos los campos que dependen de la zona
* cambia la seleccion de estos campos por ninguna.
*/
function cargarZonas(sel) {
	
	if(sel != '-') {

		var divipol = document.formPrincipal.departamento.value + sel;
		
		var ajax = nuevoAjax();
		var divDestino = document.getElementById('divselzona');
		
		ajax.open("GET", "contenido/cargarZonas.php?divipol="+divipol, true);
		ajax.onreadystatechange= function () {
			if (ajax.readyState == 4) {
				divDestino.innerHTML=ajax.responseText;
			}
		}
		
		ajax.send(null);
		
		var divMuncp = document.getElementById('divselzona');
		if(divMuncp.style.display == "none") {
			mostrar('divselzona');
		}
		
	} else {
		ocultarIniciar('divselzona','selzona');
	}
	
	ocultarIniciar('divselpuesto','selpuesto');
	ocultarIniciar('divselmesa','selmesa');
	
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
	ocultarIniciar('divselpuesto','selpuesto');
	ocultarIniciar('divselmesa','selmesa');
	
}

function comunaCargaPuesto(idcomuna) {
	
	if(idcomuna != '-') {

		var divipol = document.formPrincipal.departamento.value + document.formPrincipal.municipio.value;
		
		var ajax = nuevoAjax();
		var selectDestino = document.getElementById('selpuesto');
		
		ajax.open("GET", "contenido/cargarPuestos.php?idcomuna="+idcomuna+"&divipol="+divipol, true);
		ajax.onreadystatechange= function () {

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
		
	} else {
		ocultarIniciar('divselpuesto','selpuesto');
	}
	ocultarIniciar('divselmesa','selmesa');
	
}

function zonaCargaPuesto(zona) {
	
	if(zona != '-') {
		var divipol = document.formPrincipal.departamento.value + document.formPrincipal.municipio.value;
		
		var ajax = nuevoAjax();
		var selectDestino = document.getElementById('selpuesto');
		
		ajax.open("GET","contenido/cargarPuestos.php?zona="+zona+"&divipol="+divipol, true);
		ajax.onreadystatechange= function () {
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
	}else {
		ocultarIniciar('divselpuesto','selpuesto');
	}
	ocultarIniciar('divselmesa','selmesa');
	
}

function cargarMesas(divipol){

	if(divipol != '-') {
		var corpo = document.formPrincipal.corporacion.value;
		
		var ajax = nuevoAjax();
		var selectDestino = document.getElementById('selmesa');
		
		ajax.open("GET", "contenido/cargarMesas.php?divipol="+divipol+"&corporacion="+corpo, true);
		ajax.onreadystatechange= function () {
			if (ajax.readyState == 4) {
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
		ajax.send(null);
		
		var divMuncp = document.getElementById('divselmesa');
		if(divMuncp.style.display == "none") {
			mostrar('divselmesa');
		}
		
	} else {
		ocultarIniciar('divselmesa','selmesa');
	}

}

/**
* Funcion:
* Oculta todos los campos que dependen del departamento y 
* cambia la seleccion de estos campos por ninguna y
* cambia el departamento seleccionado a -Ninguna-
*/
function mostrarOcultarDepto(sel){

	var selDepto = document.formPrincipal.departamento.value = '-';
	var divDepto = document.getElementById('combDepartamento');
	
	if(sel != '-' ) {
		if(divDepto.style.display == "none")
			mostrar('combDepartamento');
	} else {
		ocultar('combDepartamento');
	}

	ocultarIniciar('divselmunicipio','selmunicipio');
	ocultarIniciar('divselzona','selzona');
	ocultarIniciar('divselcomuna','selcomuna');
	ocultarIniciar('divselpuesto','selpuesto');
	ocultarIniciar('divselmesa','selmesa');
}
