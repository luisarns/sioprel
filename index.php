<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<!--Elementos necesarios para usar Extjs2.0-->
	<link rel="stylesheet" type="text/css" href="lib/extjs/resources/css/ext-all.css" />
	<script type="text/javascript" src="lib/extjs/adapter/ext/ext-base.js"></script>
	<script type="text/javascript" src="lib/extjs/ext-all-debug.js"></script>
	
	<!--Para el idioma -->
	<script type="text/javascript" src="lib/extjs/build/locale/ext-lang-es.js"></script>
	
	<!--Archivos complementarios para usar funciones utiles -->
	<script type="text/javascript" src="lib/Ext.ux.grid.Search.js"></script>
	<script type="text/javascript" src="lib/MultiSelect.js"></script>
	<script type="text/javascript" src="lib/js/global.js"></script>
	
	<!--Titulo de la pagina-->
	<title>Estad&iacute;sticas Electorales</title>
	
	<!--Pantalla principal del sistema-->
	<script type="text/javascript">
	Ext.onReady(function(){
		Ext.ns('Siprel');
		Ext.QuickTips.init();
		Ext.BLANK_IMAGE_URL = 'lib/extjs/resources/images/default/s.gif';
		
		
		
		//Menu del sistema
		Siprel.Window = Ext.extend(Ext.Viewport, {
			layout: 'border',
			frame: true,
			border: true,
			autoScroll: true,
			initComponent : function() {
				Ext.apply(this,{
					items : [
					{   
						xtype   : 'panel',
						region  : 'north',
						id      : 'norte',
						cls     : 'titulo',
						buttons : [
						new Ext.form.ComboBox({
							xtype : 'combo',
							id    : 'usu_combo_theme',
							name  : 'idcombotheme',
							forceSelection : true,
							fieldLabel     : 'Tema',
							editable       : false,
							width          : '30',
							triggerAction  : 'all',
							store : [
								['peppermint','Rojo'],
								['gray','Gris'],
								['slate','Slate'],
								['blue','Azul'],
								['silverCherry','Plateado']
							],
							value: 'blue'
						})
						]
					},
					{
						xtype:        'panel',
						border:       false,
						title:        'Men&uacute; principal',
						id:           'panelMenu',
						region:       'west',
						style:        'width:18%;',
						split:        true, 
						titlebar:     true,
						collapsible:  true,
						frame:        true,
						autoScroll: true,
						items :  [ { xtype : 'panel', id : 'menu', border : false } ]
					},
					{
						region: 'center',
						xtype:  'panel',
						frame:  true, 
						id:     'central',
						layout: 'fit',
						style:  'width:100%;height:99%',
						fitToFrame: true,
						autoScroll: true//,
						//items : [{ xtype : 'panel', id : 'panelCentral', border : false }]
					}
				]
			});
			
			Siprel.Window.superclass.initComponent.apply(this, arguments);			
			},
			afterRender : function() {
				Siprel.Window.superclass.afterRender.apply(this, arguments);
				Ext.getCmp('usu_combo_theme').on('select', function(combo, record, indice){
					var tema = Ext.getCmp('usu_combo_theme').getValue();
					Ext.util.CSS.swapStyleSheet('theme','lib/extjs/resources/css/xtheme-'+tema+'.css');
				});
			}
		});
		
		var principal = new Siprel.Window({
			name:'Siprel'
		});
		
		principal.show();
		
		actualizarPanel('menu',URL_SIPREL+'html/menu.html'); //Las formas deben estar incluidas en un script
		
	});
	</script>
</head>
<body>
</body>
</html>
