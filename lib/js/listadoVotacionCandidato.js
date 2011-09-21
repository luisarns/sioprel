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
	
	/*componente para seleccionar una corporacion*/
	var cmpcorporacion = Siprel.CmpCorporacion.init();
	
	//Para eliminar el ultimo registro de la lista JAL
	cmpcorporacion.getStore().on('load',function(st,rcs,ops){
		st.remove(rcs.pop());
	});
	
	
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
				items : [cmpcorporacion,cmpdivipol]
			},
			{
				items : listadoVotacionCand
			}
		]
	});
	
	cmpdivipol.on('click',function(nodo,even) {
		if(!Ext.isEmpty(cmpcorporacion.corporacion)){
			
			var parametros = {}; 
			parametros.coddivipol = nodo.attributes.coddivipol;
			parametros.codnivel   = nodo.attributes.codnivel;
			parametros.codcorpo   = cmpcorporacion.corporacion.data.codcorpo;
			parametros.nivcorpo   = cmpcorporacion.corporacion.data.codnivel;
			listadoVotacionCand.getStore().load({
				params : {
					datos     : Ext.encode(parametros)
				}
			});
			//Es la votacion por candidato
		}else {
			mensaje('Informaci&oacute;n','Seleccione una corporaci&oacute;n',Ext.Msg.OK,Ext.Msg.WARNING);
		}
	});
	
	function getListadoVotacionCandidato() {
	
		var store = new Ext.data.JsonStore({
			url      : URL_SIPREL+'controladores/listadoVotacionCandidato.php',
			autoLoad : false,
			fields   : [
			   { name : 'codigo'    },
			   { name : 'nombres'   },
			   { name : 'apellidos' },
			   { name : 'partido'   },
			   { name : 'votos', type : 'int' }
			]
		});
		
		var grid = new Ext.grid.GridPanel({
			store      : store,
			columns    : [
				{ header : "C&oacute;digo", width : 60,  sortable : true, dataIndex : 'codigo'    },
				{ header : "Nombres",       width : 150, sortable : true, dataIndex : 'nombres'   },
				{ header : "Apellidos",     width : 150, sortable : true, dataIndex : 'apellidos' },
				{ header : "Partido",       width : 170,  sortable : true, dataIndex : 'partido'   },
				{ header : "Votos",         width : 60,  sortable : true, dataIndex : 'votos'     }
			],
			tbar         : genBarraExportar('listadoVotacionCandidato'),
			plugins      : buscarPlugin(),
			stripeRows   : true,
			autoScroll   : true,
			border       : true,
			frame        : true,
			height       : 350,
			width        : 625,
			title        : 'Listado Votaci&oacute;n Candidatos'
		});
		
		return grid;
	}
	
});