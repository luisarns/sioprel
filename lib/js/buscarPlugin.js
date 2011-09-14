function buscarPlugin(){
	return new Ext.ux.grid.Search({
		mode          : 'local',
		position      : 'top',
		searchText    : 'Filtrar',
		iconCls       : 'buscar',
		selectAllText : 'Seleccionar todos',
		searchTipText : 'Escriba el texto que desea buscar y presione la tecla enter',
		width         : 100
	});
}