<?php
function WrapLetter($infoLetter)
{
    //$sign = '*';
    $sign = '';
	//return $sign.$infoLetter;
	return '['.$infoLetter.']';
};
function WrapNumberWhole($infoNumber)
{
    //$sign = '*';
    $sign = '';
	return $sign.$infoNumber;
};
function WrapNumberSpell($numberToWrap)
{
    $return_str = '';


    $num_for_str = (string)$numberToWrap;
    
    $num_negative = (strlen(preg_replace ( "/^(-?)[0-9]+$/" , "$1" , $numberToWrap )) > 0);
    $num_for_str = preg_replace ( "/^-?([0-9]+)$/" , "$1" , $numberToWrap );

    $num_for_int_str = (string)$num_for_str;
    $sign = ''; $sep = '';

    $num_for_int_str = $sign.$sep.implode($sep, str_split($num_for_int_str));

    $return_str = ( $num_negative === true ? "minus $num_for_int_str" : $num_for_int_str );
    
	return $return_str;
};
function WrapNumberRead($numberToWrap)
{
    $return_str = '';


    $num_for_str = (string)$numberToWrap;
    $num_for_int = +$numberToWrap;
    
    $num_negative = (strlen(preg_replace ( "/^(-?)[0-9]+$/" , "$1" , $numberToWrap )) > 0);
    $num_for_str = preg_replace ( "/^-?([0-9]+)$/" , "$1" , $numberToWrap );
    $num_for_int = preg_replace ( "/^-?0*([0-9]+)$/" , "$1" , $numberToWrap );

    $num_for_int_str = (string)$num_for_int;
    $sign = '*'; $sep = '';

    $num_for_int_str = $sign.$sep.implode($sep, str_split($num_for_int_str));

    $return_str = ( $num_negative === true ? "minus $num_for_int_str" : $num_for_int_str );
    
	return $return_str;
};
function strtolower_utf8($string){
  $convert_to = array(
    "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
    "v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï",
    "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "?", "?", "?", "?", "?", "?", "?", "?",
    "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?",
    "?", "?", "?", "?"
  );
  $convert_from = array(
    "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
    "V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï",
    "Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "?", "?", "?", "?", "?", "?", "?", "?",
    "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?",
    "?", "?", "?", "?"
  );
  return strtolower(str_replace($convert_from, $convert_to, $string));
}
function strtoupper_utf8($string){
  $convert_from = array(
    'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u',
    'v', 'w', 'x', 'y', 'z', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï',
    'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', '?', '?', '?', '?', '?', '?', '?', '?',
    '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?',
    '?', '?', '?', '?'
  );
  $convert_to = array(
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U',
    'V', 'W', 'X', 'Y', 'Z', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï',
    'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', '?', '?', '?', '?', '?', '?', '?', '?',
    '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?',
    '?', '?', '?', '?'
  );

  return strtoupper(str_replace($convert_from, $convert_to, $string));
}
?>