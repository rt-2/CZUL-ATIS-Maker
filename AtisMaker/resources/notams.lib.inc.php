<?php



foreach($GLOBALS['phonetic_alphabet'] as $letter => $sound)
{
    New NotamTextAdjustments($letter , WrapLetter($sound));
}

New NotamTextAdjustments('TWY', 'TAXIWAY');
New NotamTextAdjustments('FM', 'FROM');
New NotamTextAdjustments('RWY', 'RUNWAY');
New NotamTextAdjustments('BTN', 'BETWEEN');
New NotamTextAdjustments('AVBL', 'AVAILABLE');
New NotamTextAdjustments('CLSD', 'CLOSED');

New NotamTextTranslations('CLSD', 'FERMÉ');
New NotamTextTranslations('AVBL', 'DISPONIBLE');
New NotamTextTranslations('DAILY', 'QUOTIDIENNEMENT');
New NotamTextTranslations('RWY', 'PISTE');
New NotamTextTranslations('AS', 'COMME');
New NotamTextTranslations('BTN', 'ENTRE');
New NotamTextTranslations('AND', 'ET');
New NotamTextTranslations('FROM', 'DE');


$tmp_fr_trans = str_replace('CLOSED','FERMÉ', $tmp_fr_trans);
$tmp_fr_trans = str_replace('BTN','ENTRE', $tmp_fr_trans);
$tmp_fr_trans = str_replace('AVBL','DISPONIBLE', $tmp_fr_trans);
$tmp_fr_trans = str_replace('RWY','PISTE', $tmp_fr_trans);
$tmp_fr_trans = str_replace('FROM','DE', $tmp_fr_trans);
$tmp_fr_trans = str_replace('TWY','TAXIWAY', $tmp_fr_trans);

?>