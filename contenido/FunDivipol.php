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
?>