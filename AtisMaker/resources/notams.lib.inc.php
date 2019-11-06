<?php



foreach($GLOBALS['phonetic_alphabet'] as $letter => $sound)
{
    New NotamTextAdjustments($letter , WrapLetter($sound));
}

New NotamTextAdjustments("(?:(?<month1>[A-Z]{3}\s)(?:(?<day1>\d\d\s)))?(?<time1>\d{4})-(?:(?<month2>[A-Z]{3}\s)(?<day2>\d\d\s))?(?<time2>\d{4})", "$1$2$3Z TO $4$5$6Z");
New NotamTextAdjustments('(?<=[A-Z]{3}\s)(\d1)\s\d{4}Z', "$1ST");
New NotamTextAdjustments('(?<=[A-Z]{3}\s)(\d2)\s\d{4}Z', "$1ND");
New NotamTextAdjustments('(?<=[A-Z]{3}\s)(\d3)\s\d{4}Z', "$1RD");
New NotamTextAdjustments('(?<=[A-Z]{3}\s)(\d\d)\s\d{4}Z', "$1TH");
New NotamTextAdjustments('JAN', 'JANUARY');
New NotamTextAdjustments('FEB', 'FEBRUARY');
New NotamTextAdjustments('MAR', 'MARCH');
New NotamTextAdjustments('APR', 'APRIL');
//New NotamTextAdjustments('MAY', 'MAY');
New NotamTextAdjustments('JUN', 'JUNE');
New NotamTextAdjustments('JUL', 'JULY');
New NotamTextAdjustments('AUG', 'AUGUST');
New NotamTextAdjustments('SEP', 'SEPTEMBER');
New NotamTextAdjustments('OCT', 'OCTOBER');
New NotamTextAdjustments('NOV', 'NOVEMBER');
New NotamTextAdjustments('DEC', 'DECEMBER');

New NotamTextAdjustments('TWY', 'TAXIWAY');
New NotamTextAdjustments('FM', 'FROM');
New NotamTextAdjustments('RWY', 'RUNWAY');
New NotamTextAdjustments('BTN', 'BETWEEN');
New NotamTextAdjustments('AVBL', 'AVAILABLE');
New NotamTextAdjustments('CLSD', 'CLOSED');

New NotamTextTranslations('([A-Z]{3,9})\s(\d\d)(?:ST|ND|RD|TH)(?=(\s\d{4}Z|\sTO\s|\s[A-Z]{3,9}\s|$))', '$2 $1');
New NotamTextTranslations('JANUARY', 'JANVIER');
New NotamTextTranslations('FEBRUARY', 'FÉVRIER');
New NotamTextTranslations('MARCH', 'MARS');
New NotamTextTranslations('APRIL', 'AVRIL');
New NotamTextTranslations('MAY', 'MAI');
New NotamTextTranslations('JUNE', 'JUIN');
New NotamTextTranslations('JULY', 'JUILLET');
New NotamTextTranslations('AUGUST', 'AOUT');
New NotamTextTranslations('SEPTEMBER', 'SEPTEMBRE');
New NotamTextTranslations('OCTOBER', 'OCTOBRE');
New NotamTextTranslations('NOVEMBER', 'NOVEMBRE');
New NotamTextTranslations('DECEMBER', 'DÉCEMBRE');

New NotamTextTranslations('CLOSED', 'FERMÉ');
New NotamTextTranslations('AVAILABLE', 'DISPONIBLE');
New NotamTextTranslations('DAILY', 'QUOTIDIENNEMENT');
New NotamTextTranslations('RUNWAY', 'PISTE');
New NotamTextTranslations('AS', 'COMME');
New NotamTextTranslations('TO', 'À');
New NotamTextTranslations('BETWEEN', 'ENTRE');
New NotamTextTranslations('AND', 'ET');
New NotamTextTranslations('FROM', 'DE');


$tmp_fr_trans = str_replace('CLOSED','FERMÉ', $tmp_fr_trans);
$tmp_fr_trans = str_replace('BTN','ENTRE', $tmp_fr_trans);
$tmp_fr_trans = str_replace('AVBL','DISPONIBLE', $tmp_fr_trans);
$tmp_fr_trans = str_replace('RWY','PISTE', $tmp_fr_trans);
$tmp_fr_trans = str_replace('FROM','DE', $tmp_fr_trans);
$tmp_fr_trans = str_replace('TWY','TAXIWAY', $tmp_fr_trans);

?>