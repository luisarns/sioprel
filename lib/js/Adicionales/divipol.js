//Este archivo contiene el componente encargado de mostrar las divipoles disponibles en el sistema 
//mediante el uso de un arbol
//El panel que contiene el arbol, este arbol sera generado de forma dinamica desde el servidor y en funcion de algunos parametros
//que pueden ser pasados por el usuario programador ya que todo este codigo estara dentro de un funcion
var treeDivipol = new Ext.tree.TreePanel({
	title      : 'Divisi&oacute;n Pol&iacute;tica',
	renderTo   : 'rdDivipol',
	autoScroll : true,
	animate    : true,
	enableDD   : false,
	containerScroll : true,
	border  : false,
	height  : 250,
	width   : 200,
	loader  : new Ext.tree.TreeLoader({
		dataUrl : URL_SIPREL+'/controladores/Divipol.php',
		listeners : {
			'beforeload' : function(tree,node){
				tree.baseParams.coddivipol = node.attributes.coddivipol;
				tree.baseParams.nivel = node.attributes.codnivel;
			}
		}
	}),
	root : {
		nodeType   : 'async',
		text       : '31-VALLE',
		codnivel   : 1,
		coddivipol : '310000000',
		draggable  : false,
		id         : 'idDivipol'
	},
	listeners : {
		'click' : function (nodo,even) {
			Ext.Msg.alert('Nodo',nodo.text); 
		}
	}
});
treeDivipol.render();
treeDivipol.getRootNode().expand();