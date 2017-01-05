<?php
$loc_de = setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
//$loc_de = setlocale(LC_ALL, 'de_DE');
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset=utf-8>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" id="viewport-meta">
    <title>Test</title>
    <meta name="author" content="Thomas Puppe">

</head>

<body>
<h1>Testing</h1>
<?php

$dto = new DateTime();
$dto->setDate(2016, 11, 04);
echo $dto->format('l');

echo "\n\nPreferred locale for german on this system is '$loc_de'";

?>

</body>
</html>