/**
 * Formulario resumenVotacionCandidato.js cuya funciones es:
 * Presentar la votación por candidato, al seleccionar el respectivo
 * municipio, totalizándola
 *
 * @author    Ing. Luis A. Nuñez
 * @copyright (c) 2011, by Ing. Luis A. Nuñez
 * @date      13. Septiembre 2011
 * @version 1.0.0
 */

 var cmpdivipol = Siprel.CmpDivipol.init(2);//Nivel maximo municipio
 var cmpcorporacion = Siprel.CmpCorporacion.init();
  
 ///////////////////Inicio Mostrar campo comuna/////////////////////////
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
		hidden         : true,
		// hideLabel      : true,
		store 		   : stcomunas
 });
 var comunaVisible = false;
 ///////////////////FIN/////////////////////////
 
 
 //Grilla con el resumen de la votacion por candidato en los municipios
 var store = new Ext.data.JsonStore({
	url      : URL_SIPREL+'controladores/votaCandMunicipio.php',
	autoLoad : false,
	fields   : [
	   {name : 'codigo'},
	   {name : 'nombres'},
	   {name : 'apellidos'},
	   {name : 'votos', type : 'int'}
	]
 });
 
 var columnas = [
	{header: "C&oacute;digo", width: 100, sortable: true, dataIndex: 'codigo'},
	{header: "Nombres", width: 115, sortable: true, dataIndex: 'nombres'},
	{header: "Apellidos", width: 150, sortable: true, dataIndex: 'apellidos'},
	{header: "Votos", width: 80, sortable: true, dataIndex: 'votos'}
 ];

 var gdVotaCandidato = new Ext.grid.GridPanel({
	store      : store,
	columns    : columnas,
	height     : 300,
	width      : 480,
	tbar       : genBarraExportar('votaCandMunicipio'),
	plugins    : buscarPlugin(),
	loadMask   : true,
	frame      : true,
	title  :'Resumen Votacion Candidatos'
 });
 
 //Contenedor principal
  var pnPrincipal = new Ext.Panel({
	autoHeight       : true,
	autoWidth        : true,
	layout           : 'column',
	autoScroll       : true,
	frame            : true,
	renderTo         : 'resVotCandida',
	items : [
	{
		columnWidth: .2,
		items : [cmpcorporacion,cmpdivipol]
	},
	{
		columnWidth : .8,
		layout : 'form',
		items : [cboxComunas,gdVotaCandidato]
	}
	],
	buttonAlign : 'center',
	buttons : [
		{text: 'Generar', handler : cargarVotaCand}
	]
 });
 
 //Carga la votacion de un candidato dado un municipio seleccionado en la divipol
 function cargarVotaCand(fm,even) {
	
	if(!Ext.isEmpty(cmpcorporacion.corporacion) && !Ext.isEmpty(cmpdivipol.divipol)){
		var parametros = {};

		parametros.coddivipol     = cmpdivipol.divipol.attributes.coddivipol;
		parametros.codnivel       = cmpdivipol.divipol.attributes.codnivel;
		parametros.codcorporacion = cmpcorporacion.corporacion.data.codcorpo;
		parametros.nivcorpo       = cmpcorporacion.corporacion.data.codnivel;
		
		if(!Ext.isEmpty(cboxComunas.getValue())){
			parametros.idcomuna = cboxComunas.getValue();
		}
		
		if(cmpdivipol.divipol.attributes.codnivel == 2) {
			gdVotaCandidato.getStore().load({
				params : parametros
			});
			store.sort('votos','DESC');
		} else {
			mensaje('Informaci&oacute;n','Seleccione un municipio',Ext.Msg.OK,Ext.Msg.INFO);
		}
		
	}else {
		mensaje('Informaci&oacute;n','Seleccione una corporaci&oacute;n y luego la divipol',Ext.Msg.OK,Ext.Msg.INFO);
	}
	
 }
 
 cmpcorporacion.on('rowclick',mostrarComuna);
 
 //Manejo de los eventos
 cmpdivipol.on('click',function(nodo,even) {
	if(cboxComunas.isVisible()){
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
		comunaVisible = true;
		cboxComunas.setVisible(comunaVisible)
	}else if(comunaVisible){
		limpiarComuna();
		comunaVisible = !comunaVisible;
		cboxComunas.setVisible(comunaVisible);
	}
 }
 
 function limpiarComuna(){
		stcomunas.removeAll();
		cboxComunas.reset();
 }
 