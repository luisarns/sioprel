/**
 * Formulario resumenCurulesPartido.js cuya funciones es:
 * Generar un listado de resumen de curules asignadas y votación por partidos
 *
 * @author    Ing. Luis A. Nuñez
 * @copyright (c) 2011, by Ing. Luis A. Nuñez
 * @date      22. Septiembre 2011
 * @version   1.0.0
 */
Ext.onReady(function () {
	
	//--Declaracion Componentes
	var cmpcorporacion = Siprel.CmpCorporacion.init();	
	var cmpdivipol = Siprel.CmpDivipol.init(2);
	var gridResumenCurules = getResumenCurules();
	
	
	var pnPrincipal = new Ext.Panel({
		title        : 'Resumen Curules Partido',
		autoHeight   : true,
		autoWidth    : true,
		layout       : 'table',
		defaults     : { bodyStyle : 'padding:10px' },
		layoutConfig : { columns   : 2 },
		autoScroll   : true,
		frame        : true,
		renderTo     : 'resCurlPartido',
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
			{ text : 'Generar', handler : generar }
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
			url      : URL_SIPREL+'controladores/resumenCurulesPartido.php',
			autoLoad : false,
			fields   : [
			   { name : 'partido' },
			   { name : 'votos', type : 'int' },
			   { name : 'curules', type : 'int' }
			]
		});
		
		var grid = new Ext.grid.GridPanel({
			store      : store,
			columns    : [
				{ header : "Partido", width : 280, sortable : true, dataIndex : 'partido' },
				{ header : "Votos",   width : 100, sortable : true, dataIndex : 'votos'   },
				{ header : "Curules", width : 100, sortable : true, dataIndex : 'curules' }
			],
			tbar         : genBarraExportar('resumenCurulesPartido'),
			plugins      : buscarPlugin(),
			stripeRows   : true,
			autoScroll   : true,
			border       : true,
			frame        : true,
			height       : 350,
			width        : 500,
			title        : 'Listado Curules Asignadas'
		});
		
		return grid;
	}
	
	function generar(){
		if(!Ext.isEmpty(cmpdivipol.divipol) && !Ext.isEmpty(cmpcorporacion.corporacion)) {
			var parametros = {};
			
			parametros.coddivipol     = cmpdivipol.divipol.attributes.coddivipol;
			parametros.codnivel       = cmpdivipol.divipol.attributes.codnivel;
			parametros.codcorporacion = cmpcorporacion.corporacion.data.codcorpo;
			parametros.corpnivel      = cmpcorporacion.corporacion.data.codnivel;
			
			gridResumenCurules.getStore().load({
				params : {
					datos : Ext.encode(parametros)	
				}
			});
		}
	}
	
});