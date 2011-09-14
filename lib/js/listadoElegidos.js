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
	sm: new Ext.grid.RowSelectionModel({ singleSelect : true } ),	
	height : 300,
	width  : 300,
	frame  : true,
	title  :'Comunas',
	mesa   : null
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
	buttons : [ {text : 'Generar Listado', handler : generarListado } ]
 });
 
 cmpdivipol.on('click',function(nodo,even) {	
	stcomuna.load({
		params : {
			coddivipol : nodo.attributes.coddivipol,
			codnivel   : nodo.attributes.codnivel
		}
	});
 });
 
 //Funcion encargada de enviar los datos al servidor para generar el listado de los elegidos
 function generarListado(fm,evn){
	var valido = true;
	var parametros = {};
	
	if(!Ext.isEmpty(cmpdivipol.divipol)){
		parametros.coddivipol = cmpdivipol.divipol.attributes.coddivipol;
		parametros.codnivel   = cmpdivipol.divipol.attributes.codnivel;
	}else {
		valido = false; //obligo a que se seleccione una divipol antes de enviar la solicitud
	}
	
	if(pnPrincipal.getForm().isValid() && valido) {
		pnPrincipal.getForm().submit({
			url : URL_SIPREL+'controladores/consuListadoElegidos.php',//scrip php encargado de mostrar la tabla con los datos
			params : parametros,
			success : function (fm,act){
				//actualizarPanel('central',URL_SIPREL+'html/listadoConsolidado.html'); //listado consolidado elecciones
				mensaje('Informacion',act.result.msg,Ext.Msg.OK,Ext.Msg.INFORM);
			},
			failured : function(fm,act){
				mensaje('Error','Ocurrio un error mientras se procesaba la solicitud',Ext.Msg.OK,Ext.Msg.ERROR);
			}
		});
	}else {
		mensaje('Mensaje','Seleccione una divipol',Ext.Msg.OK,Ext.Msg.WARNING);
	}

 }
 
 
 
 
 
 
 