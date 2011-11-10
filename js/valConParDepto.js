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
    }
    
    var param = "?coddivipol=" + form.departamento.value;
    param += "&corporacion=" + form.corporacion.value;

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

function mostrarOcultarDepto(sel){}
