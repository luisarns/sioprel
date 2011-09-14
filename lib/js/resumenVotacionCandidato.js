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

 //Grilla con el resumen de la votacion por candidato en los municipios
 var store = new Ext.data.JsonStore({
	url      : URL_SIPREL+'controladores/votaCandMunicipio.php',
	autoLoad : false,
	fields   : [
	   {name : 'idcandidato', type : 'int'},
	   {name : 'nombres'},
	   {name : 'apellidos'},
	   {name : 'votos', type : 'int'}
	]
 });
 
 var columnas = [
	{header: "Cod. Candidato", width: 100, sortable: true, dataIndex: 'idcandidato'},
	{header: "Nombres", width: 115, sortable: true, dataIndex: 'nombres'},
	{header: "Apellidos", width: 150, sortable: true, dataIndex: 'apellidos'},
	{header: "Votos", width: 80, sortable: true, dataIndex: 'votos'}
 ];

 
 var gdVotaCandidato = new Ext.grid.GridPanel({
	store      : store,
	columns    : columnas,
	height     : 300,
	width      : 480,
	tbar       : genBarraExportar('resumenVatacionCandidato'),
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
		items : cmpdivipol
	},
	{
		columnWidth : .8,
		items : gdVotaCandidato
	}
	]
 });
 
 //FUNCIONES PARA EL MANEJO DE LAS ACCIONES
 
 //Carga la votacion de un candidato dado un municipio seleccionado en la divipol
 function cargarVotaCand(nodo,even) {
	if(nodo.attributes.codnivel == 2) {
		gdVotaCandidato.getStore().load({
			params : {
				coddivipol : nodo.attributes.coddivipol,
				codnivel   : nodo.attributes.codnivel
			}
		});
		store.sort('votos','DESC');
	} else {
		mensaje('Informaci&oacute;n','Seleccione un municipio',Ext.Msg.OK,Ext.Msg.INFORM);
	}
 }
 
 cmpdivipol.on('click',cargarVotaCand);