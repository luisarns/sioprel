function validar(form)
{
	if (form.departamento.value == '-') {
		alert("Seleccione un departamento");
		return false;
	}
	
	var param = "?departamento=" + form.departamento.value;
	param += "&municipio=" + form.municipio.value;
	
	var ajax = nuevoAjax();
	ajax.open("GET", "contenido/tablaResVotPar.php" + param, true);
	ajax.onreadystatechange= function () {
            if(ajax.readyState == 1) {
                document.getElementById('tbResVotPar').innerHTML = "<img src='../images/loading42.gif'></img>";
            }
            if (ajax.readyState == 4) {
                document.getElementById('tbResVotPar').innerHTML = ajax.responseText;
            }
	}
	ajax.send(null);
	
	return false;
}

function cargarMunicipios(sel)
{
	if (sel != '-') {
		var ajax = nuevoAjax();
		
		ajax.open("GET", "contenido/cargarMunicipios.php?opcion=" + sel, true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 4) {
				document.getElementById('selmunicipio').parentNode.innerHTML = ajax.responseText;
			}
		}
		ajax.send(null);
		
		document.formPrincipal.municipio.value = '-';
		if (document.getElementById('divselmunicipio').style.display == "none") {
			mostrar('divselmunicipio');
		}
		
	} else {
		ocultarIniciar('divselmunicipio', 'selmunicipio');
	}
}

function cargarZonas(sel){}
function cargarComunas(sel) {}