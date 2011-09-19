/**
 * Formulario consolidadoPartido.js cuya funciones es:
 * presentar la votacion de los partidos a nivel departamental
 * municipal, zonal, comuna, puesto mesa
 * 
 * @author    Ing. Luis A. Nuñez
 * @copyright (c) 2011, by Ing. Luis A. Nuñez
 * @date      15. Septiembre 2011
 * @version   1.0.0
 */
 
 //--Objeto que contiene los parametros de la ultima peticion enviada al servidor
 var parametros = {};
 
 var cmpdivipol = Siprel.CmpDivipol.init(4);

 //--Combobox de comunas
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
	valueField     : 'idcomuna',
	store 		   : stcomunas
 });
 
 //--Grid de mesas
 var stmesas = new Ext.data.JsonStore({
	url           : URL_SIPREL+'controladores/Punto1/mesasDivipol.php',
	autoLoad      : false,
	root          : 'datos',
	totalProperty : 'total',
	fields : [ 
		{ name : 'codTx'},
		{ name : 'mesa'}
	]
 });
 
 var gdMesa = new Ext.grid.GridPanel({
	store   : stmesas,
	columns : [
		{header: "C&oacute;digo transmisi&oacute;n", width: 150, sortable: true, dataIndex: 'codTx'},
		{header: "Mesa", width: 70, sortable: true, dataIndex: 'mesa'}
	],
	tbar       : ['->'],
	plugins    : buscarPlugin(),
	stripeRows : true,
	sm         : new Ext.grid.RowSelectionModel( { singleSelect : true } ),
	height     : 350,
	width      : 270,
	frame      : true,
	title      : 'Listado de mesas'
 });
 
 //--Panel principal
 var pnPrincipal = new Ext.form.FormPanel({
	title        : 'Form Consolidado Corporacion',
	frame        : true,
	autoHeight   : true,
	width        : 550,
	layout       : 'table',
	defaults     : { bodyStyle : 'padding:10px'},
	layoutConfig : { columns   : 2 },
	autoScroll   : true,
	renderTo     : 'idconsolcorpr',
	items        : [
		{
			items : cmpdivipol
		},
		{
			layout : 'form',
			items : [ 
				cboxComunas,
				gdMesa
			]
		}
	],
	buttonAlign : 'center' ,
	buttons     : [
		{ text : 'Generar', handler : generar }
	],
	animCollapse  : true,
	collapsible   : true
 });
 
 //-/-Funciones auxiliares
 
 //--Cargar comunas
 function cargarComunas(nodo,even){
	stcomunas.load({
		params : {
			coddivipol : nodo.attributes.coddivipol,
			codnivel   : nodo.attributes.codnivel
		}
	});	
 }
 
 //--Cargar mesas
 function cargarMesas(nodo,even){
	gdMesa.getStore().load({
		params : {
			coddivipol : nodo.attributes.coddivipol,
			codnivel   : nodo.attributes.codnivel
		}
	});
 }
 
 //--Manejo del evento
 cmpdivipol.on('click',function(nodo,even){
	stcomunas.removeAll();
	stmesas.removeAll();
	cboxComunas.reset();
	switch(nodo.attributes.codnivel){
		case 2:
			cargarComunas(nodo,even);
		break;
		case 4:
			cargarMesas(nodo,even);
		break;
	}
 });
 
 //-/- Generacion de resultados
 
 //-- Grid de resultados
 function getGrid(){
	
	var store = new Ext.data.JsonStore({
		url      : URL_SIPREL+'controladores/Punto1/consolidadoCorporacion.php',
		autoLoad : false,
		fields   : [
		   {name : 'divipol'},
		   {name : 'codigo'},
		   {name : 'descripcion'},
		   {name : 'tipovoto'},
		   {name : 'votos'}
		]
	});
	  
	var grid = new Ext.grid.GridPanel({
		store      : store,
		columns    : [
			{ header : "Ubicaci&oacute;n", width : 115, sortable : true, dataIndex : 'divipol' },
			{ header : "C&oacute;digo",    width : 60,  sortable : true, dataIndex : 'codigo'  },
			{ header : "Nombre",           width : 250, sortable : true, dataIndex : 'descripcion' },
			{ header : "Tipo Voto",        width : 250, sortable : true, dataIndex : 'tipovoto' },
			{ header : "Votos",            width : 100, sortable : true, dataIndex : 'votos'   }
		],
		tbar         : genBarraExportar('Punto1/consolidadoCorporacion'), //Parametros de la peticion para exportar
		plugins      : buscarPlugin(),
		stripeRows   : true,
		autoScroll   : true,
		border       : true,
		frame        : true,
		animCollapse : true,
		collapsible  : true,
		collapsed    : true,
		height       : 350,
		width        : 550,
		title        : 'Listado Consolidado Corporaciones'
	});
	return grid;
 }
 
 //Enviar los datos en un objeto codificado en formato json
 function generar(fm,evn){
	var valido = true;
	parametros = {};
	
	if(!Ext.isEmpty(cmpdivipol.divipol)) {
		parametros.coddivipol  = cmpdivipol.divipol.attributes.coddivipol;
		parametros.codnivel    = cmpdivipol.divipol.attributes.codnivel;
		parametros.descripcion = cmpdivipol.divipol.attributes.descripcion;
	} else {
		valido = false;
	}
	
	if(gdMesa.getStore().getTotalCount() > 0 && gdMesa.getSelectionModel().hasSelection()) {
		var rec = gdMesa.getSelectionModel().getSelected();
		parametros.codTransmision = rec.get('codTx');
	}
	
	if(!Ext.isEmpty(cboxComunas.getValue())) {
		parametros.idcomuna = cboxComunas.getValue();
	}
	
	if(valido) {
		gdConsolidadoPartido.getStore().load({ 
			params : {
				datos : Ext.encode(parametros)
			}
		});
		gdConsolidadoPartido.expand(true);
		pnPrincipal.collapse(true);
		
	} else {
		mensaje('Advertencia','Debe seleccionar un elemento de la <br/> divisi&oacute;n pol&iacute;tica',Ext.Msg.OK,Ext.Msg.WARNING);
	}
	
 }
 
 var gdConsolidadoPartido = getGrid();
 gdConsolidadoPartido.render('idgridconsolcorpor');