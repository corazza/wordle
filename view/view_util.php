<?php

function print_table($length, $attempts, $green_color, $brown_color) {
    echo '<table class="guesscontainer">';
    
    foreach ($attempts as $attempt) {
        echo '<tr>';
        $characters = str_split($attempt);
        for ($i = 0; $i < $length; ++$i) {
            $td_class = "regular";
            if (array_key_exists($characters[$i], $brown_color)) {
                $td_class = "correctLetter";
            }
            if (array_key_exists($attempt, $green_color)) {
                if (in_array($i, $green_color[$attempt])) {
                    $td_class = "correctPosition";
                }
            }
            echo '<td class="' . $td_class . '">' . $characters[$i] . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';   
}

function print_big_hints($length, $big_hints) {
    if (count($big_hints) === 0) {
        return;
    }

    echo '<h3> Veliki hintovi: </h3>';
    echo '<table class="guesscontainer">';    
    echo '<tr>';
    for ($i = 0; $i < $length; ++$i) {
        $td_class = "regular";
        if (array_key_exists($i, $big_hints)) {
            $td_class = "correctPosition";
            echo '<td class="' . $td_class . '">' . $big_hints[$i] . '</td>';
        } else {
            echo '<td class="' . $td_class . '">' . '</td>';
        }
    }
    echo '</tr>';

    echo '</table>';   
    echo '<br/>';
}

function print_hints($length, $hints) {
    if (count($hints) === 0) {
        return;
    }

    echo '<h3> Hintovi: </h3>';
    echo '<ul class="guesscontainer">';
    foreach ($hints as $hint => $exists) {
        echo '<li>' . $hint . '</li>';
    }
    echo '</ul>';
    echo '<br/>';
}


?>
