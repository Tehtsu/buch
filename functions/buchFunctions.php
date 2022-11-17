<?php


function getSpalte($feldObj): string
{
    $spaltennamen = [
        1 => "titel",
        2 => "zimmer_id",
        3 => "verlag_id",
        4 => "autoren"
    ];
    return $spaltennamen[$feldObj->spalteNr];
}