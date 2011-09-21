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
	
	/*componente para seleccionar una corporacion*/
	var cmpcorporacion = Siprel.CmpCorporacion.init();
	cmpcorporacion.getStore().on('beforeload',function(st,op){
		op.params = {
			tipoeleccion : 1
		};
		return true;
	});
	
	var cmpdivipol = Siprel.CmpDivipol.init(2);
	var listadoListas = getListadoListas();
	
	//Combobox para mostrar las comunas cuando la corporacion sea JAL
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
		width          : 150,
		valueField     : 'idcomuna',
		store 		   : stcomunas
	});
	
	cmpcorporacion.on('rowclick',mostrarComuna);
	
	function mostrarComuna(grid,rowInd,e){
		if(grid.corporacion.data.comuna == 1){
			pnSuperior.setVisible(true);
		}else if(pnSuperior.isVisible()){
			pnSuperior.setVisible(false);
		}
	}
	
	cmpdivipol.on('click',function(nodo,even) {
		if(pnSuperior.isVisible()){
			stcomunas.load({
				params : {
					coddivipol : nodo.attributes.coddivipol,
					codnivel   : nodo.attributes.codnivel
				}
			});
		}
	});
	
	
	var pnSuperior = new Ext.Panel({
		autoHeight : true,
		autoWidth  : true,
		layout     : 'form',
		hidden     : true,
		items      : [cboxComunas]
	});
	
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
				items : [cmpcorporacion,cmpdivipol]
			},
			{
				items : [pnSuperior,listadoListas]
			}
		],
		buttonAlign : 'center',
		buttons : [
			{text : 'Generar', handler : generar}
		]
	});
	
	
	function generar(){
		if(!Ext.isEmpty(cmpdivipol.divipol) && !Ext.isEmpty(cmpcorporacion.corporacion)) {
			var parametros = {};
			
			parametros.coddivipol     = cmpdivipol.divipol.attributes.coddivipol;
			parametros.codnivel       = cmpdivipol.divipol.attributes.codnivel;
			parametros.codcorporacion = cmpcorporacion.corporacion.data.codcorpo;
			parametros.corpnivel      = cmpcorporacion.corporacion.data.codnivel;
			
			listadoListas.getStore().load({
				params : {
					datos : Ext.encode(parametros)			
				}
			});
		}
	}
	
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