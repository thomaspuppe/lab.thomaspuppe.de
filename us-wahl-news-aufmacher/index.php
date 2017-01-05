<?php

function _in($haystack, $needle)
{
    if (is_array($needle)) {
        foreach ($needle as $subneedle) {
            if (stripos($haystack, $subneedle) !==false) {
                return true;
            }
        }
    } else {
        return stripos($haystack, $needle) !==false;
    }

    return false;
}

function _handleJsonError()
{
    echo '<pre style="border:2px red solid">ERROR';

    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - Keine Fehler';
            break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximale Stacktiefe überschritten';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Unterlauf oder Nichtübereinstimmung der Modi';
            break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unerwartetes Steuerzeichen gefunden';
            break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntaxfehler, ungültiges JSON';
            break;
        case JSON_ERROR_UTF8:
            echo ' - Missgestaltete UTF-8 Zeichen, möglicherweise fehlerhaft kodiert';
            break;
        default:
            echo ' - Unbekannter Fehler';
            break;
    }

    echo '</pre>';
    die();
}

function getColorClassFromCell($cell)
{
    $title = $cell['title'];

    # enforced corrections

    if (array_key_exists('class', $cell) && $cell['class'] != '') {
        return $cell['class'];
    }

    if (_in($title, array('audi', 'lampedusa'))) {
        return 'color-other';
    }

    # inspect title

    if (_in($title, array('hillary', 'clinton')) && _in($title, array('donald', 'trump'))) {
        return 'color-uswahl';
    } elseif (_in($title, array('clinton', 'demokraten', 'hillary'))) {
        return 'color-clinton';
    } elseif (_in($title, array('trump', 'republikaner', 'donald'))) {
        return 'color-trump';
    } elseif (_in($title, array('white house', 'us-wahl', 'usa', 'amerika', 'first family', 'wahlkampf', 'weiße haus', 'präsidentschaft', 'wahllokal', 'kopf-an-kopf', 'stimmen', 'florida', 'kanadisch', 'wahlparty', 'wahlbetrug', 'ohio', 'wahl', 'arroganz', 'weißen haus', 'cyrus'))) {
        return 'color-uswahl';
    } elseif (_in($title, array('erdogan', 'türk', 'cumhuriyet'))) {
        return 'color-turkey';
    } elseif (_in($title, array('csu', 'maut', 'rente', 'grün', 'union', 'deutschland', 'bundeswehr', 'spd', 'leyen', 'afd', 'parteitag', 'freital', 'klimaschutz'))) {
        return 'color-germany';
    } elseif (_in($title, array('elbe', 'region', 'münchen', 'brandenburg', 'senftenberg', 'cottbus', 'rundschau', 'berliner', 'tegel', 'hoyerswerda'))) {
        return 'color-region';
    } elseif (strlen($title)) {
        return 'color-other';
    } elseif (strlen($title)==0) {
        return 'color-error';
    }

    return '';
}

function get_datetime_array($str)
{

    # 2016-11-03_22-50-01

    $strArray = explode('_', $str);
    $dateString = $strArray[0];
    $timeString = $strArray[1];

    $dateArray = explode('-', $dateString);
    $timeArray = explode('-', $timeString);

    return array(
        'year' => $dateArray[0],
        'month' => $dateArray[1],
        'day' => $dateArray[2],
        'hour' => $timeArray[0],
        'minute' => $timeArray[1],
        'second' => $timeArray[2]
    );
}

function renderTimeCell($row, $lastRow)
{

    if ((!$lastRow) || $row['datetime']['day'] != $lastRow['datetime']['day']) {
        # this is embarassing. but setlocale() does not work for some reason
        $daystring = 'Freitag';
        if ($row['datetime']['day'] == '04') {
            $daystring = 'Freitag';
        } elseif ($row['datetime']['day'] == '05') {
            $daystring = 'Samstag';
        } elseif ($row['datetime']['day'] == '06') {
            $daystring = 'Sonntag';
        } elseif ($row['datetime']['day'] == '07') {
            $daystring = 'Montag';
        } elseif ($row['datetime']['day'] == '08') {
            $daystring = 'Dienstag';
        } elseif ($row['datetime']['day'] == '09') {
            $daystring = 'Mittwoch';
        } elseif ($row['datetime']['day'] == '10') {
            $daystring = 'Donnerstag';
        } elseif ($row['datetime']['day'] == '11') {
            $daystring = 'Freitag';
        }

        return sprintf('<td colspan="11" class="newday">%s %d.%d.</td></tr><tr><td></td>', $daystring, $row['datetime']['day'], $row['datetime']['month']);
    } elseif ($row['datetime']['hour'] != $lastRow['datetime']['hour']) {
        return sprintf('<td>%d:00</td>', $row['datetime']['hour']);
    }

    return '<td></td>';
}

?>
<!doctype html>
<html lang="de">
<head>
    <meta charset=utf-8>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" id="viewport-meta">
    <title>Aufmacher auf deutschen News-Websites zur US-Wahl</title>
    <meta name="author" content="Thomas Puppe">

    <style type="text/css">
        html {
            font-size: 20px; 
        }
        body { 
            background: #fff; 
            color: #333; 
            font-family: "Hoefler Text", "Georgia", "Times New Roman", Times, serif; 
            line-height: 150%;
            max-width: 980px;
            margin: 2rem auto;
        }
        h1, h2 {
            font-family: Helvetica, sans-serif; 
            font-size: 2em; 
            line-height: 150%;
            font-weight: normal;
            margin: 20px 0px 0px 0px;
        }
        h2 {
            font-size: 1.5em; 
            margin: 30px 0px 10px 0px;
        }
        p {
            line-height: 30px; 
            margin:30px auto;
        }

        section {
            width: 80%; max-width: 30em; margin: 60px 10%;
        }

        table {
            border-spacing: 0;
            width: 100%;
        }

        table thead td {
            text-align: center;
            vertical-align: bottom;
            padding-bottom: 0.5rem;
        }

        table td {
            border-left: 0.1rem #FFFFFF solid;
            font-size: 0.6rem;
            line-height: 0.5rem;
            padding: 0;
            position: relative;
            width: 10%;
        }

        table td:first-child:not(:last-child) {
            text-align: right;
        }

        table td.newday {
            background-color: #333333;
            color: #EEEEEE;
            font-size: 0.8rem;
            line-height: 1em;
            padding-left: 0.5ch;
        }

        table td.cut {
            border-top: 1px #FFFFFF solid;
        }

        table td a {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-decoration: none;
            top: 0;
        }

        dl {
            line-height: 2em;
            vertical-align: text-bottom;
        }

        dt {
            border: 1px #000000 solid;
            clear: both;
            display: inline-block;
            width: 1.2em;
            height: 1.2em;
        }
        dd {
            display: inline-block;
            margin-left: 0;
            margin-right: 4ch;
            
        }


        .color-clinton {
            background-color: #3B556C;
            color: #FFFFFF;
            text-shadow: 2px 2px 3px #000000; 
        }
        .color-trump {
            background-color: #B73E45;
            color: #FFFFFF;
            text-shadow: 2px 2px 3px #000000; 
        }
        .color-uswahl {
            background-color: #794A59;
            background: -webkit-repeating-linear-gradient(left, #3B556C, #3B556C 5px, #B73E45 5px, #B73E45 10px);
            background: repeating-linear-gradient(to right, #3B556C, #3B556C 5px, ##B73E45 5px, #B73E45 10px);
            color: #FFFFFF;
            text-shadow: 2px 2px 3px #000000;
        }
        .color-turkey {
            background-color: #798A65;
            color: #FFFFFF;
            text-shadow: 2px 2px 3px #000000; 
        }
        .color-germany {
            background-color: #FFE2AD;
            color: #000000;
            text-shadow: 2px 2px 3px #FFFFFF;
        }
        .color-region {
            background-color: #FFF1D6;
            color: #000000;
            text-shadow: 2px 2px 3px #FFFFFF;
        }
        .color-other {
            background-color: #DEDEDE;
            color: #000000;
            text-shadow: 2px 2px 3px #FFFFFF; 
        }
        .color-error {
            background-color: #000000;
            background: -webkit-repeating-linear-gradient(90deg, #FFFF00, #FFFF00 10px, #000000 10px, #000000 20px);
            background: repeating-linear-gradient(90deg, #FFFF00, #FFFF00 10px, #000000 10px, #000000 20px);
            color: #FFFFFF;
            text-shadow: 2px 2px 3px #000000;
        }

        .legend span {
            border: 1px #000000 solid;
            padding: 0.25rem 1ch;
            white-space: nowrap;
        }

    </style>
</head>

<body>
<h1>Aufmacher zur US-Wahl</h1>

<?php
$string = file_get_contents("/var/www/newscurl/result.json");
$json = json_decode($string, true);

if (!$json) {
    _handleJsonError();
}

$collectionArray = array();

foreach ($json as $k => $v) {
    $datetime = $v['datetime'];
    $url = array_key_exists('url', $v) ? $v['url'] : '#';
    $publisher = $v['publisher'];
    $publisher_label = $v['publisher_label'];
    $image = array_key_exists('image', $v) ? $v['image'] : '#';
    $title = array_key_exists('title', $v) ? $v['title'] : '#';
    $class = array_key_exists('class', $v) ? $v['class'] : '#';

    if (!array_key_exists($datetime, $collectionArray)) {
        $collectionArray[$datetime] = array();
    }

    $collectionArray[$datetime][$publisher] = array(
        'title' => $title,
        'publisher_label' => $publisher_label,
        'image' => $image,
        'url' => $url,
        'class' => $class
    );
}

ksort($collectionArray);
$outputArray = array();

foreach ($collectionArray as $k => $v) {
    $outputArray[] = array(
        'datetime' => get_datetime_array($k),
        'values' => $v
    );
}
?>

<pre><?php/* print_r($outputArray); */?></pre>


<p class="legend">
    <strong>Legende:</strong>

    <dl>
        <dt class="color-clinton"></dt>
        <dd>US-Wahl: Clinton</dd>
        <dt class="color-trump"></dt>
        <dd>US-Wahl: Trump</dd>
        <dt class="color-uswahl"></dt>
        <dd>US-Wahl allgemein</dd>
        <dt class="color-turkey"></dt>
        <dd>Türkei</dd>
        <dt class="color-germany"></dt>
        <dd>Deutschland</dd>
        <dt class="color-region"></dt>
        <dd>Regional</dd>
        <dt class="color-other"></dt>
        <dd>Andere</dd>
        <dt class="color-none"></dt>
        <dd>Fehlende Daten (Crawling Selektor oder so)</dd>
        <dt class="color-error"></dt>
        <dd>Error (Seite down)</dd>
    </dl>

<table>
<thead>
    <tr>
        <td></td>
        <td>Spie&shy;gel</td>
        <td>Zeit</td>
        <td>Welt</td>
        <td>FAZ</td>
        <td>Fo&shy;cus</td>
        <td>Sued&shy;deut&shy;sche</td>
        <td>taz</td>
        <td>Ber&shy;li&shy;ner<br>Mor&shy;gen&shy;post</td>
        <td>Lau&shy;si&shy;tzer<br>Rund&shy;schau</td>
    </tr>
</thead>
<tbody>
    <?php

    $publishers = array(
        'spiegel',
        'zeit',
        'welt',
        'faz',
        'focus',
        'sueddeutsche',
        'taz',
        'morgenpost',
        'lronline'
        );

    $rowsToSpare = 1;

    $rowCount = count($outputArray);
    for ($i=0; $i<$rowCount; $i++) {
        $row = $outputArray[$i];
        $lastRow = ($i == 0 ? false : $outputArray[$i-1]);
    ?>
        <tr>
            <?php echo renderTimeCell($row, $lastRow); ?>

            <?php
            foreach ($publishers as $publisher) {
                if (!array_key_exists($publisher, $row['values'])) {
                    echo '<td></td>';
                    continue;
                }

                $cell = $row['values'][$publisher];

                $currentTitle = $cell['title'];
                $lastTitle = ($lastRow && array_key_exists($publisher, $lastRow['values'])? $lastRow['values'][$publisher]['title'] :'');

                if ($lastTitle != $currentTitle) {
                    $cutClass = ' cut';
                }

            ?>
                <td class="<?php echo getColorClassFromCell($cell); ?><?php echo $cutClass?>">
                    <a  href="<?php echo array_key_exists('url', $cell) ? $cell['url'] : '#'; ?>" 
                        title="<?php echo array_key_exists('title', $cell) ? $cell['publisher_label'] . ': ' . htmlentities($cell['title']) : '(keine Daten)'; ?>"></a>
                </td>
            <?php

            $cutClass = '';
            }
            ?>
        </tr>
    <?php
    }
    ?>
</tbody>
</table>


</body>
</html>