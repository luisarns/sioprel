var stcomuna = new Ext.data.JsonStore({
	url      : URL_SIPREL+'controladores/comunasDivipol.php',
	autoLoad : false,
	params   : {
		coddivipol : null,
		codnivel   : null
	},
	fields   : [ { name : 'idcomuna', type : 'int' }, { name : 'descripcion' } ]
 });
 var cboxComuna = new Ext.form.ComboBox({
	fieldLabel     : 'Comuna',
	editable       : true,
	name           : 'comuna',
	forceSelection : true,
	triggerAction  : 'all',
	typeAhead      : true,
	displayField   : 'descripcion',
	valueField     : 'idcomuna',
	store 		   : stcomuna
 });