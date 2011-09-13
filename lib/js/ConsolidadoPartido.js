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
 
 /*componente para seleccionar una corporacion*/
 var cmpcorporacion = Siprel.CmpCorporacion.init();
 
 /*componente para seleccionar una divipol*/
 var cmpdivipol = Siprel.CmpDivipol.init(4);//Nivel maximo puesto
 
 
 /*
 * Store dinamico depende de la divipol seleccionada
 * tiene como parametro la divipol que esta seleccionada en el arbol
 */
 var stmesas = new Ext.data.JsonStore({
	url    : URL_SIPREL+'controladores/mesasDivipol.php',
	root   : 'datos',
	totalProperty : 'total',
	fields : [ { name : 'codTx', type : 'int' },{ name : 'mesa' } ]
 });
 stmesas.setDefaultSort('mesa', 'codTx'); //para ordenar los registros del store
 

 var gdMesa = new Ext.grid.GridPanel({
	store   : stmesas,
	columns : [
		{id:'idcodTx', header: "C&oacute;digo transmisi&oacute;n", width: 150, sortable: true, dataIndex: 'codTx'},
		{header: "Mesa", width: 70, sortable: true, dataIndex: 'mesa'}
	],
	stripeRows : true,
	sm: new Ext.grid.RowSelectionModel({ singleSelect : true } ),
	autoExpandColumn : 'idcodTx',
	height : 370,
	width  : 270,
	frame  : true,
	title  :'Mesas',
	mesa   : null
 });
 
 /*Carga las grid mesas en funcion de la divipol y corporacion seleccionada*/
 function cargarMesas(){
	var param = '';
	if(!Ext.isEmpty(cmpdivipol.divipol) && !Ext.isEmpty(cmpcorporacion.corporacion)){
		param +='?codcorporacion='+cmpcorporacion.corporacion.data.codcorpo;
		param +='&coddivipol='+cmpdivipol.divipol.attributes.coddivipol;
		param +='&codnivel='+cmpdivipol.divipol.attributes.codnivel;
		
		gdMesa.getStore().proxy = new Ext.data.HttpProxy({
			url: URL_SIPREL+'controladores/mesasDivipol.php'+param
		});
		gdMesa.getStore().load();
	}
	//Para cargar las mesas validar que se haya seleccionado una divipol de nivel puesto
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
 
 var cboxLista = new Ext.form.ComboBox({
	fieldLabel     : 'Lista',
	editable       : true,
	hidden         : false,
	name           : 'lista',
	forceSelection : true,
	triggerAction  : 'all',
	typeAhead      : true,
	displayField   : 'id',
	valueField     : 'nombre',
	store 		   : [[0,'UNICA'],[1,'PREFERENTE']]
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
				cboxLista,
				gdMesa
			]
		}
	]
 });
 
 //Esta es la funcion encargada de enviar los datos al servidor
 //si envio los datos aqui y los guardo en session y luego renderizo el componente que presentara los datos
 //y obtengo los datos por medio del filtro que ya esta en session y de esta misma manera genero los reportes xls, pdf, doc y demas
 function mostrarResultados(fm,evn){
	var valido = true;
	var idpartido;
	var idmesa;
	var idlista;
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
		if(!Ext.isEmpty(cboxLista.getValue())){
			parametros.idlista = cboxLista.getValue();
			cboxLista.clearValue();
		}
		if(!Ext.isEmpty(cmpdivipol.divipol.attributes.idcomuna)){
			parametros.idcomuna = cmpdivipol.divipol.attributes.idcomuna;
		}
		
		var record = gdMesa.getSelectionModel().getSelected();
		gdMesa.getSelectionModel().clearSelections();
		if(!Ext.isEmpty(record)){
			parametros.idmesa  = record.data.codTx;
		}
	}
	
	/*Envio la peticion al servidor con los filtros seleccionados*/
	if(resultElecciones.getForm().isValid() && valido){
		resultElecciones.getForm().submit({
			url : URL_SIPREL+'controladores/consolidadoPartido.php',//scrip php encargado de mostrar la tabla con los datos
			params : parametros,
			success : function (fm,act){
				actualizarPanel('central',URL_SIPREL+'html/listadoConsolidado.html'); //listado consolidado elecciones
			},
			failured : function(fm,act){
				mensaje('Error','Ocurrio un error mientras se procesaba la solicitud',Ext.Msg.OK,Ext.Msg.ERROR);
			}
		});
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
	buttons : [ {text : 'Generar Consolidado', handler : mostrarResultados } ]
 });
 
 resultElecciones.render();//recordar primero definir las funciones antes de renderizar los componentes
 
 /*Actualiza la configuracion del arbol y recarga los nodos*/
 cmpcorporacion.on('rowclick',function(grd,indx,eob){
	cmpdivipol.getLoader().baseParams.codcorpo = grd.getStore().getAt(indx).get('codcorpo');
	delete cmpdivipol.getLoader().baseParams.idcomuna;
	cmpdivipol.getLoader().load(cmpdivipol.getRootNode());
	//cmpdivipol.getRootNode().expand();
	
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