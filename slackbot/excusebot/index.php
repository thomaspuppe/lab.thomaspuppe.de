<?php
header('Content-Type: application/json');

function fetchOriginalPage() {
    return file_get_contents('http://www.programmerexcuses.com');
};

function getTextBetweenTags($string, $tagname) {
    $pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
    preg_match($pattern, $string, $matches);
    return $matches[1];
};

$wholeHtmlString = fetchOriginalPage();
$excuseString = getTextBetweenTags($wholeHtmlString, 'a');
$response = array('response_type'=> 'in_channel', 'text' => $excuseString);

echo json_encode($response);
