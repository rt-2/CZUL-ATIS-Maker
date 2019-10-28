<?php
function WrapLetter($infoLetter)
{
    //$sign = '*';
    $sign = '';
	return $sign.$infoLetter;
};
function WrapNumberWhole($infoNumber)
{
    //$sign = '*';
    $sign = '';
	return $sign.$infoNumber;
};
function WrapNumberSpell($infoNumber)
{
    $return_str = '';
    $num_for_str_results = (string)$infoNumber;
    $num_for_str_tests = preg_replace ( "/^0*(?=[0-9]+)/" , '' , $num_for_str_results );
    $num_for_int_tests = +$infoNumber;
    $num_negative = ($num_for_int_tests < 0)? true : false;
    if((string)+$num_for_str_tests === (string)$num_for_str_tests)
    {
        //$sep = "\t"; $sign = '*';
        $sep = "\r"; $sign = '';
        $length = strlen($num_for_str_results);
        $return_str .= $sep;
        for ($i=0; $i < $length; $i++) {
            $return_str .= $sign.+$num_for_str_results[$i].$sep;
        }
        if($num_negative)
        {
            $return_str= 'minus '.$return_str;
        }
    }
    else
    {
    
	    $return_str = $num_for_str_results;
    }
	return $return_str;
};
?>