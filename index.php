<?php
//phpinfo();
/*
 * Aufgabe:
 * Erstelle ein Klassendiagramm für meine Büchersammlung:
 * Jedes Buch kommt genau einmal vor.
 * Die Bücher haben Autoren und jeweils einen Titel, sind von genau einem Verlag
 * herausgegeben worden und stehen in genau einem Zimmer.
 * Ich möchte jederzeit Wissen, wo ein spezielles Buch steht.
 * Ich möchte wissen, welcher Autor welches Buch (mit-)geschrieben hat.
 * Zu jedem Titel möchte ich die Autoren wissen.
 * Zu jedem Zimmer möchte ich die Bücher wissen.
 *
 * Details:
 * Datenbankanschluss wie bisher erstellen
 * pro Datensatz ein Objekt erstellen, dabei den Konstruktor verwenden
 * insgesamt sollen 2 Verlags- und 2 Zimmerobjekte in der
 * index.php erstellt werden
 */

/*
 * Aufgabe 14:30 Uhr bis 15:30
 * Erstelle eine HTML-Seite mit einer Form mit input-Feld,
 * die die Daten für einen Verlagsnamen aufnimmt
 * und in der Tabelle verlag speichert
 */

// config.php einbinden
include 'config.php';

//include funktionen;
include 'functions/htmlFunctions.php';
include 'functions/buchFunctions.php';

// Klassen einbinden
spl_autoload_register(function ($className) {
    include 'class/' . $className . '.php';
});

/*
// Variablen empfangen
$view = $_REQUEST['view'] ?? 'liste';
$vername = $_POST['vername'] ?? null;

if (isset($vername)) {
    $v = new Verlag();
    $verlagHinzu = $v->insertIntoVerlag($vername);
}
*/

/*
 * 20.05.2021
 * Ziel:
 * OnePageLayout für Bücher CRUD-Anwendung erstellen
 * Ausgabe in Tabellenform
 * Autoren kommen in ein Feld
 * Alle Felder in input-Felder umwandeln
 * Können wir neue Bücher in der Tabelle eingeben
 */
$buecher = Buch::getAllAsObjects();
$ausgabe = [];
// Schleife über alle Bücher
// alle möglichen Zimmer
// vorausgewählter Wert
// PullDownMenu bauen
// in Array Tabelle einbauen

for ($i = 0; $i < count($buecher); $i++) {
    $autor = '';
    $zeile = [];
    $zimmerInput = buildHTMLInputSelect(Zimmer::getAllAsObjects(),
        false,
        'zimmerWert', [$buecher[$i]->getZimmer()->getId()]);
    $verlagInput = buildHTMLInputSelect(Verlag::getAllAsObjects(),
        false,
        'verlagWert',
        [$buecher[$i]->getVerlag()->getId()]);
    array_push($zeile,
        $buecher[$i]->getId(),
        '<input type="text" value="' . $buecher[$i]->getTitel() . '">',
        $zimmerInput,
        $verlagInput);
    $autor = '<button>';
    foreach ($buecher[$i]->getAutoren() as $buchAutor) {
        $autor .= $buchAutor->getVorname() . " " . $buchAutor->getNachname() . ' ';
    }
    $autor .= '</button>';
    array_push($zeile, $autor);
    array_push($ausgabe, $zeile);
}

$tablehead = ['ID', 'Titel', 'Zimmer', 'Verlag', 'Autor/en'];
$html = buildHTMLTable($ausgabe, $tablehead);
include 'view/listeBuecher.php';