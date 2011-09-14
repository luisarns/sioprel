//Barra superior de opciones
 function genBarraExportar(nameFile){
	 var exporBar = [
		{
			text    : 'PDF',
			cls     : 'x-btn-text-icon',
			icon    : URL_SIPREL+'images/pdf.png',
			handler : exportar,
			format  : 'pdf'
		},
		{
			text    : 'XLS',
			cls     : 'x-btn-text-icon',
			icon    : URL_SIPREL+'images/xls.jpg',
			handler : exportar,
			format  : 'xls'
		},
		// {
			// text    : 'RTF',
			// cls     : 'x-btn-text-icon',
			// icon    : URL_SIPREL+'images/rtf.png',
			// handler : exportar,
			// format  : 'rtf'
		// },
		{
			text    : 'TXT',
			cls     : 'x-btn-text-icon',
			icon    : URL_SIPREL+'images/txt.jpg',
			handler : exportar,
			format  : 'txt'
		},
		{
			text    : 'DOC',
			cls     : 'x-btn-text-icon',
			icon    : URL_SIPREL+'images/doc.png',
			handler : exportar,
			format  : 'doc'
		},
		'->'
	];
	
	function exportar(btn,even){
		window.open(URL_SIPREL+'controladores/informes/'+nameFile+'_'+btn.format+'.php');
	}
	
	return exporBar;
 }