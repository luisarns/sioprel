
	var menuPrincipal = [
	{
		text : 'Estad&iacute;sticas Electorales',
		id : 'est_electorales',
		draggable : false,
		expanded : true,
		iconCls:'icon-cmp',
		name : 'admon',
		children : [
		{
			id : 'rs_elecciones',
			tabType : 'load',
			draggable : false,
			text : 'Punto 1.',
			leaf : true
		},
		{
			text : 'Resultados Escrutinios',
			id : 'res_escrutinios',
			draggable : false,
			expanded : true,
			iconCls:'icon-cmp',
			name : 'inform',
			children : [
			{
				id : 'conseinform',
				tabType : 'load',
				text : 'Consultas e Informes',
				draggable : false,
				leaf:true
			}]
		}]
	}];
	
	Siprel.Menu = new Ext.Panel({
		renderTo    : 'menuPrincipal',
		border : true,
		style : 'width:100%;',
		titlebar : true,
		collapsible : true,
		items:[
			{
				cls:'ct'
			},
			{
				xtype           : 'treepanel',
				iconCls         : 'nav',
				rootVisible     : false,
				lines           : true,
				draggable       : false,
				containerScroll : true,
				singleExpand    : false,
				useArrows       : true,
				enableDD        : true,
				listeners       : {
					click : function(node)
					{
						if (node.attributes.name == 'admon') {
						}else if (node.attributes.name == 'inform'){
						}else if (node.attributes.id == 'rs_elecciones') {
							actualizarPanel('central',URL_SIPREL+'html/resultadoElecciones.html');
						}
					}
				},
				selectable    : true,
				singleSelect  : true,
				root          : new Ext.tree.AsyncTreeNode({
					expanded  : true,
					text      : 'Autos',
					draggable : false,
					id        : 'source',
					children  : menuPrincipal
				}),
				layoutConfig : {
					animate : true
				}
			}
		]
	});
	Siprel.Menu.render();