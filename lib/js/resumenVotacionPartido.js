/**
 * Formulario resumenVotacionPartido.js cuya funciones es:
 * Presentar la votación por partido, al seleccionar el respectivo
 * municipio, totalizándola
 *
 * @author    Ing. Luis A. Nuñez
 * @copyright (c) 2011, by Ing. Luis A. Nuñez
 * @date      13. Septiembre 2011
 * @version 1.0.0
 */
 Ext.onReady(function (){
 var cmpdivipol = Siprel.CmpDivipol.init(2);//Nivel maximo municipio

 //Grilla con el resumen de la votacion por candidato en los municipios
 var store = new Ext.data.JsonStore({
	url      : URL_SIPREL+'controladores/votaPartMunicipio.php',
	autoLoad : false,
	fields   : [
	   {name : 'codpartido', type : 'int'},
	   {name : 'descripcion'},
	   {name : 'votos', type : 'int'}
	]
 });
 
 var columnas = [
	{header: "Cod.Partido", width: 100, sortable: true, dataIndex: 'codpartido'},
	{header: "Nombre", width: 200, sortable: true, dataIndex: 'descripcion'},
	{header: "Votos", width: 80, sortable: true, dataIndex: 'votos'}
 ];

 var gdVotaPartido = new Ext.grid.GridPanel({
	store      : store,
	columns    : columnas,
	height     : 300,
	width      : 480,
	tbar       : genBarraExportar('resumenVatacionPartido'),
	plugins    : buscarPlugin(),
	loadMask   : true,
	frame      : true,
	title      : 'Resumen Votacion Partidos'
 });
 
 //Contenedor principal
  var pnPrincipal = new Ext.Panel({
	autoHeight       : true,
	autoWidth        : true,
	layout           : 'column',
	autoScroll       : true,
	frame            : true,
	renderTo         : 'resVotPartido',
	items : [
	{
		columnWidth: .2,
		items : cmpdivipol
	},
	{
		columnWidth : .8,
		items : gdVotaPartido
	}
	]
 });
 
 //FUNCIONES PARA EL MANEJO DE LAS ACCIONES
 
 //Carga la votacion de un candidato dado un municipio seleccionado en la divipol
 function cargarVotaPart(nodo,even){
	gdVotaPartido.getStore().load({
		params : {
			coddivipol : nodo.attributes.coddivipol,
			codnivel   : nodo.attributes.codnivel
		}
	});
	store.sort('votos','DESC');
 }
 
 cmpdivipol.on('click',cargarVotaPart);

});