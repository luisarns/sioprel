/**
 * Grilla consolPartidoDepto.js cuya funciones es:
 * Presenta el listado de cada partido, en el cual hay
 * un comparativo en donde se aprecien el número de
 * candidatos avalados, número de candidatos elegidos y la respectiva votación
 * alcanzada por el partido político. De igual manera.
 * Muestra un segundo listado donde estan los nombres de los respectivos 
 * ciudadanos inscritos y elegidos por el partido político. 
 *
 * @author    Ing. Luis A. Nuñez
 * @copyright (c) 2011, by Ing. Luis A. Nuñez
 * @date      09. Septiembre 2011
 *
 */
 
 /**Grid que muestra los candidatos (idcandidato,nombres,apellidos,estado) del partido seleccionado */
 function crearGridCandidatos(){
	
	//Convertir este store en un json
	var store = new Ext.data.JsonStore({
		url      : URL_SIPREL+'controladores/candidatosPartido.php',
		autoLoad : false,
		fields   : [
		   {name: 'codcandidato'},
		   {name: 'nombres'},
		   {name: 'apellidos'},
		   {name: 'estado'}
		]
	});
	
	var columnas = [
		{header: "Cod. Candidato", width: 100, sortable: true, dataIndex: 'codcandidato'},
		{header: "Nombres", width: 115, sortable: true, dataIndex: 'nombres'},
		{header: "Apellidos", width: 150, sortable: true, dataIndex: 'apellidos'},
		{header: "Estado", width: 80, sortable: true, dataIndex: 'estado'}
	];
	
	var gdCandidatos = new Ext.grid.GridPanel({
		title      : 'Candidatos',
		id         : 'idgridcandidatos1',
		store      : store,
		columns    : columnas,
		height     : 200,
		width      : 480,
		loadMask   : true,
		frame      : true
	});
	
	return gdCandidatos;
 }
 
 var myData = [
	[1,'ALIANZA AFROCOLOMBIANA',150,15,1500],
	[2,'PARTIDO LIBERAL',120,12,1200],
	[3,'PARTIDO CONSERVADOR',45,5,500],
	[4,'PARTIDO ANDINO',52,2,100]
 ];

 var store = new Ext.data.SimpleStore({
	fields: [
	   {name: 'codpartido', type : 'int'},
	   {name: 'partido'},
	   {name: 'candAvala', type: 'int'},
	   {name: 'candElegi', type: 'int'},
	   {name: 'votos', type: 'int'}
	]
 });

 store.loadData(myData);

 /**Grid que contiene el consolidado de la votacion por partido a nivel departamental*/
 var gdConparnac = new Ext.grid.GridPanel({
	store    : store,
	columns : [
		{header: "Partido", width: 170, sortable: true, dataIndex: 'partido'},
		{header: "Cand. Avalados", width: 100, sortable: true, dataIndex: 'candAvala'},
		{header: "Cand. Elegidos", width: 100, sortable: true, dataIndex: 'candElegi'},
		{header: "Part. Votos", width: 80, sortable: true, dataIndex: 'votos'}
	],
	sm: new Ext.grid.RowSelectionModel({ singleSelect : true } ),
	height : 300,
	width  : 480, 
	frame  : true,
	tbar   : genBarraExportar('consolPartidoDepto'),
	title  : 'Consolidado Partidos Departamental'
 });
 
  function cargarCandidatos(grid,index,even){
	var rec = grid.getStore().getAt(index);
	var gdcand = Ext.getCmp('idgridcandidatos1');
	gdcand.setTitle('Candidatos '+rec.data.partido);
	gdcand.getStore().load({
		params : { 
			codpartido : rec.data.codpartido 
		}
	});
 }
 
 gdConparnac.on('rowclick',cargarCandidatos);
 
 var gdCandidatos = crearGridCandidatos();
 
 var pnPrincipal = new Ext.Panel({
	autoHeight       : true,
	autoWidth        : true,
	autoScroll       : true,
	frame            : true,
	renderTo         : 'consPartNacio',
	items : [
		gdConparnac,
		gdCandidatos
	]
 });