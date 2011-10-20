function validar(form){
	
	if(form.departamento.value == '-'){
		alert("Seleccione un departamento");
		return false;
	}
	
	return true;
}

//Funcion encargada de la carga dinamico de los municipios despues que se ha seleccionado un departamento
function cargarMunicipios(sel){
	
	if(sel != '-') {

		var ajax = nuevoAjax();
		var selectDestino = document.getElementById('selmunicipio');
		
		ajax.open("GET", "contenido/cargarMunicipios.php?opcion="+sel, true);
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




function mostrar(id){
	var div	= document.getElementById(id);
	div.style.display = "block";
}

function ocultar(id){
	var div	= document.getElementById(id);
	div.style.display = "none";
}

function cargarZonas(sel){}
function cargarComunas(sel) {}