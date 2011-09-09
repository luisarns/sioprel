//cargar una pagina o script en un div
function actualizarPanel(div, accion) {
    Ext.get(div).load({ url : accion, scripts : true , text : "Cargando ..."});
}
function mensaje(titulo,mensaje,boton,icono) {
	Ext.Msg.show({
		title:titulo,
		msg: mensaje,
		buttons: boton,
		animEl: 'central',
		icon: icono
	});
}
var URL_SIPREL = "http://127.0.0.1:4001/";

