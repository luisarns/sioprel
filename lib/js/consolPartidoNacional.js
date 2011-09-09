/**
 * Grilla consolPartidoNacional.js cuya funciones es:
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
 // Ext.Msg.alert('Informacion','Aqui ira algo muy pronto (Comming Soom)');
	
 var myData = [
	['ALIANZA AFROCOLOMBIANA',150,15,1500],
	['PARTIDO LIBERAL',120,12,1200],
	['PARTIDO CONSERVADOR',45,5,500],
	['PARTIDO ANDINO',52,2,100],
 ];

 var store = new Ext.data.SimpleStore({
	fields: [
	   {name: 'partido'},
	   {name: 'candAvala', type: 'int'},
	   {name: 'candElegi', type: 'int'},
	   {name: 'votos', type: 'int'}
	]
 });
 
 store.loadData(myData);//cargo los datos de prueba

 /*Tabla de partidos configuracion de las columnas de la tabla por ahora no tiene datos*/
 var gdConparnac = new Ext.grid.GridPanel({
	store   : store,
	renderTo : 'consPartNacio',
	columns : [
		{id:'idPartido', header: "Partido", width: 170, sortable: true, dataIndex: 'partido'},
		{header: "Cand. Avalados", width: 100, sortable: true, dataIndex: 'candAvala'},
		{header: "Cand. Elegidos", width: 100, sortable: true, dataIndex: 'candElegi'},
		{header: "Part. Votos", width: 80, sortable: true, dataIndex: 'votos'}
	],
	stripeRows : true,
	sm: new Ext.grid.RowSelectionModel({ singleSelect : true } ),
	autoExpandColumn : 'idPartido',
	autoScroll : true,
	height : 180,
	width  : 470, 
	frame  : true,
	title  :'Consolidado Partidos Departamental'
 });

 gdConparnac.render();
 
 //panel central donde estaran contenidas las 3 tablas pueden terner un layout table
 