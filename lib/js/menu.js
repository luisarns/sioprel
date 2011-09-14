
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
			id : 'conspartlist',
			tabType : 'load',
			draggable : false,
			text : 'Punto 1.',
			leaf : true
		},
		{
			id : 'conspartnacio',
			tabType : 'load',
			draggable : false,
			text : 'Punto 2.',
			leaf : true
		},
		{
			id : 'resvotcandida',
			tabType : 'load',
			draggable : false,
			text : 'Punto 3.',
			leaf : true
		},
		{
			id : 'resvotpartido',
			tabType : 'load',
			draggable : false,
			text : 'Punto 4.',
			leaf : true
		},
		{
			id      : 'listelegidos',
			tabType : 'load',
			draggable : false,
			text : 'Punto 5.',
			leaf : true
		},
		{
			tabType : 'load',
			draggable : false,
			text : 'Punto 6.',
			leaf : true
		},
		{
			tabType : 'load',
			draggable : false,
			text : 'Punto 7.',
			leaf : true
		},
		{
			tabType : 'load',
			draggable : false,
			text : 'Punto 8.',
			leaf : true
		},
		{
			tabType : 'load',
			draggable : false,
			text : 'Punto 9.',
			leaf : true
		}
		]
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
						}else if (node.attributes.id == 'conspartlist') {
							actualizarPanel('central',URL_SIPREL+'html/consolidadoPartidoListaCorpo.html');
						}else if(node.attributes.id == 'conspartnacio'){
							actualizarPanel('central',URL_SIPREL+'html/consolidadoPartidoDepto.html');
						}else if (node.attributes.id == 'resvotcandida'){
							actualizarPanel('central',URL_SIPREL+'html/resumenVotacionCandidato.html');
						}else if (node.attributes.id == 'resvotpartido') {
							actualizarPanel('central',URL_SIPREL+'html/resumenVotacionPartido.html');
						}else if (node.attributes.id == 'listelegidos'){
							actualizarPanel('central',URL_SIPREL+'html/listadoElegidos.html');
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