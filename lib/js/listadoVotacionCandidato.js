/**
 * Formulario listadoVotacionCandidato.js cuya funciones es:
 * Genera un listado de la votacion de candidatos y corporaciones
 * por Municipios y Departamentos
 *
 * @author    Ing. Luis A. Nuñez
 * @copyright (c) 2011, by Ing. Luis A. Nuñez
 * @date      13. Septiembre 2011
 * @version 1.0.0
 */
Ext.onReady(function () {

	var cmpdivipol = Siprel.CmpDivipol.init(2);
	var listadoVotacionCand = getListadoVotacionCandidato();
	
	var pnPrincipal = new Ext.Panel({
		title        : 'Votaci&oacute;n Candidatos',
		autoHeight   : true,
		autoWidth    : true,
		layout       : 'table',
		defaults     : { bodyStyle : 'padding:10px' },
		layoutConfig : { columns   : 2 },
		autoScroll   : true,
		frame        : true,
		renderTo     : 'idlistvotacand',
		items : [
			{
				items : cmpdivipol
			},
			{
				items : listadoVotacionCand
			}
		]
	});
	
	cmpdivipol.on('click',function(nodo,even) {
		listadoVotacionCand.getStore().load({
			params : {
				coddivipol : nodo.attributes.coddivipol,
				codnivel   : nodo.attributes.codnivel
			}
		});
	});
	
	function getListadoVotacionCandidato() {
	
		var store = new Ext.data.JsonStore({
			url      : URL_SIPREL+'controladores/listadoVotacionCandidato.php',
			autoLoad : false,
			fields   : [
			   { name : 'corporacion'         },
			   { name : 'codcandidato'        },
			   { name : 'nombres'             },
			   { name : 'apellidos'           },
			   { name : 'votos', type : 'int' }
			]
		});
		  
		var grid = new Ext.grid.GridPanel({
			store      : store,
			columns    : [
				{ header : "Corporacion",   width : 90,  sortable : true, dataIndex : 'corporacion'  },
				{ header : "C&oacute;digo", width : 50,  sortable : true, dataIndex : 'codcandidato' },
				{ header : "Nombres",       width : 150,  sortable : true, dataIndex : 'nombres'      },
				{ header : "Apellidos",     width : 150,  sortable : true, dataIndex : 'apellidos'    },				
				{ header : "Votos",       width : 60,  sortable : true,  dataIndex : 'votos'       }
			],
			tbar         : genBarraExportar('listadoVotacionCandidato'),
			plugins      : buscarPlugin(),
			stripeRows   : true,
			autoScroll   : true,
			border       : true,
			frame        : true,
			height       : 350,
			width        : 525,
			title        : 'Listado Votaci&oacute;n Candidatos'
		});
		
		return grid;
	}
	
});