function validar(form){
	
	if(form.departamento.value == '-'){
		alert("Seleccione un departamento");
		return false;
	}
	
	return true;
}

function cargarMunicipios(sel){
	
	if(sel != '-') {
		var ajax = nuevoAjax();
		var selectDestino = document.getElementById('selmunicipio');
		
		ajax.open("GET", "contenido/cargarMunicipios.php?opcion="+sel, true);
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
	
}

function cargarZonas(sel){}
function cargarComunas(sel) {}