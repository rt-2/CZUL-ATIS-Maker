<?php



foreach($GLOBALS['phonetic_alphabet'] as $letter => $sound)
{
    New NotamTextAdjustments($letter , $sound);
}

New NotamTextAdjustments('TWY', 'TAXIWAY');
New NotamTextAdjustments('FM', 'FROM');
New NotamTextAdjustments('RWY', 'RUNWAY');
New NotamTextAdjustments('BTN', 'BETWEEN');
New NotamTextAdjustments('AVBL', 'AVAILABLE');
New NotamTextAdjustments('CLSD', 'CLOSED');

$tmp_fr_trans = str_replace('CLOSED','FERMÉ', $tmp_fr_trans);
$tmp_fr_trans = str_replace('BETWEEN','ENTRE', $tmp_fr_trans);
$tmp_fr_trans = str_replace('AVAILABLE','DISPONIBLE', $tmp_fr_trans);
$tmp_fr_trans = str_replace('RUNWAY','PISTE', $tmp_fr_trans);
$tmp_fr_trans = str_replace('FROM','DE', $tmp_fr_trans);
$tmp_fr_trans = str_replace('TAXIWAY','TAXIWAY', $tmp_fr_trans);

?>