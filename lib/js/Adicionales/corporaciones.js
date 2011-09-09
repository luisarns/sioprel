var jstore = new Ext.data.JsonStore({
	url    : URL_SIPREL+'/controladores/Corporacion.php',
	autoLoad : true,
	fields : [ { name : 'codcorpo', type : 'int' }, 'descorpo']
});

var gridCorporacion = new Ext.grid.GridPanel(
{
	renderTo : 'rdCorpo',
	store : jstore,
	columns : [
		{header: "C&oacute;digo", width: 60, sortable: true, dataIndex: 'codcorpo'},
		{id:'iddecorpo', header: "Descripci&oacute;n", width: 180, sortable: true, dataIndex: 'descorpo'}
	],
	stripeRows : true,
	autoExpandColumn : 'iddecorpo',
	height : 160,
	width  : 180,
	title  :'Corporaciones P&uacute;blicas'
});
gridCorporacion.render();