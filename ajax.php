<?php
// config.php einbinden
include 'config.php';

// Funktionen einbinden
include 'functions/htmlFunctions.php';
include 'functions/buchFunctions.php';

// Klassen einbinden
spl_autoload_register(function ($className) {
    include 'class/' . $className . '.php';
});

// Übergabe Variable(n)
$feld = $_POST['ajax'];
//$feld = '{"pk":"1","wert":"2","spalteNr":2}';

//$feld = '{"pk":1, "action": "holePulldown"}';
$feldObj = json_decode($feld);
file_put_contents('debug.txt', implode(':::',(array)$feldObj));
//echo '<pre>';
//print_r($feldObj);
//echo '</pre>';
//echo $feldObj->wert . '<br>';

if (isset($feldObj->action) && $feldObj->action === "holePulldown") {
    $autoren_ids = [];
    $b = new Buch();
    $buch = $b->getBuchById($feldObj->pk);
    $autoren = $buch->getAutoren();
//    echo '<pre>';
//    print_r($autoren);
//    echo '</pre>';
    foreach ($autoren as $autor){
        array_push($autoren_ids, $autor->getId());
    }
    echo buildHTMLInputSelect(Autor::getAllAsObjects(),true,'autoren_id',$autoren_ids);
} else {
    $spalte = getSpalte($feldObj);
// falls titel,zimmer oder verlag geändert wurde, dann Tabelle buch updaten
    if ($spalte === 'titel') {
        $b = new Buch();
        $buch = $b->getBuchById($feldObj->pk);
        $buch->setTitel($feldObj->wert);
        $buch->update();
    } elseif ($spalte === 'zimmer_id') {
        $b = new Buch();
        $buch = $b->getBuchById($feldObj->pk);
        $buch->setZimmer((int)$feldObj->wert);
        $buch->update();
    } elseif ($spalte === 'verlag_id') {
        $b = new Buch();
        $buch = $b->getBuchById($feldObj->pk);
        $buch->setVerlag((int)$feldObj->wert);
        $buch->update();
    } elseif ($spalte === 'autoren') {

    }

    echo $spalte;
}
