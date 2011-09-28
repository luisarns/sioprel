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
	var cmpdivipol = Siprel.CmpDivipol.init(2);
	var listadoVotacionCand = getListadoVotacionCandidato();
	
	
	///////////////////Inicio Comuna/////////////////////////
	var stcomunas = new Ext.data.JsonStore({
		url    : URL_SIPREL+'controladores/comunasDivipol.php',
		autoLoad : false,
		fields : [ 
			{ name : 'idcomuna',  type : 'int' },
			{ name : 'codcomuna', type : 'int' },
			{ name : 'descripcion' }
		]
	});

	var cboxComunas = new Ext.form.ComboBox({
		fieldLabel     : 'Comuna',
		name           : 'idcomuna',
		editable       : true,
		forceSelection : true,
		mode           : 'local',
		triggerAction  : 'all',	
		typeAhead      : true,
		displayField   : 'descripcion',
		width          : 200,
		valueField     : 'idcomuna',
		allowBlank     : false,
		blankText      : 'Debe seleccionar una comuna',
		emptyText      : 'Seleccione una comuna',
		listWidth      : 200,
		store 		   : stcomunas
	});

	var pnSuperior = new Ext.form.FieldSet({
		autoHeight : true,
		width      : 480,
		hidden     : true,
		border     : false,
		items      : cboxComunas
	});
	///////////////////FIN/////////////////////////
	
	
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
				items : [pnSuperior,listadoVotacionCand]
			}
		],
		buttonAlign : 'center',
		buttons : [
			{text: 'Generar', handler : cargar}
		]
	});
	
	
	function cargar(fm,even) {
		if(!Ext.isEmpty(cmpcorporacion.corporacion) && !Ext.isEmpty(cmpdivipol.divipol)){
			
			var parametros = {}; 
			parametros.coddivipol = cmpdivipol.divipol.attributes.coddivipol;
			parametros.codnivel   = cmpdivipol.divipol.attributes.codnivel;
			parametros.codcorpo   = cmpcorporacion.corporacion.data.codcorpo;
			parametros.nivcorpo   = cmpcorporacion.corporacion.data.codnivel;

			if(cmpcorporacion.corporacion.data.comuna == 1){
				
				if(!Ext.isEmpty(cboxComunas.getValue())){
					parametros.idcomuna = cboxComunas.getValue();
					
					listadoVotacionCand.getStore().load({
						params : {
							datos     : Ext.encode(parametros)
						}
					});
				}else{
					mensaje('Informaci&oacute;n','Seleccione una comuna',Ext.Msg.OK,Ext.Msg.WARNING);
				}
				
			} else {
				listadoVotacionCand.getStore().load({
					params : {
						datos     : Ext.encode(parametros)
					}
				});
			}
			
		} else {
			mensaje('Informaci&oacute;n','Seleccione una corporacion y una divipol',Ext.Msg.OK,Ext.Msg.WARNING);
		}
		
	}
	
	cmpcorporacion.on('rowclick',mostrarComuna);
	
	//Manejo de los eventos
	cmpdivipol.on('click',function(nodo,even) {
		if(pnSuperior.isVisible()){
			limpiarComuna();
			stcomunas.load({
				params : {
					coddivipol : nodo.attributes.coddivipol,
					codnivel   : nodo.attributes.codnivel
				}
			});
		}
	});
	
	function mostrarComuna(grid,rowInd,e){
		if(grid.corporacion.data.comuna == 1){
			pnSuperior.setVisible(true)
		}else if(pnSuperior.isVisible()){
			limpiarComuna();
			pnSuperior.setVisible(false);
		}
	}
	
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
				{ header : "Partido",       width : 170, sortable : true, dataIndex : 'partido'   },
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
	
	function limpiarComuna(){
		stcomunas.removeAll();
		cboxComunas.reset();
	}
	
});