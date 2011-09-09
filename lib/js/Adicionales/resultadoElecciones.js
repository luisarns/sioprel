/**
 * Formulario resultadoElecciones.js cuya funciones es:
 * Proporcionar los resultados de las elecciones de Gobernadores, Alcaldes, Asambleas,
 * Concejo y JAL, categorizados por departamento, municipio, comuna, zona, puesto,
 * mesa, por candidato y o lista y/o por movimiento o partido político
 *
 * @author    Ing. Luis A. Nuñez
 * @copyright (c) 2011, by Ing. Luis A. Nuñez
 * @date      02. Septiembre 2011
 *
 */
 
 /*componente para seleccionar una corporacion*/
 var cmpcorporacion = Siprel.CmpCorporacion.init();
 
 /*componente para seleccionar una divipol*/
 var cmpdivipol = Siprel.CmpDivipol.init();
 
 /*combobox para cargar la mesa cuando la divipol seleccionada sea un puesto/este a nivel de puesto*/
 /*Mantener la mesa oculta, mientras la divipol seleccionada no sea un puesto*/
 var cboxMesa = new Ext.form.ComboBox({
	fieldLabel     : 'Mesa',
	editable       : false,
	forceSelection : true,
	triggerAction  : 'all',
	typeAhead      : true,
	displayField   : 'id',
	valueField     : 'nombre',
	store 		   : [[0,'Mesa 1'],[1,'Mesa 2']]
 });
 
 
 /*combobox para cargar la lista*/
 /*
 Este combo queda con esta configuracion ya que no hay una tabla maestro que contenga la informacion de las listas
 a la cual corresponde un candidato
 */
 var cboxLista = new Ext.form.ComboBox({
	fieldLabel     : 'Lista',
	editable       : false,
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
	forceSelection : true,
	triggerAction  : 'all',
	typeAhead      : true,
	displayField   : 'nombre',
	valueField     : 'id',
	store 		   : jspartidos
 });
 
 /*Panel superior, contiene los componentes corporacion y divipol */
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
				cboxMesa
			]
		}
	]
 });
 
 /*Panel principal*/
 var resultElecciones = new Ext.form.FormPanel({
	title    : 'Resultado Elecciones',
	renderTo : 'combo1',
	width      : 550,
	autoHeight : true,
	frame   : true,
	border  : true,
	items    : [
		pnSuperior
	],
	buttons : [ {text : 'Ver Resultados', handler : mostrarResultados } ]
 }).render();
 
 function mostrarResultados(fm,evn){
	Ext.Msg.alert('Informaci&oacute;n','Mostrando resultados');
 }
 
 /*Actualiza la configuracion del arbol y recarga los nodos*/
 cmpcorporacion.on('rowclick',function(grd,indx,eob){
	cmpdivipol.getLoader().baseParams.codcorpo = grd.getStore().getAt(indx).get('codcorpo');
	delete cmpdivipol.getLoader().baseParams.idcomuna; //elimino la propiedad idcomuna
	cmpdivipol.getLoader().load(cmpdivipol.getRootNode());
	cmpdivipol.getRootNode().expand();
 });
 
 /*Manejo de eventos de los componentes*/
 cmpdivipol.on('click',function(){
	Ext.Msg.alert('Divipol',this.divipol.attributes.text);
 });
 
  cmpdivipol.getLoader().on('beforeload',function(tree,node){
	if(!Ext.isEmpty(node.attributes.idcomuna)){
		tree.baseParams.idcomuna = node.attributes.idcomuna;
	}
 });