/**
 *Valida que la información del formulario este correcta y envia una peticion
 *ajax al servidor para generar el listado consolidado partido departamental
*/
function validar(form)
{
    if (form.corporacion.value == '-'){
        alert("Seleccione una corporacion");
        return false;
    } else if (form.departamento.value == '-') {
        alert("Seleccione un departamento");
        return false;
    } else if (form.municipio.value == '-' && form.corporacion.value !=1 &&  form.corporacion.value !=2) {
        alert('Seleccione un municipio');
        return false;
    } else if (form.corporacion.value == 5 && form.comuna.value == '-'){
        alert('Seleccione una comuna');
        return false;
    }
    
    var param = "?corporacion=" + form.corporacion.value;
    param += "&departamento=" + form.departamento.value;
    param += "&municipio=" + form.municipio.value;
    param += "&comuna=" + form.comuna.value;

    var ajax = nuevoAjax();
    ajax.open("GET", "contenido/tablaConParDepto.php" + param, true);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById('tbConParDepto').innerHTML = "<img src='../images/loading42.gif'></img>";
        }
        if (ajax.readyState == 4) {
            document.getElementById('tbConParDepto').innerHTML = ajax.responseText;
        }
    }
    ajax.send(null);

    return false;
}

function mostrarOcultarDepto(sel)
{
    document.formPrincipal.departamento.value = '-';
    ocultarIniciar('divselmunicipio', 'selmunicipio');
    ocultarIniciar('divselcomuna', 'selcomuna');
}


/**
 * Muestra los candidatos inscritos y elegidos de un partido
 * a hacer dobre click solo la fila de un partido
 */
function cargarDetalle(url){
    var ajax = nuevoAjax();
    ajax.open("GET", url, true);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById('tablaConParCanDepto').innerHTML = "<img src='../images/loading42.gif'></img>";
        }
        if (ajax.readyState == 4) {
            document.getElementById('tablaConParCanDepto').innerHTML = ajax.responseText;
        }
    }
    ajax.send(null);
}


function cargarMunicipios(sel)
{
    var corpo = document.formPrincipal.corporacion.value;
    
    if (sel != '-' && corpo != 1 && corpo != 2) {
        
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