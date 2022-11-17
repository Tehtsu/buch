<?php
$html = $html ?? ''
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/main.css">
    <title>Bücher übersicht</title>
</head>
<body>
<?php

echo $html;
?>
</body>
<script>
    // an alle input-felder eine onChange-Methode anfügen
    let inputs = document.getElementsByTagName('input');
    let selects = document.getElementsByTagName('select');
    let buttons = document.getElementsByTagName('button');
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("change", aktualisiereEingabe);
    }
    for (let i = 0; i < selects.length; i++) {
        selects[i].addEventListener("change", aktualisiereEingabe);
//        console.log(selects[i]);
    }
    for (let i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener("click", zeigeAuswahl);
    }

    function zeigeAuswahl() {
        // pulldownmenu vom Server holen
        let buttonElement = this;
        let pk = this.parentElement.parentElement.firstChild.innerHTML;
        //alert(pk);
        let antwort;
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                antwort = this.responseText;
                //alert('antwort: ' + antwort);
                let newElement = buttonElement.parentElement;
                newElement.innerHTML = antwort;
                newElement.parentElement.addEventListener("change", testwas);
                //@todo Funktionalität: Ändern und Anzeige als Knopf implenetieren
            }
        };
        xhttp.open("POST", "ajax.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        function Feld(pk, action){
            this.pk = pk;
            this.action = action;
        }
        let anforderung = new Feld(pk, "holePulldown");
        alert(JSON.stringify(anforderung));
        xhttp.send('ajax=' + JSON.stringify(anforderung));
    }
    // Funktion zum testen
    function testwas() {
        alert('123');
    }


    function aktualisiereEingabe() {
        let pk = parseInt(this.parentElement.parentElement.firstChild.innerHTML);
        let spalteNr = this.parentNode.cellIndex;
        let inputfeld = function (pk, wert, spalteNr) {
            this.pk = pk;
            this.wert = wert;
            this.spalteNr = spalteNr;
        }
        let feld = new inputfeld(pk, this.value, spalteNr);

        let antwort;
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                antwort = this.responseText;
                // alert('antwort: ' + antwort)
            }
        };
        xhttp.open("POST", "ajax.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//        alert('ajax=' + JSON.stringify(feld));
        xhttp.send('ajax=' + JSON.stringify(feld));
    }
</script>
</html>
