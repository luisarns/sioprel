Ext.ns('Siprel.CmpDivipol');
Siprel.CmpDivipol = {
	init : function (nivelmax) {
	
		var treeDivipol = new Ext.tree.TreePanel({
			title      : 'Divisi&oacute;n Pol&iacute;tica',
			autoScroll : true,
			animate    : true,
			enableDD   : false,
			containerScroll : true,
			border  : false,
			frame   : true,
			height  : 250,
			width   : 200,
			loader  : new Ext.tree.TreeLoader({
				dataUrl : URL_SIPREL+'controladores/DivipolCorporacion.php',
				listeners : {
					'beforeload' : function(tree,node){
						tree.baseParams.coddivipol = node.attributes.coddivipol;
						tree.baseParams.nivel = node.attributes.codnivel;
						
						//Hasta que nivel se llega en el arbol
						tree.baseParams.nivelmax = nivelmax;
					}
				}
			}),
			root : {
				nodeType   : 'async',
				text       : '27-SANTANDER',
				codnivel   : 1,
				coddivipol : '270000000',
				draggable  : false,
				id         : 'idDivipol'
			},
			divipol : null
		});
		
		function regClic(nodo,even) {
			treeDivipol.divipol = nodo;
		}
		
		treeDivipol.on('click',regClic);
		
		return treeDivipol;
	}
};