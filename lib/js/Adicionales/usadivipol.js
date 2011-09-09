var cmpdivipol = Siprel.CmpDivipol.init();
var win = new Ext.Window({
	title    : 'Usa Divipol',
	closable : true,
	width    : 150,
	height   : 150,
	plain    : true,
	layout   : 'fit',
	items    : [cmpdivipol],
	buttons  : [
		{
			text : 'Ver Divipol',
			handler : function(){
				Ext.Msg.alert('Divipol', cmpdivipol.divipol.attributes.text);
			}
		}
	]
});
win.show();