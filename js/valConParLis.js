/**
* Valida que los datos del formulario se hayan ingresado correctamente 
* antes de enviar los datos al servidor.
*/
function validar(form)
{
    if (form.corporacion.value == '-') {
        alert("Seleccione una corporación");
        return false;
        
    } else if (form.departamento.value == '-') {
        alert("Seleccione un departamento");
        return false;
        
    } else if (form.corporacion.value == 5) {

        if (form.municipio.value == "-") {
                alert("Seleccione un municipio");
                return false;
        } else if (form.comuna.value == "-") {
                alert("Seleccione una comuna");
                return false;
        }
    }
    
    var datos = $(form).serialize();
    var param = "?" + datos;

    var ajax = nuevoAjax();
    ajax.open("GET", "contenido/tablaConParList.php" + param, true);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            document.getElementById('tbConParList').innerHTML = ajax.responseText;
        }
    }
    ajax.send(null);
    return false;
}



/**
* Funcion:
* Oculta todos los campos que dependen del departamento y 
* cambia la seleccion de estos campos por ninguna y
* cambia el departamento seleccionado a -Ninguna-
*/
function mostrarOcultarDepto(sel) 
{  
    document.formPrincipal.departamento.value = '-';

    if (sel != '-' ) {
        if (document.getElementById('tdDepto').style.display == 'none') {
                mostrar('tdDepto');
        }
    } else {
            ocultar('tdDepto');
    }

    ocultarIniciar('etfMunicipio','selmunicipio');
    ocultarIniciar('etfZonaComuna', 'selcomuna');
    ocultarIniciar('etfZonaComuna', 'selzona');
    ocultarIniciar('etfPuesto', 'selpuesto');
    ocultarIniciar('etfMesa', 'selmesa');
}


/**
* Carga los municipios en funcion del departamento seleccionado
* Oculta todos los campos que dependen del municipio y 
* cambia la seleccion de estos campos por ninguna.
* @update 26-10-2011 (dd-mm-yyyy)
*/
function cargarMunicipios(sel) 
{	
    if (sel != '-') {

        var corpo = document.formPrincipal.corporacion.value;
        var ajax = nuevoAjax();
        var selectDestino = document.getElementById('etfMunicipio');

        ajax.open("GET", "contenido/cargarMunicipios.php?opcion=" + sel + "&corporacion=" + corpo, true);
        ajax.onreadystatechange = function () {
                if (ajax.readyState == 4) {
                        selectDestino.innerHTML = ajax.responseText;
                }
        }
        ajax.send(null);

        if (document.getElementById('etfMunicipio').style.display == "none") {
                mostrar('etfMunicipio');
        }

    } else {
        ocultarIniciar('etfMunicipio', 'selmunicipio');
    }

    ocultarIniciar('etfZonaComuna', 'selcomuna');
    ocultarIniciar('etfZonaComuna', 'selzona');
    ocultarIniciar('etfPuesto', 'selpuesto');
    ocultarIniciar('etfMesa', 'selmesa');
}


/**
* Carga los zonas en funcion del municipio seleccionado
* Oculta todos los campos que dependen de la zona
* cambia la seleccion de estos campos por ninguna.
*/
function cargarZonas(sel) 
{
    if (sel != '-') {
        var divipol = document.formPrincipal.departamento.value + sel;

        var ajax = nuevoAjax();
        ajax.open("GET", "contenido/cargarZonas.php?divipol=" + divipol, true);
        ajax.onreadystatechange= function () {
                if (ajax.readyState == 4) {
                        document.getElementById('etfZonaComuna').innerHTML = ajax.responseText;
                }
        }
        ajax.send(null);

        if (document.getElementById('etfZonaComuna').style.display == "none") {
                mostrar('etfZonaComuna');
        }
    } else {
        ocultarIniciar('etfZonaComuna', 'selzona');
    }
    ocultarIniciar('etfPuesto', 'selpuesto');
    ocultarIniciar('etfMesa', 'selmesa');
}


function cargarComunas(sel) 
{
    if (sel != '-') {
        var divipol = document.formPrincipal.departamento.value + sel;
        var ajax = nuevoAjax();

        ajax.open("GET", "contenido/cargarComunas.php?opcion=" + sel + "&divipol=" + divipol, true);
        ajax.onreadystatechange= function () {
                if (ajax.readyState == 4) {
                        document.getElementById('etfZonaComuna').innerHTML = ajax.responseText;
                }
        }
        ajax.send(null);

        if (document.getElementById('etfZonaComuna').style.display == "none") {
                mostrar('etfZonaComuna');
        }
    } else {
        ocultarIniciar('etfZonaComuna', 'selcomuna');
    }
    ocultarIniciar('etfPuesto', 'selpuesto');
    ocultarIniciar('etfMesa', 'selmesa');
}


function comunaCargaPuesto(idcomuna) 
{
    if (idcomuna != '-') {

        var divipol = document.formPrincipal.departamento.value + document.formPrincipal.municipio.value;
        var ajax = nuevoAjax();

        ajax.open("GET", "contenido/cargarPuestos.php?idcomuna=" + idcomuna + "&divipol=" + divipol, true);
        ajax.onreadystatechange= function() {
            if (ajax.readyState == 4) {
                document.getElementById('etfPuesto').innerHTML = ajax.responseText;
            }
        }
        ajax.send(null);

        if(document.getElementById('etfPuesto').style.display == "none") {
            mostrar('etfPuesto');
        }

    } else {
        ocultarIniciar('etfPuesto', 'selpuesto');
    }
    ocultarIniciar('etfMesa', 'selmesa');
}

function zonaCargaPuesto(zona) 
{	
	if(zona != '-') {
		var divipol = document.formPrincipal.departamento.value + document.formPrincipal.municipio.value;
		
		var ajax = nuevoAjax();
		ajax.open("GET","contenido/cargarPuestos.php?zona=" + zona + "&divipol=" + divipol, true);
		ajax.onreadystatechange= function () {
			if (ajax.readyState == 4) {
				document.getElementById('etfPuesto').innerHTML = ajax.responseText;
			}
		}
		ajax.send(null);
		
//		document.formPrincipal.puesto.value = '-';
		if(document.getElementById('etfPuesto').style.display == "none") {
			mostrar('etfPuesto');
		}
	}else {
		ocultarIniciar('etfPuesto', 'selpuesto');
	}
	ocultarIniciar('etfMesa', 'selmesa');
}

function cargarMesas(divipol)
{
    if(divipol != '-') {
        var corpo = document.formPrincipal.corporacion.value;

        var ajax = nuevoAjax();

        ajax.open("GET", "contenido/cargarMesas.php?divipol=" + divipol + "&corporacion=" + corpo, true);
        ajax.onreadystatechange= function () {
            if (ajax.readyState == 4) {
                    document.getElementById('etfMesa').innerHTML = ajax.responseText;
            }
        }
        ajax.send(null);

        if (document.getElementById('etfMesa').style.display == "none") {
            mostrar('etfMesa');
        }

    } else {
        ocultarIniciar('etfMesa', 'selmesa');
    }
}

