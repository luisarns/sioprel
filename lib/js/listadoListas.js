/**
 * Formulario listadoListas.js cuya funciones es:
 * Genera un listado de las listas con mayor votacion por municipio y departamento
 *
 * @author    Ing. Luis A. Nuñez
 * @copyright (c) 2011, by Ing. Luis A. Nuñez
 * @date      13. Septiembre 2011
 * @version 1.0.0
 */
Ext.onReady(function () {

	var cmpdivipol = Siprel.CmpDivipol.init(2);
	var listadoListas = getListadoListas();
	
	var pnPrincipal = new Ext.Panel({
		title        : 'Votaci&oacute;n de listas',
		autoHeight   : true,
		autoWidth    : true,
		layout       : 'table',
		defaults     : { bodyStyle : 'padding:10px' },
		layoutConfig : { columns   : 2 },
		autoScroll   : true,
		frame        : true,
		renderTo     : 'idlistlista',
		items : [
			{
				items : cmpdivipol
			},
			{
				items : listadoListas
			}
		]
	});
	
	cmpdivipol.on('click',function(nodo,even) {
		listadoListas.getStore().load({
			params : {
				coddivipol : nodo.attributes.coddivipol,
				codnivel   : nodo.attributes.codnivel
			}
		});
	});
	
	function getListadoListas() {
	
		var store = new Ext.data.JsonStore({
			url      : URL_SIPREL+'controladores/listadoListas.php',
			autoLoad : false,
			fields   : [
			   { name : 'partido'             },
			   { name : 'corporacion'         },
			   { name : 'nombres'             },
			   { name : 'votos', type : 'int' }
			]
		});
		  
		var grid = new Ext.grid.GridPanel({
			store      : store,
			columns    : [
				{ header : "Partido",     width : 150,  sortable : true, dataIndex : 'partido'     },
				{ header : "Corporacion", width : 110,  sortable : true, dataIndex : 'corporacion' },
				{ header : "Nombre",      width : 150,  sortable : true, dataIndex : 'nombres'     },
				{ header : "Votos",       width : 60,  sortable : true,  dataIndex : 'votos'       }
			],
			tbar         : genBarraExportar('listadoListas'),
			plugins      : buscarPlugin(),
			stripeRows   : true,
			autoScroll   : true,
			border       : true,
			frame        : true,
			height       : 350,
			width        : 500,
			title        : 'Listado Votaci&oacute;n Listas'
		});
		
		return grid;
	}
	
});