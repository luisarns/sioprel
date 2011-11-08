function mostrar(id) 
{
	document.getElementById(id).style.display = "block";
}

function ocultar(id) 
{
	document.getElementById(id).style.display = "none";
}

function ocultarIniciar(divId,comboId) {
    if (document.getElementById(divId).style.display == "block") {
        document.getElementById(divId).style.display="none";
        if (document.getElementById(comboId)) {
            document.getElementById(comboId).value="-";
        }
    }
}

function nuevoAjax() 
{
	var xmlhttp=false;
	try
	{
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	} catch(e) {
		try {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		} catch(E) {
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp;
}