/**
 * Formulario listadoListas.js cuya funciones es:
 * Genera un listado de las listas con mayor votacion por municipio y departamento
 *
 * @author    Ing. Luis A. Nu�ez
 * @copyright (c) 2011, by Ing. Luis A. Nu�ez
 * @date      22. Septiembre 2011
 * @version   1.0.0
 */
Ext.onReady(function () {
	
	//--Componentes
	var cmpcorporacion = Siprel.CmpCorporacion.init();
	var cmpdivipol = Siprel.CmpDivipol.init(2);
	var listadoListas = getListadoListas();
	
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
		listWidth      : 200,
		typeAhead      : true,
		displayField   : 'descripcion',
		width          : 200,
		valueField     : 'idcomuna',
		store 		   : stcomunas
	});
	
	var pnSuperior = new Ext.Panel({
		autoHeight : true,
		width      : 500,
		layout     : 'form',
		hidden     : true,
		items      : cboxComunas
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
	
	//--Manejo de eventos
	cmpcorporacion.getStore().on('beforeload',function(st,op){
		op.params = {
			tipoeleccion : 1
		};
		return true;
	});
	
	cmpcorporacion.on('rowclick',mostrarComuna);
	
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
	
	//--Funciones
	function mostrarComuna(grid,rowInd,e){
		if(grid.corporacion.data.comuna == 1){
			pnSuperior.setVisible(true);
		}else if(pnSuperior.isVisible()){
			limpiarComuna();
			pnSuperior.setVisible(false);
		}
	}
	
	function getListadoListas() {
	
		var store = new Ext.data.JsonStore({
			url      : URL_SIPREL+'controladores/listadoListas.php',
			autoLoad : false,
			fields   : [
			   { name : 'lista' },
			   { name : 'votos', type : 'int' }
			]
		});
		
		var grid = new Ext.grid.GridPanel({
			store      : store,
			columns    : [
				{ header : "Lista", width : 350, sortable : true, dataIndex : 'lista'    },
				{ header : "Votos",   width : 100, sortable : true, dataIndex : 'votos'  }
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
	
	function generar(){
		if(!Ext.isEmpty(cmpdivipol.divipol) && !Ext.isEmpty(cmpcorporacion.corporacion)) {
			var parametros = {};
			
			parametros.coddivipol     = cmpdivipol.divipol.attributes.coddivipol;
			parametros.codnivel       = cmpdivipol.divipol.attributes.codnivel;
			parametros.codcorporacion = cmpcorporacion.corporacion.data.codcorpo;
			parametros.corpnivel      = cmpcorporacion.corporacion.data.codnivel;
			
			if(pnSuperior.isVisible() && !Ext.isEmpty(cboxComunas.getRawValue()) && !Ext.isEmpty(cboxComunas.getValue())){
				parametros.idcomuna  = cboxComunas.getValue();
			}
			
			listadoListas.getStore().load({
				params : {
					datos : Ext.encode(parametros)			
				}
			});
		}
	}
	
    function pctChange(val){
        if(val > 0){
            return '<span style="color:green;">' + val + '%</span>';
        }else if(val < 0){
            return '<span style="color:red;">' + val + '%</span>';
        }
        return val;
    }
	
	function limpiarComuna(){
		stcomunas.removeAll();
		cboxComunas.reset();
	}
	
});