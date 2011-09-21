Ext.ns('Siprel.CmpCorporacion');
Siprel.CmpCorporacion = {
	init : function () {
		var jstore = new Ext.data.JsonStore({
			url    : URL_SIPREL+'controladores/Corporacion.php',
			autoLoad : true,
			fields : [ 
			{ name : 'codcorpo', type : 'int' }, 
			'descorpo', 
			{name:'codnivel', type : 'int'}, 
			{name:'tipoeleccion', type: 'int'},
			{name:'comuna', type:'int'}
			]
		});
		
		var gridCorporacion = new Ext.grid.GridPanel({
			store : jstore,
			columns : [
				{header: "C&oacute;digo", width: 60, sortable: true, dataIndex: 'codcorpo'},
				{id:'iddecorpo', header: "Descripci&oacute;n", width: 180, sortable: true, dataIndex: 'descorpo'}
			],
			stripeRows : true,
			sm     : new Ext.grid.RowSelectionModel( { singleSelect : true } ),
			autoExpandColumn : 'iddecorpo',
			height : 170,
			width  : 200,
			frame  : true,
			title  :'Corporaciones P&uacute;blicas',
			corporacion : null
		});
		
		//Para pasar informacion a la variable del objeto
		var regClic = function (grid,rowInd,e) {
			grid.corporacion = grid.getStore().getAt(rowInd);
		}
		
		gridCorporacion.on('rowclick',regClic); 
		
		return gridCorporacion;
	}
};