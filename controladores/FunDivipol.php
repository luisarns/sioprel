<?php
 function getNumDigitos($nivel){
   switch($nivel){
	  case 1:
		return 2;
	  case 2:
		return 5;
	  case 3: 
		return 7;
	  case 4:
		return 9;
	  default : 
	    return 0;
   }
 }

 function getCodPadre($coddivipol,$nivel){
   return substr($coddivipol,0,getNumDigitos($nivel));
 }
 
 /*
 * Recibe una divipol y retorna si tiene divipoles que dependen 
 * de ella
 *
 * @param	string	$codcordivipol	codigo corto de la divipol
 * @param	int	$codnivel	nivel de la divipol
 * @param	resource	$sqlite	conexion a la base de datos
 * @return	bool
 * @access public
 */
 function tieneHijos($codcordivipol,$codnivel,$firebird){
	if($codnivel != 4 && is_numeric($codcordivipol) && is_numeric($codnivel)){
		$cdnvhijos = $codnivel + 1;
	
		$query =<<<SON
		SELECT COUNT(*) as num FROM pdivipol 
		WHERE coddivipol LIKE $codcordivipol || '%' AND codnivel = $cdnvhijos
SON;
		$result = ibase_query($firebird,$query);
		
		$row = ibase_fetch_object($result);
		return ($row->NUM > 0)? true : false;
	}
	return false;
 }
 
 
?>