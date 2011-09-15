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
	title        : 'Form Consolidado Partido',
	frame        : true,
	autoHeight   : true,
	width        : 550,
	layout       : 'table',
	defaults     : { bodyStyle : 'padding:10px'},
	layoutConfig : { columns   : 2 },
	autoScroll   : true,
	renderTo     : 'idconsolpartid',
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
		{ text : 'Generar' }
	]
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
	switch(nodo.attributes.codnivel){
		case '2':
			cargarComunas(nodo,even);
		break;
		case '4':
			cargarMesas(nodo,even);
		break;
	}
 });
 
 
 