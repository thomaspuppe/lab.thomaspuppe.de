<?php
###### Welcome to Blockspring!
###### To help you get started, below is a fully-commented sample function that prints out a simple welcome message.

# require the blockspring package.
require('blockspring.php');

# TODO LIST
# - Montagsessen amm Freitagnachmittag ausgeben

function fetchMenuFromSource() {
    return file_get_contents('https://gist.githubusercontent.com/thomaspuppe/d3ec1821ec0a6fb9643b/raw/zon-menu.txt');
};

function getNextLunchDate() {
    $lunchDate = new DateTime();

    if (    $lunchDate->format('G') > 13 ||
            $lunchDate->format('w') == 0 ||
            $lunchDate->format('w') == 6 ) {
        $lunchDate->setTimestamp(strtotime($lunchDate->format('Y-m-d') . ' +1 Weekday'));
    }

    return $lunchDate;
}

function makeArrayFromMenuString($menuString) {
    $menuArray = Array();

    $menuLines = explode("\n", $menuString);
    foreach ($menuLines as $menuLine) {
        $menuCols = explode(";", $menuLine);
        
        if (is_array($menuCols) && count($menuCols)==6) {
            $menuLineArray = Array(
                'E1' => $menuCols[1],
                'E2' => $menuCols[3],
                'E3' => $menuCols[5],
                'N1' => $menuCols[2],
                'N2' => $menuCols[4]
            );
            $menuArray[$menuCols[0]] = $menuLineArray;
        }
    }

    return $menuArray;
};

function getIntro($lunchDate) {

    $germanWeekdayArray = Array('Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag');
    $dayOfTheWeek = $lunchDate->format('w');
    $germanWeekday = $germanWeekdayArray[$dayOfTheWeek];

    $today = new DateTime();

    if ($today->format('Y-m-d') == $lunchDate->format('Y-m-d')) {
        $intro = "Heute (" . $germanWeekday . ") auf dem Speiseplan:";
    } else {
        $intro = "Am " . $germanWeekday . " auf dem Speiseplan:";
    }

    return $intro;
}

function getEnding($containsAlcohol) {
    $endings = array("Enjoy!", "Yummie!", "Hmm, Lecker!", "Delicious!", "Mit Nachschlag, bitte!");
    $rand_key = array_rand($endings, 1);

    $returnString = '';

    if ($containsAlcohol) {
        $returnString.= 'Lunch mit Schnaps! ';
    }

    $returnString.= $endings[$rand_key];

    return $returnString;
}

function findAlcoholInMenuItem($menuItem) {
    foreach($menuItem as $key => $value){
        if( stristr( $value, '*' ) ){
            return true;
        }
    }
    return false;
}

function formatMenu($menuItem) {

    $searchArray = array("ﬂ", "‰", "ˆ", "¸", "ƒ", "Í", "È", "Ë");
    $replaceArray = array("ß", "ä", "ö", "ü", "Ä", "è", "é", "é");

    $menuString = 
        "- " . $menuItem['E1'] . "\n".
        "- " . $menuItem['E2'] . "\n".
        "- " . $menuItem['E3'] . "\n".
        "und zum Nachtisch gibt es: \n".
        "- " . $menuItem['N1'] . "\n".
        "- " . $menuItem['N2'];

    return str_replace($searchArray, $replaceArray, $menuString);
}

# pass your function into Blockspring::define. tells blockspring what function to run.
Blockspring::define(function ($request, $response) {

    $menuString = fetchMenuFromSource();
    $menuArray = makeArrayFromMenuString($menuString);

    $lunchDate = getNextLunchDate();
    $lunchDateFormatted = $lunchDate->format('d.m.Y');

    if (!isset($menuArray[$lunchDateFormatted])) {
        $response->addOutput('text', 'Leider keinen Speiseplan gefunden. Sag Thomas er soll nachtragen!');
    } else {
        $menuItem = $menuArray[$lunchDateFormatted];
        $responseText = getIntro($lunchDate) . "\n";
        $responseText.= formatMenu($menuItem);
        $responseText.= "\n\n" . getEnding(findAlcoholInMenuItem($menuItem));
        
        $response->addOutput('text', $responseText);
    }

    # return the output.
    $response->end();
});
