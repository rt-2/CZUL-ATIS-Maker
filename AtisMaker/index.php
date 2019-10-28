<?php
// Euroscope Compatibility
header('Content-Type: text/plain; charset=WINDOWS-1252');
//header('Content-Type: text/plain; charset=ISO-8859-1');
//header('Content-Type: text/plain; charset=UTF-8');

require_once('./includes/atis.class.inc.php');
require_once('./includes/notam.class.inc.php');
require_once('./includes/metar.class.inc.php');
require_once('./includes/functions.inc.php');

include_once('./resources/metars.lib.inc.php');
include_once('./resources/airports.lib.inc.php');
include_once('./resources/notams.lib.inc.php');



?>
<?php

define('DEBUG', false);

$metarMatches = [];
$metar = $_GET['metar'];
$metarMainParts = [];
$metarMainPartStrings = [];

$metar_regex = '';
foreach(MetarMainPart::$allMetarMainPartsByNames as $name => $metarMainPart_obj)
{
	
	$metar_regex .= $metarMainPart_obj->regex.'\s?';
}
$metar_regex = '/'.$metar_regex.'/mu';


/*
echo $metar_regex;

$main_local_regex.'\s?'.$main_wind_regex.'\s?'.$main_visibility_regex.'\s?'.$main_precipitation_regex.'\s?'.$main_cloud_regex.'\s?'.$main_temp_regex.'\s?'.$main_altimeter_regex.'\s?RMK\s'.$main_remark_regex;
$winds_regex = '/(?P<icao>\w{4})\s(?P<time_day>\d{2})(?P<time_zulu>\d{4})Z\s(?P<winds>\d{5}(?:G\d{2})?KT)\s(?P<visibility>\d{1,2})SM\s(?P<precipitations>(?:(?:(?:\-|\+)?[A-Z]{2})\s){0,})(?P<clouds>(?:(?:FEW|BKN|SCT|OVC)\d{3}\s){0,})(?P<temperature>\d{2})\/(?P<dewPoint>\d{2})\sA(?P<altimeter>\d{4}) RMK (?P<remarks>[[:ascii:]]*)';
*/
//(?P<icao>\w{4})\s(?P<time_day>\d{2})(?P<time_zulu>\d{4})Z

if(DEBUG)
{
	echo "\n\n";
	echo "Parameters :\n";
	echo json_encode($_GET);
	echo "\n\n";
	echo "Metar :\n";
	echo $metar;
	echo "\n\n";
	echo "Main Regex :\n";
	echo $metar_regex;
	echo "\n\n";
}

preg_match_all($metar_regex, $metar, $matches);
foreach(MetarMainPart::$allMetarMainPartsByNames as $name => $metarMainPart_obj)
{
	$metarMainPart_obj->SetResultString($matches[$name][0]);
}



$notamsDemanded = isset($_GET['notams']);



MetarMainPart::$allMetarMainPartsByNames['local']->addSubPart(['airport_icao','issue_date','issue_time'], '/^(?P<airport_icao>\w{4})\s(?P<issue_date>\d{2})(?P<issue_time>\d{4})Z$/');
MetarMainPart::$allMetarMainPartsByNames['winds']->addSubPart(['wind_degree','wind_speed','wind_variaton'], '/^(?P<wind_degree>\w{3})(?P<wind_speed>\w{2}(?:G\w{2})?KT)(?:\s(?P<wind_variaton>\d{3}V\d{3}))?$/');



if(DEBUG)
{
	foreach(MetarMainPart::$allMetarMainPartsByNames as $name => $metarMainPart_obj)
	{
		//echo "\n\n";
		echo $name.' : ';
		echo '   ';
		echo MetarMainPart::$allMetarMainPartsByNames[$name]->result_str;
		echo "\n";
		foreach($metarMainPart_obj->subPartsByNames as $name => $metarPart_obj)
		{
			echo '   '.$name.' : ';
			echo $metarPart_obj->result_str;
			echo "\n";
			
		}
		
	}
}
	//echo "\n\n";
	//echo "\n\n";
	//echo json_encode($matches);

$airportICAO = MetarMainPart::$allMetarMainPartsByNames['local']->subPartsByNames['airport_icao']->result_str;
function GetAirportNameString($icao, $lang)
{
	$return_value = '['.$icao.']';
	if(@strlen($GLOBALS['cityNames_array'][$icao][$lang]) > 0)
	{
		$return_value = $GLOBALS['cityNames_array'][$icao][$lang];
	}
	return $return_value;
}

//var_dump(MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_degree']->result_str);

//var_dump(MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_variaton']->result_str);
//var_dump(MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_speed']->result_str);

$infoLetter = $_GET['info'];
$infoZuluTime = str_replace('Z', ' Zulu', MetarMainPart::$allMetarMainPartsByNames['local']->subPartsByNames['issue_time']->result_str);
$windDirection = MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_degree']->result_str;
$windVariaton = MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_variaton']->result_str;
$windVariatonList = explode('V', $windVariaton);
$windSpeeds = MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_speed']->result_str;

preg_match_all('/(?P<speed_kts>(?<=^)\d\d)(?:G(?P<speed_gust>(?<=G)\d\d))?KT$/', $windSpeeds, $speedMatches);
$windSpeed_kts = +$speedMatches['speed_kts'][0];
$windSpeed_gust = +$speedMatches['speed_gust'][0];

$visibility = str_replace('SM', '', MetarMainPart::$allMetarMainPartsByNames['visibility']->result_str);
$precipitations = MetarMainPart::$allMetarMainPartsByNames['precipitations']->result_str;
$precipitations_array = explode(" ", $precipitations);
$precipitations_segmentArr = [];
$precipitations_segmentStr = '';
foreach($precipitations_array as $precipitation)
{
	$precipitation_intensity = '';
	$precipitation_name = $precipitation;
	$precipitation_descr = $precipitation;
	$precipitation_intensity_str = '';
	$precipitation_descr_str = '';
	$precipitation_name_str = '';
	if(substr($precipitation, 0, 1) === '-' || substr($precipitation, 0, 1) === '+')
	{
		$precipitation_intensity = substr($precipitation, 0, 1);
		$precipitation_name = substr($precipitation, 1, 2);
	}
	
	switch($precipitation_intensity)
	{
		case '-':
			$precipitation_intensity_str = 'light';
			break;
		case '':
			$precipitation_intensity_str = 'moderate';
			break;
		case '+':
			$precipitation_intensity_str = 'heavy';
			break;
	}
	if(strlen($precipitation_name) === 4)
	{
		$precipitation_descr = substr($precipitation_name, 0, 2);
		$precipitation_name = substr($precipitation_name, 2, 2);
	}
	$precipitation_descr_str = METAR_PRECIP_DESCR_NAMES[$precipitation_descr]['en'];
	$precipitation_name_str = METAR_PRECIP_NAMES[$precipitation_name]['en'];
	if(strlen($precipitation_name_str) > 0)
	{
		$precipitations_segmentArr[] = $precipitation_intensity_str.' '.(( strlen($precipitation_descr_str) > 0) ? $precipitation_descr_str.' ':'').$precipitation_name_str;
	}
}

    $precipitations_segmentStr = implode(" , ", $precipitations_segmentArr);

$clouds = MetarMainPart::$allMetarMainPartsByNames['clouds']->result_str;
$clouds_array = explode(" ", $clouds);
$cloudLayers_segmentArr = [];
$cloudLayers_segmentStr = '';
foreach($clouds_array as $cloudLayer)
{
	$type = substr($cloudLayer, 0, 3);
    //$unit = '(fr)';
    $unit = '';
	$alt = WrapNumberWhole((+substr($cloudLayer, 3, 3)).'00').$unit;
	$retStr = '';

	switch($type)
	{
		case 'FEW':
			$retStr = 'few clouds at '.$alt;
			break;
		case 'SCT':
			$retStr = 'scattered clouds at '.$alt;
			break;
		case 'BKN':
			$retStr = $alt.' broken';
			break;
		case 'OVC':
			$retStr = $alt.' overcast';
			break;
	}
	$cloudLayers_segmentArr[] = $retStr;
}
$cloudLayers_segmentStr = implode(" , ", $cloudLayers_segmentArr);
$temps = MetarMainPart::$allMetarMainPartsByNames['temps']->result_str;
$temps_array = explode("/", str_replace("M", "-", $temps));
$temp_celcius = +preg_replace('((?=0)\d)', '$1', $temps_array[0]);
$temp_dewpoint = +preg_replace('((?=0)\d)', '$1', $temps_array[1]);
$altimeter_hg = substr(MetarMainPart::$allMetarMainPartsByNames['altimeter']->result_str, 1);
$dep_rwys = $_GET['dep'];
$dep_rwys_str = '';
function GetAirportDepRwysString($dep_rwys, $lang)
{
	$dep_rwys_list = [];
	foreach(explode(',', $dep_rwys) as $rwy)
	{
		$dep_rwys_list[] = ($lang === 'fr'? 'piste '.str_replace(['R', 'L'], ['D', 'G'], $rwy): 'runway '.$rwy);
	}
	return implode(($lang === 'fr'? ' et ': ' and '), $dep_rwys_list);
}

$app_rwys = $_GET['arr'];
$app_type = $_GET['apptype'];
$app_rwys_str = '';
function GetAirportAppRwysString($app_rwys, $app_type, $lang)
{
	$app_rwys_list = [];
	foreach(explode(',', $app_rwys) as $rwy)
	{
		$app_rwys_list[] = $app_type.($lang === 'fr'? ' piste '.str_replace(['R', 'L'], ['D', 'G'], $rwy): ' runway '.$rwy);
	}
	return implode(($lang === 'fr'? ' et ': ' and '), $app_rwys_list);
}
if(strtoupper($windDirection) !== 'VRB')
{
    $windDirection += 20;
    $windDirection = ($windDirection > 360)? $windDirection - 360 : $windDirection;
    while(strlen($windDirection) < 3)
    {
	    $windDirection = '0'.$windDirection;
    }
}

if($notamsDemanded)
{
    //Fetch NOTAMs
    $thisArptNotams = [];
    if(in_array($airportICAO, array_keys($GLOBALS['notams_array'])))
    {
	    $thisArptNotams = $GLOBALS['notams_array'][$airportICAO];
    }
}

if(DEBUG)
{
	echo "\n\n";
	echo '== FINAL ATIS ==';
	echo "\n\n";
}




$atsResultEn = New AtisConstructor();
$atsResultFr = New AtisConstructor();



//Build String

$basicInformations = New AtisSectionConstructor();
$basicInformations->addSection(  GetAirportNameString($airportICAO, 'fr').' information '.WrapLetter($infoLetter) );
$basicInformations->addSection( 'météo à '.WrapNumberSpell($infoZuluTime).' Zulu' );
$atsResultFr->addSection( $basicInformations->returnResult() );

$windsEtc = New AtisSectionConstructor();
$windsEtc->addSection( 'vent '. ( $windDirection === 'VRB' ? 'Variable' : WrapNumberSpell($windDirection) ).' à '.WrapNumberWhole($windSpeed_kts).($windSpeed_gust > 0 ? ' rafales Ã Â  '.WrapNumberWhole($windSpeed_gust) : '').(strlen($windVariaton) > 0 ? " variant entre ".$windVariatonList[0].' et '.$windVariatonList[1] : '') );
$windsEtc->addSection( 'visibilité '.WrapNumberSpell($visibility) );
$atsResultFr->addSection( $windsEtc->returnResult() );

$precip = New AtisSectionConstructor();
$precip->addSection( $precipitations_segmentStr );
$atsResultFr->addSection( $precip->returnResult() );

$clouds = New AtisSectionConstructor();
if (strlen($cloudLayers_segmentStr) > 0 ){
    $clouds->addSection( $cloudLayers_segmentStr );
} else {
    $clouds->addSection( 'Sky clear');
}
$atsResultFr->addSection( $clouds->returnResult() );

$baroEtc = New AtisSectionConstructor();
$baroEtc->addSection( 'température '.WrapNumberSpell($temp_celcius) );
$baroEtc->addSection( 'point de rosée '.WrapNumberSpell($temp_dewpoint) );
$baroEtc->addSection( 'altimètre '.WrapNumberSpell($altimeter_hg) );
$atsResultFr->addSection( $baroEtc->returnResult() );

$procedures = New AtisSectionConstructor();
$procedures->addSection( 'approches IFR '.GetAirportAppRwysString($app_rwys, $app_type, 'fr') );
$procedures->addSection( 'departs '.GetAirportDepRwysString($dep_rwys, 'fr') );
$atsResultFr->addSection( $procedures->returnResult() );

if($notamsDemanded)
{
    $notams = New AtisSectionConstructor();
    foreach($thisArptNotams as $notam)
    {
        $this_notam_text =  $notam['fr'];
    
        $this_notam_text = NotamTextAdjustments::AdjustAndReturnText($this_notam_text);

        //var_dump($this_notam_text);

        //var_dump($this_notam_text);
        $this_notam_text = strtolower($this_notam_text);
    
	    $notams->addSection( $this_notam_text );
    }
    $atsResultFr->addSection( $notams->returnResult() );

}

$ending = New AtisSectionConstructor();
$ending->addSection( "Avisez l'ATC que vous avez l'informaton ".WrapLetter($infoLetter) );
$atsResultFr->addSection( $ending->returnResult() );




$basicInformations = New AtisSectionConstructor();
$basicInformations->addSection( GetAirportNameString($airportICAO, 'en').' information '.WrapLetter($infoLetter) );
$basicInformations->addSection( 'weather at '.WrapNumberSpell($infoZuluTime).' Zulu' );
$atsResultEn->addSection( $basicInformations->returnResult() );

$windsEtc = New AtisSectionConstructor();
$windsEtc->addSection( 'wind '. ( $windDirection === 'VRB' ? 'Variable' : WrapNumberSpell($windDirection) ).' at '.WrapNumberWhole($windSpeed_kts).($windSpeed_gust > 0 ? ' , gusting '.WrapNumberWhole($windSpeed_gust) : '').(strlen($windVariaton) > 0 ? " , varying between ".$windVariatonList[0].' and '.$windVariatonList[1] : '') );
$windsEtc->addSection( 'visibility '.WrapNumberSpell($visibility) );
$atsResultEn->addSection( $windsEtc->returnResult() );

$precip = New AtisSectionConstructor();
$precip->addSection( $precipitations_segmentStr );
$atsResultEn->addSection( $precip->returnResult() );

$clouds = New AtisSectionConstructor();
if (strlen($cloudLayers_segmentStr) > 0 ){
    $clouds->addSection( $cloudLayers_segmentStr );
} else {
    $clouds->addSection( 'Sky clear');
}
$atsResultEn->addSection( $clouds->returnResult() );

$baroEtc = New AtisSectionConstructor();
$baroEtc->addSection( 'temperature '.WrapNumberSpell($temp_celcius) );
$baroEtc->addSection( 'dew point '.WrapNumberSpell($temp_dewpoint) );
$baroEtc->addSection( 'altimeter '.WrapNumberSpell($altimeter_hg) );
$atsResultEn->addSection( $baroEtc->returnResult() );

$procedures = New AtisSectionConstructor();
$procedures->addSection( 'IFR approach '.GetAirportAppRwysString($app_rwys, $app_type, 'en') );
$procedures->addSection( 'departures '.GetAirportDepRwysString($dep_rwys, 'en') );
$atsResultEn->addSection( $procedures->returnResult() );


if($notamsDemanded)
{

    $notams = New AtisSectionConstructor();
    foreach($thisArptNotams as $notam)
    {
        $this_notam_text =  $notam['en'];
    
        //var_dump($this_notam_text);
        $this_notam_text = NotamTextAdjustments::AdjustAndReturnText($this_notam_text);

        //var_dump($this_notam_text);
        $this_notam_text = strtolower($this_notam_text);
    
                //echo '<br><br>'."\n\n";
                //echo '<br><br>'."\n\n";
                //echo '<br><br>'."\n\n";

    
        //$this_notam_text[0] = strtoupper($this_notam_text[0]);

	    $notams->addSection( $this_notam_text );
    }

    $atsResultEn->addSection( $notams->returnResult() );

}

//var_dump($atsResult);
$ending = New AtisSectionConstructor();
$ending->addSection( "Advise ATC that you have information ".WrapLetter($infoLetter) );
$atsResultEn->addSection( $ending->returnResult() );






/*
$windDirection = +$metarMatches['wind_dir'][0];
$windDirection += 20;
$windDirection = ($windDirection > 360)? $windDirection - 360 : $windDirection;

$outputEnglishText = $metarMatches['icao'][0].' information '.$_GET['info'][0].', ';
$outputEnglishText .= $metarMatches['winds'][0].', ';
//$outputEnglishText .= 'wind '.$windDirection.' at '.$metarMatches['wind_kts'][0].', ';
$outputEnglishText .= 'visibility '.$metarMatches['visibility'][0].' miles, ';
$outputEnglishText .= $metarMatches['precipitations'][0].', ';
$outputEnglishText .= $metarMatches['clouds'][0].', ';

//$outputEnglishText = str_replace(["\r\n","\n","\r"], "", $outputText);
//$outputEnglishText = str_replace(['CYUL','CYOW'], ['Montreal','Ottawa'], $outputText);
*/

//$outputEnglishText = $outputEnglishText.' A B C D E F G H J K L M N O P Q R S T U V W X Y Z ';
//$outputEnglishText = $outputEnglishText;

$search = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
$replace = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');


//var_dump($atsResult);

$endString = '';

$endString .= "\r\t\t".'(('."\r".$atsResultFr->returnResult()."\t\t".'))'."\r\r";
$endString .=  iconv('WINDOWS-1252', 'UTF-8//TRANSLIT',str_replace($search, $replace,  $atsResultEn->returnResult()));


//echo  $atsResultEn->returnResult());


$endString = preg_replace ( '/(?<=\W|^)(\d{2}[R|D|L|G|C]?)\/(\d{2}[R|D|L|G|C]?)(?=\W|$)/' , "$1–$2" , $endString);

$endString = iconv('UTF-8', 'WINDOWS-1252//TRANSLIT', $endString);

//echo "\t\r\t";
//echo $outputFrenchText;
echo $endString;


?>