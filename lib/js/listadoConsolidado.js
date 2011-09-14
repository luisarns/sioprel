 // create the data json store
 var store = new Ext.data.JsonStore({
	url  : URL_SIPREL+'controladores/listadoConsolidado.php',
	root : 'datos',
	totalProperty : 'total',
	fields: [
	   {name: 'corporacion'},
	   {name: 'lista', type: 'int'},
	   {name: 'partido'},
	   {name: 'votos', type: 'int'},
	]
 });

 //Muestra el nombre de la lista
 function mostrarLista(val){
	if(val == 0){
		return 'UNICA';
	}else if(val == 1){
		return 'PREFERENTE';
	}
	return val;
 }
  
 var grid = new Ext.grid.GridPanel({
	store      : store,
	columns    : [
		{header : "Corporacion", width: 160, sortable: true, dataIndex: 'corporacion'},
		{header : "Lista", width: 75, sortable: true, renderer : mostrarLista, dataIndex: 'lista'},
		{id     : 'idPartido', header: "Partido", width: 75, sortable: true, dataIndex: 'partido'},
		{header : "Votos", width: 75, sortable: true, dataIndex: 'votos'}
	],
	tbar       : genBarraExportar('listadoConsolidado'),
	plugins    : buscarPlugin(),
	stripeRows : true,
	autoScroll : true,
	border     : true,
	frame      : true,
	autoExpandColumn : 'idPartido',
	height     : 350,
	width      : 720,
	title      : 'Resultado Elecciones'
 });

 grid.render('listaConsolidado');
 store.load();
