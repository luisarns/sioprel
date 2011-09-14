/**
 * Formulario listadoElegidos.js cuya funciones es:
 * Genera un listado de todas los candidatos elegidos para todas las corporaciones
 * discrimados por partidos, genero, departamento, municipio y comuna
 *
 * @author    Ing. Luis A. Nuñez
 * @copyright (c) 2011, by Ing. Luis A. Nuñez
 * @date      13. Septiembre 2011
 * @version 1.0.0
 */
 Ext.onReady(function (){
 
 
 var cmpdivipol = Siprel.CmpDivipol.init(2);

 //Inicio partidos
 var jspartidos = new Ext.data.JsonStore({
	url    : URL_SIPREL+'controladores/Partidos.php',
	autoLoad : true,
	fields : [ { name : 'id', type : 'int' }, {name : 'nombre'}]
 });
 var cboxPartido = new Ext.form.ComboBox({
	fieldLabel     : 'Partido',
	editable       : true,
	name           : 'partido',
	forceSelection : true,
	triggerAction  : 'all',
	typeAhead      : true,
	displayField   : 'nombre',
	valueField     : 'id',
	store 		   : jspartidos
 });
 //Fin partidos
 
 
 //Inicio genero
 var cboxGenero = new Ext.form.ComboBox({
	fieldLabel     : 'G&eacute;nero',
	editable       : true,
	hidden         : false,
	name           : 'genero',
	forceSelection : true,
	triggerAction  : 'all',
	typeAhead      : true,
	store 		   : [['F','FEMENINO'],['M','MASCULINO']]
 });
 //Fin genero
 
 
 //Inicio comunas
 var stcomuna = new Ext.data.JsonStore({
	url      : URL_SIPREL+'controladores/comunasDivipol.php',
	autoLoad : false,
	fields   : [ { name : 'idcomuna', type : 'int' }, { name : 'descripcion' } ]
 });

 var gridComuna = new Ext.grid.GridPanel({
	store   : stcomuna,
	columns : [
		{header: "Cod. Comuna", width: 80, sortable: true, dataIndex: 'idcomuna'},
		{header: "Comuna", width: 200, sortable: true, dataIndex: 'descripcion'}
	],
	stripeRows : true,
	sm      : new Ext.grid.RowSelectionModel({ singleSelect : true } ),	
	height  : 300,
	width   : 300,
	tbar    : ['->'],
	plugins : buscarPlugin(),
	frame   : true,
	title   :'Comunas',
	mesa    : null
 });
 //Fin comunas
  
  
 //Contenedor principal
 var pnPrincipal = new Ext.form.FormPanel({
	title        : 'Forma Consulta Elegidos',
	autoHeight   : true,
	width        : 600,
	layout       : 'table',
	defaults     : { bodyStyle : 'padding:10px'},
	layoutConfig : { columns   : 2 },
	autoScroll   : true,
	frame        : true,
	renderTo     : 'listaElegidos',
	items : [
	{
		items : cmpdivipol
	},
	{
		layout      : 'form',
		items : [
			cboxPartido,
			cboxGenero,
			gridComuna
		]
	}
	],
	buttons : [ {text : 'Generar Listado', handler : generarListado } ],
	
	//Para colapsar el formulario y dar mas espacio para la presentacion de resultados
	animCollapse  : true,
	collapsible   : true//,
	//titleCollapse : true
 });
 
 //La grid que despliega los resultados de la consulta de elegidos
 var gdlistaelegidos = getListadoElegidos();
 gdlistaelegidos.render('gridlistaElegidos');
 
 cmpdivipol.on('click',function(nodo,even) {	
	stcomuna.load({
		params : {
			coddivipol : nodo.attributes.coddivipol,
			codnivel   : nodo.attributes.codnivel
		}
	});
 });
 
 function generarListado(fm,evn){
	var valido = true;
	var parametros = {};
	
	if(!Ext.isEmpty(cmpdivipol.divipol)) {
		parametros.coddivipol = cmpdivipol.divipol.attributes.coddivipol;
		parametros.codnivel   = cmpdivipol.divipol.attributes.codnivel;
	} else {
		valido = false;
	}
	
	if(gridComuna.getStore().getTotalCount() > 0 && gridComuna.getSelectionModel().hasSelection()) {
		var rec = gridComuna.getSelectionModel().getSelected();
		parametros.comuna = rec.get('idcomuna');
	}
	
	if(!Ext.isEmpty(cboxPartido.getValue())) {
		parametros.partido = cboxPartido.getValue();
	}
	
	if(!Ext.isEmpty(cboxGenero.getValue())) {
		parametros.genero = cboxGenero.getValue();
	}
	
	if(valido) {
		gdlistaelegidos.getStore().load({ params : parametros });
		gdlistaelegidos.expand(true); //expando la grid y oculto el formulario de consulta
		pnPrincipal.collapse(true);
	} else {
		mensaje('Advertencia','Seleccione una divipol',Ext.Msg.OK,Ext.Msg.WARNING);
	}
 }
 
 //La tabla de se encarga de mostrar los resultados
 //INICIO GRID RESULTADOS
 // create the data json store
 function getListadoElegidos(){
	
	var store = new Ext.data.JsonStore({
		url      : URL_SIPREL+'controladores/consuListadoElegidos.php',
		autoLoad : false,
		fields   : [
		   {name : 'corporacion'},
		   {name : 'partido'},
		   {name : 'nombres'},
		   {name : 'apellidos'},
		   {name : 'genero'},
		   {name : 'ubicacion'}
		]
	});
	  
	var grid = new Ext.grid.GridPanel({
		store      : store,
		columns    : [
			{ header : "Corporacion",      width : 160, sortable : true, dataIndex : 'corporacion' },
			{ header : "Ubicaci&oacute;n", width : 160, sortable : true, dataIndex : 'ubicacion'   },
			{ header : "Partido",          width : 75,  sortable : true, dataIndex : 'partido'     },
			{ header : "Nombres",          width : 75,  sortable : true, dataIndex : 'nombres'     },
			{ header : "Apellidos",        width : 75,  sortable : true, dataIndex : 'apellidos'   },
			{ header : "G&eacute;nero",    width : 75,  sortable : true, dataIndex : 'genero'      }
		],
		tbar         : genBarraExportar('listadoConsolidado'),
		plugins      : buscarPlugin(),
		stripeRows   : true,
		autoScroll   : true,
		border       : true,
		frame        : true,
		animCollapse : true,
		collapsible  : true,
		collapsed    : true,
		height       : 350,
		width        : 720,
		title        : 'Listado Elegidos'
	});
	return grid;
 }
 
 
 });
//FIN GRID RESULTADOS