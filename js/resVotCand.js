function validar(form)
{
	if (form.corporacion.value == '-') {
		alert("Seleccione una corporación");
		return false;
	} else if (form.departamento.value == '-') {
		alert("Seleccione un departamento");
		return false;
	} else if (form.municipio.value == '-') {
		alert("Seleccione un municipio");
		return false;
	} else if (form.corporacion.value==5) {
		if (form.municipio.value=="-") {
			alert("Seleccione un municipio");
			return false;
		} else if (form.comuna.value=="-") {
			alert("Seleccione una comuna");
			return false;
		}
	}
	
	var param = "?corporacion=" + form.corporacion.value;
	param += "&departamento=" + form.departamento.value;
	param += "&municipio=" + form.municipio.value;
	param += "&comuna=" + form.comuna.value;
	
	var ajax = nuevoAjax();
	ajax.open("GET", "contenido/tablaResVotCand.php" + param, true);
	ajax.onreadystatechange = function () {
            if(ajax.readyState == 1) {
                document.getElementById('tbResVotCand').innerHTML = "<img src='../images/loading42.gif'></img>";
            }
            if (ajax.readyState == 4) {
                    document.getElementById('tbResVotCand').innerHTML = ajax.responseText;
            }
	}
	ajax.send(null);
	return false;
}

function mostrarDepto(sel)
{
	document.formPrincipal.departamento.value = '-';
	var divDepto = document.getElementById('divseldepto');
	
	if (sel != '-') {
		if(divDepto.style.display == "none")
			divDepto.style.display = "block";
	} else {
		divDepto.style.display = "none";
	}
	
	ocultarIniciar('divselmunicipio', 'selmunicipio');
	ocultarIniciar('divselcomuna', 'selcomuna');
}

function cargarMunicipios(sel)
{
	if (sel != '-') {
		var corpo = document.formPrincipal.corporacion.value;
		
		var ajax = nuevoAjax();
		
		ajax.open("GET", "contenido/cargarMunicipios.php?opcion=" + sel + "&corporacion=" + corpo, true);
		ajax.onreadystatechange= function () {
			if (ajax.readyState == 4) {
				document.getElementById('selmunicipio').parentNode.innerHTML = ajax.responseText;
			}
		}
		ajax.send(null);
		
		document.formPrincipal.municipio.value = '-';
		if(document.getElementById('divselmunicipio').style.display == "none") {
			mostrar('divselmunicipio');
		}
		
	} else {
		ocultarIniciar('divselmunicipio', 'selmunicipio');
	}
	ocultarIniciar('divselcomuna', 'selcomuna');
}

function cargarComunas(sel) 
{
	if (sel != '-') {
		var divipol = document.formPrincipal.departamento.value + sel;
		
		var ajax = nuevoAjax();
		
		ajax.open("GET", "contenido/cargarComunas.php?divipol=" + divipol, true);
		ajax.onreadystatechange= function () {
			if (ajax.readyState == 4) {
				document.getElementById('selcomuna').parentNode.innerHTML = ajax.responseText;
			}
		}
		ajax.send(null);
		
		document.formPrincipal.comuna.value = '-';
		if(document.getElementById('divselcomuna').style.display == "none") {
			mostrar('divselcomuna');
		}
	} else {
		ocultarIniciar('divselcomuna', 'selcomuna');
	}
}

function cargarZonas(sel){}
function comunaCargaPuesto(sel){}