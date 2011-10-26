function mostrar(id) {
	var div	= document.getElementById(id);
	div.style.display = "block";
}

function ocultar(id) {
	var div	= document.getElementById(id);
	div.style.display = "none";
}

function ocultarIniciar(divId,comboId) {
	if(document.getElementById(divId).style.display=="block") {
		document.getElementById(divId).style.display="none";
		document.getElementById(comboId).value="-";
	}
}