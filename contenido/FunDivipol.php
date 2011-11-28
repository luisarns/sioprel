<?php
    function getNumDigitos($nivel)
    {
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

    function getNivelCorporacion($corporacion)
    {
        switch ($corporacion) {
          case 1:
                return 1;
              
          case 2:
                return 1;
              
          case 3: 
                return 2;
              
          case 4:
                return 2;
              
          case 5:
                return 2;
        }
    }
    
   function getQueryDivipolCompleta($coddivipol, $codnivel)
   {
        $codniveltmp = 1;
        $inArrDivipol = array();
        $inArrNivel = array();
        while ($codniveltmp <= $codnivel) {
            array_push($inArrDivipol,str_pad(substr($coddivipol, 0, getNumDigitos($codniveltmp)), 9, '0'));
            array_push($inArrNivel,$codniveltmp);
            $codniveltmp = $codniveltmp + 1;
        }
        $inDivipol = '(' . implode(',',$inArrDivipol) . ')';
        $inNivel = '(' . implode(',',$inArrNivel) . ')';

        $queryDivipoles = "SELECT descripcion as descripcion , codnivel as codnivel "
                        . "FROM pdivipol "
                        . "WHERE coddivipol in $inDivipol "
                        . "AND codnivel in $inNivel "
                        . "ORDER BY codnivel";
        
        return $queryDivipoles;
   }
   
?>