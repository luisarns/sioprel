/**
 * Formulario ConsolidadoPartido.js cuya funciones es:
 * Consolidado por partido, listas, corporación, a nivel mesa, puesto, zona,
 * comuna, municipio
 *
 * @author    Ing. Luis A. Nuñez
 * @copyright (c) 2011, by Ing. Luis A. Nuñez
 * @date      02. Septiembre 2011
 *
 */
 Ext.onReady(function () {
 
 var cmpcorporacion = Siprel.CmpCorporacion.init();
 var cmpdivipol = Siprel.CmpDivipol.init(4);
 
 
 /*
 * Store dinamico depende de la divipol seleccionada
 * tiene como parametro la divipol que esta seleccionada en el arbol
 */
 var stmesas = new Ext.data.JsonStore({
	url    : URL_SIPREL+'controladores/mesasDivipolCorpo.php',
	root   : 'datos',
	autoLoad : false,
	totalProperty : 'total',
	fields : [ { name : 'codTx', type : 'int' },{ name : 'mesa' } ]
 });
 stmesas.setDefaultSort('mesa', 'codTx');
 

 var gdMesa = new Ext.grid.GridPanel({
	store   : stmesas,
	columns : [
		{id:'idcodTx', header: "C&oacute;digo transmisi&oacute;n", width: 150, sortable: true, dataIndex: 'codTx'},
		{header: "Mesa", width: 70, sortable: true, dataIndex: 'mesa'}
	],
	tbar       : ['->'],
	plugins    : buscarPlugin(),
	stripeRows : true,
	sm         : new Ext.grid.RowSelectionModel({ singleSelect : true } ),
	autoExpandColumn : 'idcodTx',
	height     : 370,
	width      : 270,
	frame      : true,
	title      : 'Mesas',
	mesa       : null
 });
 
 /*Carga las grid mesas en funcion de la divipol y corporacion seleccionada*/
 function cargarMesas(){
	var parametros = {};
	if(!Ext.isEmpty(cmpdivipol.divipol) && !Ext.isEmpty(cmpcorporacion.corporacion)){
		
		//Solo carga las mesas cuando se selecciona un puesto
		if(cmpdivipol.divipol.attributes.codnivel == 4){
			parametros.codcorporacion = cmpcorporacion.corporacion.data.codcorpo;
			parametros.coddivipol     = cmpdivipol.divipol.attributes.coddivipol;
			parametros.codnivel       = cmpdivipol.divipol.attributes.codnivel;
			
			gdMesa.getStore().load({
				params : parametros
			});		
		} else {
			stmesas.removeAll();
			gdMesa.mesa = null;
		}
	}
 }
 
 /*Asigno la mesa seleccionada a la propiedad mesa del grid*/
 gdMesa.on('rowclick',function(grid,index,even){
	grid.mesa = grid.getStore().getAt(index);
 });
 
 var cboxMesa = new Ext.form.ComboBox({
	fieldLabel     : 'Mesa',
	editable       : true,
	forceSelection : true,
	triggerAction  : 'all',
	typeAhead      : true,
	displayField   : 'id',
	valueField     : 'nombre',
	store 		   : [[0,'Mesa 1'],[1,'Mesa 2']]
 });
 
 var chklista = new Ext.form.Checkbox({
	fieldLabel : 'Listado',
	inputValue : 1,
	boxLabel   : 'Detallado',
	name       : 'detallado'
 });
 
 
 /*El store que almacena los datos de los partidos politicos que participan en las elecciones*/
 var jspartidos = new Ext.data.JsonStore({
	url    : URL_SIPREL+'controladores/Partidos.php',
	autoLoad : true,
	fields : [ { name : 'id', type : 'int' }, {name : 'nombre'}]
 });
 
 /*combobox para cargar los partidos*/
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
 
 /* Table panel de 2 columnas en la primera contiene los componentes divipol y corporacion. En la segunda
 *	los campos, para el filtrado de los resultados, partido, lista y la grid mesas
 */
 var pnSuperior = new Ext.Panel({
	collapsible  : true,
	layout       : 'table',
	defaults     : { bodyStyle : 'padding:10px'},
	layoutConfig : { columns   : 2 },
	items : [
		{
			items : [ cmpcorporacion , cmpdivipol ]
		},
		{
			layout : 'form',
			items : [
				cboxPartido,
				chklista,
				gdMesa
			]
		}
	]
 });
 
 function mostrarResultados(fm,evn){
	var valido = true;
	var parametros = {};
	
	if(Ext.isEmpty(cmpdivipol.divipol) || Ext.isEmpty(cmpcorporacion.corporacion)){
		valido = false;
		mensaje('Informaci&oacute;n','Seleccione la divipol y la corporaci&oacute;n',Ext.Msg.OK,Ext.Msg.WARNING);
	} else {
		parametros.coddivipol = cmpdivipol.divipol.attributes.coddivipol;
		parametros.codnivel = cmpdivipol.divipol.attributes.codnivel;
		parametros.codcorporacion = cmpcorporacion.corporacion.data.codcorpo;
		parametros.corpnivel = cmpcorporacion.corporacion.data.codnivel;
		
		if( !Ext.isEmpty(cboxPartido.getValue()) ){
			parametros.idpartido = cboxPartido.getValue();
			cboxPartido.clearValue();
		}
		
		if(!Ext.isEmpty(cmpdivipol.divipol.attributes.idcomuna)){
			parametros.idcomuna = cmpdivipol.divipol.attributes.idcomuna;
		}
		
		var record = gdMesa.getSelectionModel().getSelected();
		gdMesa.getSelectionModel().clearSelections();
		if(!Ext.isEmpty(record)){
			parametros.idmesa  = record.data.codTx;
		}
		
		if(chklista.getValue()){
			parametros.detallado = 1;
		}

	}
	
	if(valido) {
		consolidadoPartido.getStore().load({ 
			params : {
				datos : Ext.encode(parametros)
			}
		});
		consolidadoPartido.expand(true);
		resultElecciones.collapse(true);
	}
	
 }
 
 /*Panel principal*/
 var resultElecciones = new Ext.form.FormPanel({
	title    : 'Resultado Elecciones',
	renderTo : 'consPartListCorp',
	width      : 550,
	autoHeight : true,
	frame   : true,
	border  : true,
	items    : [
		pnSuperior
	],
	buttons : [ {text : 'Generar Consolidado', handler : mostrarResultados } ],
	animCollapse  : true,
	collapsible   : true
 });
 
 resultElecciones.render();
 
 /*Actualiza la configuracion del arbol y recarga los nodos*/
 cmpcorporacion.on('rowclick',function(grd,indx,eob){
	cmpdivipol.getLoader().baseParams.codcorpo = grd.getStore().getAt(indx).get('codcorpo');
	delete cmpdivipol.getLoader().baseParams.idcomuna;
	cmpdivipol.getLoader().load(cmpdivipol.getRootNode());
	cargarMesas();
 });
 
 /*Manejo de eventos de los componentes*/
 cmpdivipol.on('click',function(){
	cargarMesas();
 });
 
 cmpdivipol.getLoader().on('beforeload',function(tree,node){
	if(!Ext.isEmpty(node.attributes.idcomuna)){
		tree.baseParams.idcomuna = node.attributes.idcomuna;
	}
 });
 
 //-- Grid de resultados
 function getGrid() {
	
	var store = new Ext.data.JsonStore({
		url      : URL_SIPREL+'controladores/consolidadoPartido.php',
		autoLoad : false,
		fields   : [
		   {name : 'divipol'},
		   {name : 'codigo'},
		   {name : 'partido'},
		   {name : 'votos'}
		]
	});
	  
	var grid = new Ext.grid.GridPanel({
		store      : store,
		columns    : [
			{ header : "Divisi&oacute;n Pol&iacute;tica",     width : 115, sortable : true, dataIndex : 'divipol' },
			{ header : "C&oacute;digo", width : 60, sortable : true, dataIndex : 'codigo'  },
			{ header : "Partido",       width : 250, sortable : true, dataIndex : 'partido' },
			{ header : "Votos",         width : 100, sortable : true, dataIndex : 'votos'   }
		],
		tbar         : genBarraExportar('ConsolidadoPartido'),
		plugins      : buscarPlugin(),
		stripeRows   : true,
		autoScroll   : true,
		border       : true,
		frame        : true,
		animCollapse : true,
		collapsible  : true,
		collapsed    : true,
		height       : 450,
		width        : 550,
		title        : 'Listado Consolidado Partido'
	});
	return grid;
 }
 
  var consolidadoPartido = getGrid();
  consolidadoPartido.render('gridconsPartListCorp');
 
 });