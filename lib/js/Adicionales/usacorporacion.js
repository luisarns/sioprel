var cmpcorporacion = Siprel.CmpCorporacion.init();
var win = new Ext.Window({
	title    : 'Usa Corporaciones',
	closable : true,
	width    : 300,
	height   : 250,
	plain    : true,
	layout   : 'fit',
	items    : [cmpcorporacion],
	buttons  : [
		{
			text : 'Ver C&oacute;digo',
			handler : function(){
				Ext.Msg.alert('Codcorporaci&oacute;n', cmpcorporacion.codcorporacion);
			}
		}
	]
});
win.show();