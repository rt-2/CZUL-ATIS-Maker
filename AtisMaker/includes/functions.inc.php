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
?>