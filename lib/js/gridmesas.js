Siprel.GridMesa = {
	init : function () { //Crea y retorna la grid y en el llamado hace el envio de los parametros
		
		var stmesas =  new Ext.data.JsonStore({
			url      : URL_SIPREL+'controladores/paraMesas.php',
			autoLoad : false,
			fields   : [ 
				{ name : 'id', type : 'int' },
				{ name : 'nombre' }
			]
		});
		
		//campos utilizados para mostrar el listado de mesas
		var columnas = [
			{},
			{}
		];
		
		var gredMesa = new Ext.grid.GridPanel({
			columns    : columnas,
			store      : stmesas,
			stripeRows : true,
			border     : true,
			frame      : true,
			height : 285,
			width  : 270,
			title  :'Mesas'
		});
		
		//manejar el evento onclic para renderizar la mesa
		
		return gredMesa;
	}
};