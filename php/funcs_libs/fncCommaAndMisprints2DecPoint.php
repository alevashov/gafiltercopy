<?php

#-----------
function fncCommaAndMisprints2DecPoint($strNumberUtf8) {
    # Все символы (а, как правило, такой один) заданной в UTF-8 строки,
    # вместо которых скорее всего хотели ввести десятичную точку/запятую,
    # заменяет на точку.
    
    return
        str_replace(
            array (',', '<', '>', 'б', 'Б', 'ю', 'Ю'),
            '.',
            $strNumberUtf8
        );

} # function
#-----------

?>