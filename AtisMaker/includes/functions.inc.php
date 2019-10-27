<?php
function WrapLetter($infoLetter)
{
	return '*'.$infoLetter;
};
function WrapNumberWhole($infoNumber)
{
	return '*'.$infoNumber;
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
        $sep = "\t"; $sign = '*';
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
function GetPrecipitationDescriptionStrings($precipitation_descr)
{
	$precipitation_descr_strings = null;


	switch($precipitation_descr)
	{
		case 'BC':
			$precipitation_descr_strings = [
				'en' => 'patches',
				'fr' => '',
			];
			break;
		case 'BL':
			$precipitation_descr_strings = [
				'en' => 'blowing',
				'fr' => '',
			];
			break;
		case 'DR':
			$precipitation_descr_strings = [
				'en' => 'low drifting',
				'fr' => '',
			];
			break;
		case 'FZ':
			$precipitation_descr_strings = [
				'en' => 'freezing',
				'fr' => '',
			];
			break;
		case 'MI':
			$precipitation_descr_strings = [
				'en' => 'shallow',
				'fr' => '',
			];
			break;
		case 'PR':
			$precipitation_descr_strings = [
				'en' => 'partial',
				'fr' => '',
			];
			break;
		case 'SH':
			$precipitation_descr_strings = [
				'en' => 'shower',
				'fr' => '',
			];
			break;
		case 'TS':
			$precipitation_descr_strings = [
				'en' => 'thunderstorm',
				'fr' => '',
			];
			break;
	}
	return $precipitation_descr_strings;
}
function GetPrecipitationNameStrings($precipitation_name)
{
	$precipitation_name_str = null;
	switch($precipitation_name)
	{
		case 'DZ':
			$precipitation_name_str = [
				'en' => 'drizzle',
				'fr' => '',
			];
			break;
		case 'GR':
			$precipitation_name_str = [
				'en' => 'hail',
				'fr' => '',
			];
			break;
		case 'GS':
			$precipitation_name_str = [
				'en' => 'snow pellets',
				'fr' => '',
			];
			break;
		case 'IC':
			$precipitation_name_str = [
				'en' => 'ice crystals',
				'fr' => '',
			];
			break;
		case 'PL':
			$precipitation_name_str = [
				'en' => 'ice pellets',
				'fr' => '',
			];
			break;
		case 'RA':
			$precipitation_name_str = [
				'en' => 'rain',
				'fr' => '',
			];
			break;
		case 'SG':
			$precipitation_name_str = [
				'en' => 'snow Grains',
				'fr' => '',
			];
			break;
		case 'SN':
			$precipitation_name_str = [
				'en' => 'snow',
				'fr' => '',
			];
			break;
		case 'UP':
			$precipitation_name_str = [
				'en' => 'unknown precipitation',
				'fr' => '',
			];
			break;
		case 'BR':
			$precipitation_name_str = [
				'en' => 'mist',
				'fr' => '',
			];
			break;
		case 'DU':
			$precipitation_name_str = [
				'en' => 'widespread dust',
				'fr' => '',
			];
			break;
		case 'FG':
			$precipitation_name_str = [
				'en' => 'fog',
				'fr' => '',
			];
			break;
		case 'FU':
			$precipitation_name_str = [
				'en' => 'smoke',
				'fr' => '',
			];
			break;
		case 'HZ':
			$precipitation_name_str = [
				'en' => 'haze',
				'fr' => '',
			];
			break;
		case 'PY':
			$precipitation_name_str = [
				'en' => 'spray',
				'fr' => '',
			];
			break;
		case 'SA':
			$precipitation_name_str = [
				'en' => 'sand',
				'fr' => '',
			];
			break;
		case 'VA':
			$precipitation_name_str = [
				'en' => 'volcanic ash',
				'fr' => '',
			];
			break;
		case 'DS':
			$precipitation_name_str = [
				'en' => 'dust storm',
				'fr' => '',
			];
			break;
		case 'FC':
			$precipitation_name_str = [
				'en' => 'funnel cloud',
				'fr' => '',
			];
			break;
		case 'PO':
			$precipitation_name_str = [
				'en' => 'well-developed',
				'fr' => '',
			];
			break;
		case 'SQ':
			$precipitation_name_str = [
				'en' => 'squalls',
				'fr' => '',
			];
			break;
		case 'SS':
			$precipitation_name_str = [
				'en' => 'sandstorm',
				'fr' => '',
			];
			break;
	}
	return $precipitation_name_str;
}
?>