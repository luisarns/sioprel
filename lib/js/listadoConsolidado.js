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
 
 
 //Barra de exportacion
 var exporBar = [
	{
		text    : 'PDF',
		cls     : 'x-btn-text-icon',
		icon    : URL_SIPREL+'images/pdf.png',
		handler : exportar,
		format  : 'pdf'
	},
	{
		text    : 'XLS',
		cls     : 'x-btn-text-icon',
		icon    : URL_SIPREL+'images/xls.jpg',
		handler : exportar,
		format  : 'xls'
	},
	{
		text    : 'RTF',
		cls     : 'x-btn-text-icon',
		icon    : URL_SIPREL+'images/rtf.png',
		handler : exportar,
		format  : 'rtf'
	},
	{
		text    : 'TXT',
		cls     : 'x-btn-text-icon',
		icon    : URL_SIPREL+'images/txt.jpg',
		handler : exportar,
		format  : 'txt'
	},
	{
		text    : 'DOC',
		cls     : 'x-btn-text-icon',
		icon    : URL_SIPREL+'images/doc.png',
		handler : exportar,
		format  : 'doc'
	}
 ];
 
 /*
 * Funcion para exportar
 * @param	btn bbuton
 * @param	even	EventObject
 */
 function exportar(btn,even){
	window.open(URL_SIPREL+'controladores/informes/listadoConsolidado_'+btn.format+'.php');
 }
 
 var grid = new Ext.grid.GridPanel({
	store: store,
	columns: [
		{header : "Corporacion", width: 160, sortable: true, dataIndex: 'corporacion'},
		{header : "Lista", width: 75, sortable: true, renderer : mostrarLista, dataIndex: 'lista'},
		{id     : 'idPartido', header: "Partido", width: 75, sortable: true, dataIndex: 'partido'},
		{header : "Votos", width: 75, sortable: true, dataIndex: 'votos'}
	],
	tbar : exporBar,
	stripeRows : true,
	autoScroll : true,
	border : true,
	frame  : true,
	autoExpandColumn : 'idPartido',
	height : 350,
	width  : 720,
	title  : 'Resultado Elecciones'
 });

 grid.render('listaConsolidado');
 store.load();
