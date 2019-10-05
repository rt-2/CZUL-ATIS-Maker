<?php
//header('Content-Type: text/plain; charset=ascii');
//header('Content-Type: text/plain; charset=utf-8');
//header('Content-Type: text/plain; charset=ansi');
header('Content-Type: text/plain; charset=ISO-8859-1');

require_once('./includes/class.metarPart.inc.php');
require_once('./includes/functions.inc.php');


?>
<?php

define('DEBUG', false);

$GLOBALS['cityNames_array'] = [
	'CYUL' => [
		'en' => 'Montreal International Airport',
		'fr' => 'Aéroport International de Montréal',
	],
	'CYQB' => [
		'en' => 'Québec International Airport',
		'fr' => 'Aéroport International de Québec',
	],
	'CYOW' => [
		'en' => 'Ottawa International Airport',
		'fr' => 'Aéroport International d\'Ottawa',
	],
];
$GLOBALS['notams_array'] = [
	'CYUL' => [
		191072 => [
			'en' => 'Taxiway '. WrapLetter('E') .' closed between runway 06L/24R and taxiway '. WrapLetter('B'),
			'fr' => 'Taxiway '. WrapLetter('E') .' fermé entre la piste 06G/24D et le taxiway '. WrapLetter('B'),
		],
		191510 => [
			"en" => "First ".WrapNumber("2948")."ft of runway 06L closed",
			"fr" => "Premier ".WrapNumber("2948")."ft de la piste 06G fermé",
		],
		191538 => [
			"en" => "Runway 10/28 closed, available as taxiway",
			"fr" => "Piste 10/28 fermé, disponble comme taxiway",
		],
	],
	"CYHU" => [
		190430 => [
			"en" => "Runway 10/28 closed, available as taxiway",
			"fr" => "Piste 10/28 fermé, disponble comme taxiway",
		],
	],
	"CYMX" => [
		190459 => [
			"en" => "Runway 11/29 closed between sunset and sunrise",
			"fr" => "Piste 11/29 fermé entre le crépuscule et soir et le crépuscule du matin",
		],
		190465 => [
			"en" => "Taxiway ".WrapLetter("A")." closed between taxiway ".WrapLetter("H")." and holding bay 11",
			"fr" => "Taxiway ".WrapLetter("A")." fermé entre le taxiway ".WrapLetter("H")." et l\'air d\'attente piste 11",
		],
	],
];
global $cityNames_array, $notams_array;
//define('NOTAMS_LIST', $notams_array);
//define('CITY_NAMES_BY_ICAO', $cityNames_array);


$metarMatches = [];
$metar = $_GET['metar'];
$metarMainParts = [];
$metarMainPartStrings = [];

(new MetarMainPart())->SetNew('local', '(?<=^)\w{4}\s\d{2}\d{4}Z(?=\s)');
(new MetarMainPart())->SetNew('winds', '(?<=\s)\d{5}(?:G\d{2})?KT(?:\s\d{3}V\d{3})?(?=\s)');
(new MetarMainPart())->SetNew('visibility', '(?<=\s)(?:\d{1,2}|\d\/\d)SM(?=\s)');
(new MetarMainPart())->SetNew('precipitations', '(?<=\s)(?:(?:\-|\+)?(?:[A-Z]{2}){1,3}(?=\s)){0,}(?=\s)', false);
(new MetarMainPart())->SetNew('clouds', '(?<=\s)SKC|(?:\s?(?:FEW|BKN|SCT|OVC)\d{3}){0,}(?=\s)');
(new MetarMainPart())->SetNew('temps', '(?<=\s)M?\d\d\/M?\d\d(?=\s)');
(new MetarMainPart())->SetNew('altimeter', '(?<=\s)A\d{4}(?=\s)');
(new MetarMainPart())->SetNew('remarks', '(?<=\s)RMK [[:ascii:]]*');

$metar_regex = '';
foreach(MetarMainPart::$allMetarMainPartsByNames as $name => $metarMainPart_obj)
{
	
	$metar_regex .= $metarMainPart_obj->regex.'\s?';
}

/*

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
	preg_match_all('/'.$metar_regex.'/', $metar, $matches);

foreach(MetarMainPart::$allMetarMainPartsByNames as $name => $metarMainPart_obj)
{
	$metarMainPart_obj->SetResultString($matches[$name][0]);
	
}




MetarMainPart::$allMetarMainPartsByNames['local']->addSubPart(['airport_icao','issue_date','issue_time'], '^(?P<airport_icao>\w{4})\s(?P<issue_date>\d{2})(?P<issue_time>\d{4}Z)$');
MetarMainPart::$allMetarMainPartsByNames['winds']->addSubPart(['wind_degree','wind_speed','wind_variaton'], '^(?P<wind_degree>\w{3})(?P<wind_speed>\w{2}(?:G\w{2})?KT)(?:\s(?P<wind_variaton>\d{3}V\d{3}))?$');



if(DEBUG)
{
	foreach(MetarMainPart::$allMetarMainPartsByNames as $name => $metarMainPart_obj)
	{
		echo "\n\n";
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
$infoLetter = $_GET['info'];
$infoZuluTime = str_replace('Z', '*Z', MetarMainPart::$allMetarMainPartsByNames['local']->subPartsByNames['issue_time']->result_str);
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
	$precipitation_descr_str = GetPrecipitationDescriptionStrings($precipitation_descr)['en'];
	$precipitation_name_str = GetPrecipitationNameStrings($precipitation_name)['en'];
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
	$alt = WrapNumber((+substr($cloudLayer, 3, 3)).'00');
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
$temp_celcius = preg_replace('((?=0)\d)', '$1', $temps_array[0]);
$temp_dewpoint = preg_replace('((?=0)\d)', '$1', $temps_array[1]);
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
$windDirection = +$metarMatches['wind_dir'][0];
$windDirection += 20;
$windDirection = ($windDirection > 360)? $windDirection - 360 : $windDirection;
while(strlen($windDirection) < 3)
{
	$windDirection = '0'.$windDirection;
}
$thisArptNotams = [];
if(in_array($airportICAO, array_keys($GLOBALS['notams_array'])))
{
	$thisArptNotams = $GLOBALS['notams_array'][$airportICAO];
}
if(DEBUG)
{
	echo "\n\n";
	echo '== FINAL ATIS ==';
	echo "\n\n";
}
$outputEnglishText = GetAirportNameString($airportICAO, 'en').' information '.WrapLetter($infoLetter)." , ";
$outputEnglishText .= 'weather at '.$infoZuluTime.". ";
$outputEnglishText .= 'wind '.$windDirection.' at '.WrapNumber($windSpeed_kts).($windSpeed_gust > 0 ? ' , gusting '.WrapNumber($windSpeed_gust) : '').(strlen($windVariaton) > 0 ? " , varying between ".$windVariatonList[0].' and '.$windVariatonList[1] : '').". ";
$outputEnglishText .= 'visibility '.$visibility;
$outputEnglishText .= (strlen($precipitations_segmentStr) > 0 ? " , ".$precipitations_segmentStr.'. ' : ". ");
$outputEnglishText .= (strlen($cloudLayers_segmentStr) > 0 ? $cloudLayers_segmentStr.' , ' : 'Sky clear , ');
$outputEnglishText .= 'temperature '.WrapNumber(+$temp_celcius)." , dew point ".WrapNumber(+$temp_dewpoint)." , ";
$outputEnglishText .= 'altimeter '.$altimeter_hg.". ";
$outputEnglishText .= 'IFR approach '.GetAirportAppRwysString($app_rwys, $app_type, 'en')." , ";
$outputEnglishText .= 'departures '.GetAirportDepRwysString($dep_rwys, 'en').". ";
foreach($thisArptNotams as $notam)
{
	$outputEnglishText .= $notam['en'].'. '."";
}
$outputEnglishText .= 'Advise ATC that you have information '.WrapLetter($infoLetter).'.';

$outputFrenchText = ''.GetAirportNameString($airportICAO, 'fr').' information '.$infoLetter.'[,] ';
$outputFrenchText .= 'météo à '.$infoZuluTime.', ';
$outputFrenchText .= 'vent '.$windDirection.' à '.WrapNumber($windSpeed_kts).($windSpeed_gust > 0 ? ' rafales à '.WrapNumber($windSpeed_gust) : '').(strlen($windVariaton) > 0 ? ' variant entre '.$windVariatonList[0].' et '.$windVariatonList[1] : '').', ';
$outputFrenchText .= 'visibilité '.$visibility.', ';
$outputFrenchText .= (strlen($precipitations_segmentStr) > 0 ? $precipitations_segmentStr.', ' : '');
$outputFrenchText .= (strlen($cloudLayers_segmentStr) > 0 ? $cloudLayers_segmentStr.', ' : 'Sky clear, ');

$outputFrenchText .= 'température '.+$temp_celcius.', point de rosée '.+$temp_dewpoint.', ';
$outputFrenchText .= 'altimètre '.$altimeter_hg.', '."\r";
$outputFrenchText .= 'approches IFR '.GetAirportAppRwysString($app_rwys, $app_type, 'fr').', ';
$outputFrenchText .= 'departs '.GetAirportDepRwysString($dep_rwys, 'fr').'[.] ';
foreach($thisArptNotams as $notam)
{
	$outputFrenchText .= $notam['fr'].'. '."\r";
}
$outputFrenchText .= 'Avisez l\'ATC que vous avez l\'informaton '.WrapLetter($infoLetter).'.';

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
function getUni($str){
     return json_decode('{"t":"'.$str.'"}')->t;
}
echo $outputEnglishText;


//echo getUni("\u000A").getUni("\u000B").getUni("\u000C").getUni("\u000D").getUni("\u0085").getUni("\u2028").getUni("\u2029");
//echo getUni("\u00ed").getUni("\u000A").getUni("\u000B").getUni("\u000C").getUni("\u000D").getUni("\u0085").getUni("\u2028").getUni("\u2029");
//echo getUni("\u000A").getUni("\u000B").getUni("\u000C").getUni("\u000D").getUni("\u0085").getUni("\u2028").getUni("\u2029");

//echo "\t\r\t";
//echo $outputFrenchText;
?>
