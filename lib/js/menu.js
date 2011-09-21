
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
			tabType : 'load',
			draggable : false,
			text : 'Punto 7.',
			leaf : true
		},
		{
			tabType : 'load',
			draggable : false,
			text : 'Punto 9.',
			leaf : true
		},
		{
			text : 'Punto 1',
			draggable : false,
			expanded : true,
			leaf : false,
			children : [
				{
					id        : 'idconpart',
					tabType   : 'load',
					draggable : false,
					text      : 'Consolidado Partido',
					leaf      : true
				},
				{
					id        : 'idconlist',
					tabType   : 'load',
					draggable : false,
					text      : 'Consolidado Listas',
					leaf      : true
				},
				{
					id        : 'idconcorpr',
					tabType   : 'load',
					draggable : false,
					text      : 'Consolidado Corporaci&oacute;n',
					leaf      : true
				}
			]
		},
		{
			text : 'Punto 2',
			draggable : false,
			expanded : true,
			leaf : false,
			children : [
				{
					id : 'conspartnacio',
					tabType : 'load',
					draggable : false,
					text : 'Consolidado Partido Departamental',
					leaf : true
				}
			]
		},
		{
			text : 'Punto 3',
			draggable : false,
			expanded : true,
			leaf : false,
			children : [
				{
					id : 'resvotcandida',
					tabType : 'load',
					draggable : false,
					text : 'Resumen Votaci&oacute;n Candidatos',
					leaf : true
				}
			]
		},
		{
			text : 'Punto 4',
			draggable : false,
			expanded : true,
			leaf : false,
			children : [
				{
					id : 'resvotpartido',
					tabType : 'load',
					draggable : false,
					text : 'Resumen Votaci&oacute;n Partido',
					leaf : true
				}
			]
		},
		{
			text : 'Punto 5',
			draggable : false,
			expanded : true,
			leaf : false,
			children : [
				{
					id      : 'listelegidos',
					tabType : 'load',
					draggable : false,
					text : 'Listado Elegidos Corporaciones',
					leaf : true
				}
			]
		},
		{
			text : 'Punto 6',
			draggable : false,
			expanded : true,
			leaf : false,
			children : [
				{
					id      : 'listcandvota',
					tabType : 'load',
					draggable : false,
					text : 'Listado Votaci&oacute;n Candidatos',
					leaf : true
				}
			]
		},
		{
			text : 'Punto 8',
			draggable : false,
			expanded : true,
			leaf : false,
			children : [
				{
					id        : 'idlistlistado',
					tabType   : 'load',
					draggable : false,
					text      : 'Listado Listas Mayor Votaci&oacute;n',
					leaf      : true
				}
			]
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
					click : function(node) {
						if (!node.attributes.leaf) { //si el nodo no es una hoja
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
						}else if (node.attributes.id == 'idlistlistado') {
							actualizarPanel('central',URL_SIPREL+'html/listadoListas.html');
						}else if (node.attributes.id == 'listcandvota') {
							actualizarPanel('central',URL_SIPREL+'html/listadoVotacionCandidato.html');
						}else if (node.attributes.id == 'idconpart') {
							actualizarPanel('central',URL_SIPREL+'html/Punto1/consolidadoPartido.html');
						}else if (node.attributes.id == 'idconlist'){
							actualizarPanel('central',URL_SIPREL+'html/Punto1/consolidadoLista.html');
						}else if (node.attributes.id == 'idconcorpr'){
							actualizarPanel('central',URL_SIPREL+'html/Punto1/consolidadoCorporacion.html');
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