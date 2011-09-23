/**
 * Formulario elegidosAsignacionCurules.js cuya funciones es:
 * Generar un listado de elegidos y asignación de curules por cociente y residuo
 *
 * @author    Ing. Luis A. Nuñez
 * @copyright (c) 2011, by Ing. Luis A. Nuñez
 * @date      23. Septiembre 2011
 * @version   1.0.0
 */
Ext.onReady(function () {
	
	//--Declaracion Componentes
	var cmpcorporacion = Siprel.CmpCorporacion.init();	
	var cmpdivipol = Siprel.CmpDivipol.init(2);
	var gridResumenCurules = getResumenCurules();
	
	
	var pnPrincipal = new Ext.Panel({
		title        : 'Elegidos Asignaci&oacute;n Curules',
		autoHeight   : true,
		autoWidth    : true,
		layout       : 'table',
		defaults     : { bodyStyle : 'padding:10px' },
		layoutConfig : { columns   : 2 },
		autoScroll   : true,
		frame        : true,
		renderTo     : 'elegasigcurules',
		items : [
			{
				items : [cmpcorporacion,cmpdivipol]
			},
			{
				items : [gridResumenCurules]
			}
		],
		buttonAlign : 'center',
		buttons : [
			{text : 'Generar'}
		]
	});
	
	
	//--Manejo de eventos
	cmpcorporacion.getStore().on('beforeload',function(st,op){
		op.params = { tipoeleccion : 1 };
		return true;
	});
	
	
	//--Funciones
	function getResumenCurules() {
	
		var store = new Ext.data.JsonStore({
			url      : URL_SIPREL+'controladores/elegidosAsignacionCurules.php',
			autoLoad : false,
			fields   : [
			   { name : 'partido' },
			   { name : 'votos', type : 'int' },
			   { name : 'curules'}
			]
		});
		
		var grid = new Ext.grid.GridPanel({
			store      : store,
			columns    : [
				{ header : "Partido", width : 280, sortable : true, dataIndex : 'partido' },
				{ header : "Votos",   width : 100, sortable : true, dataIndex : 'votos'   },
				{ header : "Curules", width : 100, sortable : true, dataIndex : 'curules' }
			],
			tbar         : genBarraExportar('elegidosAsignacionCurules'),
			plugins      : buscarPlugin(),
			stripeRows   : true,
			autoScroll   : true,
			border       : true,
			frame        : true,
			height       : 350,
			width        : 500,
			title        : 'Listado Elegidos Asignaci&oacute;n Curules'
		});
		
		return grid;
	}
	
	
	
});