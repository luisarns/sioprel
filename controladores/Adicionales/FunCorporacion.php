<?php
 function caracteresCorpo($nivel){
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

?>