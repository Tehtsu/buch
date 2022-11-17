<?php
function buildHTMLTable(array $ausgabe, array $tablehead = null): string
{
    $html = '';
    if (!empty($ausgabe)) {
        $columnsNo = count($ausgabe[0]);
        if ((isset($tablehead)) && ($columnsNo != count($tablehead))) {
            echo 'Die Anzahl der Spalten im Tabellenkopf stimmt nicht mit der Spaltenzahl des Tabellenkörpers überein!';
        } else {
            $html = '<table>';
            if (isset($tablehead)) {
                $html .= '<thead>';
                for ($i = 0; $i < count($tablehead); $i++) {
                    $html .= '<th>';
                    $html .= $tablehead[$i];
                    $html .= '</th>';
                }
                $html .= '</thead>';
            }
            $html .= '<tbody>';
            for ($j = 0; $j < count($ausgabe); $j++) {
                $html .= '<tr>';
                foreach ($ausgabe[$j] as $item => $value) {
                    $html .= '<td>';
                    $html .= $ausgabe[$j][$item];
                    $html .= '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        }
    } else {
        $html = '<table>';
        if (isset($tablehead)) {
            $html .= '<thead>';
            for ($i = 0; $i < count($tablehead); $i++) {
                $html .= '<th>';
                $html .= $tablehead[$i];
                $html .= '</th>';
            }
            $html .= '</thead>';
        }
        $html .= '</table>';
        echo '<h1 style="text-decoration: none">Noch keine Einträge vorhanden</h1>';
    }
    return $html;
}

/*
 * @param $options array enthält Objekte mit Schlüsseln: id, name
 * @param $multi bei true multiselect, sonst false
 * @param $name ist Übergabename für POST
 * @param $preselected ist array, welche(r) Wert(e) vorausgewählt ist/sind
 */
function buildHTMLInputSelect(array $options, bool $multi, string $name, array $preselected = null): string
{
    $html = '';
    $muSel = $multi ? ' multiple' : '';
    $html .= "<select name=$name$muSel required>";
    if ($preselected == null) {
        $html .= "<option value='' disabled selected hidden>Wähle einen Ort aus</option>";
    }
    for ($i = 0; $i < count($options); $i++) {
        for ($j = 0; $j < count($preselected); $j++){
            if($preselected[$j] === $options[$i]->getId()){
                $selected = ' selected';
                break;
            } else{
                $selected = '';
            }
            //$selected = ($preselected[$j] === $options[$i]->getId()) ? ' selected' : '';
        }
        $html .= "<option value='" . $options[$i]->getId() . "' $selected>" . $options[$i]->getName() . "</option>";
    }
    $html .= "</select>";
    return $html;
}