<?php
// Euroscope Compatibility
header('Content-Type: text/plain; charset=WINDOWS-1252');
//header('Content-Type: text/plain; charset=ISO-8859-1');
//header('Content-Type: text/plain; charset=UTF-8');


    require_once(dirname(__FILE__).'/includes/notam.class.inc.php');
    require_once(dirname(__FILE__).'/includes/atis.class.inc.php');
    require_once(dirname(__FILE__).'/includes/metar.class.inc.php');
    require_once(dirname(__FILE__).'/includes/functions.inc.php');
    require_once(dirname(__FILE__).'/includes/curl.class.inc.php');

    require_once(dirname(__FILE__).'/includes/CANotAPI/CANotAPI.inc.php');

    require_once(dirname(__FILE__).'/resources/metars.lib.inc.php');
    require_once(dirname(__FILE__).'/resources/airports.lib.inc.php');
    require_once(dirname(__FILE__).'/resources/fir.data.inc.php');
    
    



$metarMatches = [];
$metar = $_GET['metar'];
$metarMainParts = [];
$metarMainPartStrings = [];

define('DEBUG', isset($_GET['debug']) );
if(DEBUG) "Debug Mode ON.\n\n";


//$metar_regex = '';
//foreach(MetarMainPart::$allMetarMainPartsByNames as $name => $metarMainPart_obj)
//{
	
//    $metar_regex .= $metarMainPart_obj->regex."\s?";
//}
//$metar_regex = "/".$metar_regex."/mu";

//echo "metar_regex";
//echo $metar_regex;
//echo "\n\n";


//$req = new HttpCurl();
//$req->get('http://rt2.czulfir.com/AtisMaker/?apptype=ILS&fr&ntm&metar='.urlencode('CYUL%20291132Z%2034003KT%202%201/2SM%20BCFG%20BKN001%20BKN250%2004/04%20A3018%20RMK%20SF5CI2%20VIS%20S%20W%20N%201/2%20SLP223').'&arr=24L&dep=24L&info=H&arr=24L&dep=24L&info=H');

//echo $req->getBody();
/*
$main_local_regex.'\s?'.$main_wind_regex.'\s?'.$main_visibility_regex.'\s?'.$main_precipitation_regex.'\s?'.$main_cloud_regex.'\s?'.$main_temp_regex.'\s?'.$main_altimeter_regex.'\s?RMK\s'.$main_remark_regex;
$winds_regex = '/(?P<icao>\w{4})\s(?P<time_day>\d{2})(?P<time_zulu>\d{4})Z\s(?P<winds>\d{5}(?:G\d{2})?KT)\s(?P<visibility>\d{1,2})SM\s(?P<precipitations>(?:(?:(?:\-|\+)?[A-Z]{2})\s){0,})(?P<clouds>(?:(?:FEW|BKN|SCT|OVC)\d{3}\s){0,})(?P<temperature>\d{2})\/(?P<dewPoint>\d{2})\sA(?P<altimeter>\d{4}) RMK (?P<remarks>[[:ascii:]]*)';
*/
//(?P<icao>\w{4})\s(?P<time_day>\d{2})(?P<time_zulu>\d{4})Z


global $atisEnabledAirports;
$GLOBALS['ACTIVE_NOTAMS_IDS'] = [];


$notamsDemanded = isset($_GET['ntm']);
$bilingualDemanded = isset($_GET['fr']);
$upperCaseDemanded = isset($_GET['cap']);
$bilingualDemanded = isset($_GET['fr']);
$capitalDemanded = isset($_GET['cap']);
$helpDemanded = isset($_GET['help']);


if(DEBUG)
{
	echo "\n\n";
	echo "$_GET\n";
	echo "Parameters :\n";
	echo json_encode($_GET);
	echo "\n\n";
	echo "Metar :\n";
	echo $metar;
	echo "\n\n";
	echo "Main Regex :\n";
	echo '"'.$metar_regex.'"';
	echo "\n\n";
	echo "Numbers :\n";
	echo "\nallMetarMainPartsByNames :\n";
	echo count($matches);
	echo "\nmatches :\n";
	echo count(MetarMainPart::$allMetarMainPartsByNames);
	echo "\n\n";
}
    //(?:\sRMK\s).*


    //preg_match_all($metar_regex, $metar, $matches);
    $metarMainPart_Infos = [ ];
    foreach(MetarMainPart::$allMetarMainPartsByNames as $name => $metarMainPart_obj)
    {
	    $metarMainPart_Infos[$name] = $metarMainPart_obj->SetResultString($matches[$name][0]);
    }


    foreach(MetarMainPart::$allMetarMainPartsByNames as $name => $metarMainPart_obj)
    {
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






if($helpDemanded)
{
    echo "(( \r\t\t Commands:\n\n\n";
    echo "\t&cap    all caps;\n\n";
    echo "\t&ntm    activate notams;\n\n";
    echo "\t&help    this help;\n\n";
    echo " \t\t\t\t   \n\n))";
    
}

//var_dump($metarMainPart_Infos->issue_time);

$temp_celcius =  MetarMainPart::$allMetarMainPartsByNames['temp']->result_str;
$temp_dewpoint =  MetarMainPart::$allMetarMainPartsByNames['dew']->result_str;
$altimeter_hg = MetarMainPart::$allMetarMainPartsByNames['baro']->result_str;

//if(DEBUG)
//{

//}
	//echo "\n\n";
	//echo "\n\n";
	//echo json_encode($matches);


    
if($notamsDemanded)
{
    //Fetch NOTAMs
    $notamsIds = file('../Notams/activeNotams.data.csv');
    //echo "\nnotamsIds\n";
    //var_dump($notamsIds);
    foreach($notamsIds as $value)
    {
        if(DEBUG) echo "\nvalue\n";
        if(DEBUG) var_dump($value);
        
        $notam_id = trim(preg_replace("/(;.*$)/", '', $value));
        //var_dump($notam_id);
        if(strlen($notam_id) > 0)
        {
            $GLOBALS['ACTIVE_NOTAMS_IDS'][] = $notam_id;
        }
    }
    //echo "\nACTIVE_NOTAMS_IDS\n";
    if(DEBUG) var_dump($airportICAO);
    //
    $GLOBALS['ACTIVE_NOTAMS_IDS'] = CANotAPI_GetNotamsArray($airportICAO, 'CLSD');
    //var_dump($GLOBALS['ACTIVE_NOTAMS_IDS']);
    //var_dump( $GLOBALS['ACTIVE_NOTAMS_IDS']);
}

 //echo "\n\n"; echo "\n\n";
if(DEBUG)
{
	echo "\n\n";
	echo '== FINAL ATIS ==';
	echo "\n\n";
}


function GetAirportNameString($icao, $lang)
{
	$return_value = '['.$icao.']';
	if(@strlen($GLOBALS['cityNames_array'][$icao][$lang]) > 0)
	{
		$return_value = $GLOBALS['cityNames_array'][$icao][$lang];
	}
    //var_dump($return_value);
	//echo "\n\n";
    //var_dump($icao);
	//echo "\n\n";
    //var_dump($MetarMainPart);
	//echo "\n\n";
	return $return_value;
}

$airportICAO = MetarMainPart::$allMetarMainPartsByNames['icao']->result_str;
$infoLetter = strToUpper($_GET['info']);

$infoPhonetic = $GLOBALS['phonetic_alphabet'][$infoLetter];
$infoZuluTime = str_replace('Z', '', MetarMainPart::$allMetarMainPartsByNames['issue_time']->result_str);
    //var_dump($infoZuluTime);
$windDirection = MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_deg']->result_str;
$windVariaton = MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_var']->result_str;
$windSpeeds = MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_speed']->result_str;
$windVariatonList = explode('V', $windVariaton);
$windSpeedVariaton = explode('G', $windSpeeds);


//MetarMainPart::$allMetarMainPartsByNames['icao']->addSubPart(['airport_icao','issue_date','issue_time'], '/^(?<speed_kts>VRB|\d{3})(?<speed_kts>\d{2})(?:G(?<speed_kts>\d{2}))?KT$/');
MetarMainPart::$allMetarMainPartsByNames['winds']->addSubPart(['wind_degree','wind_speed','wind_gust'], '/^(?<speed_kts>vrb|\d{3})(?<wind_speed>\d{2})(?:g(?<wind_gust>\d{2}))?kt$/');


var_dump(MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_degree']->result_str);
(MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_variaton']->result_str);
(MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_speed']->result_str);

"".utf8_decode(json_decode('"\u00A0"')).WrapLetter($infoPhonetic);

//preg_match('/(?<speed_kts>\d{2})G(?<speed_gust>\d{2}?)KT(?:G(?P<speed_gust>\d\d))?KT$/', $windSpeeds, $speedMatches);
$windSpeed_kts = +$speedMatches['speed_kts'][0];
$windSpeed_gust = +$speedMatches['speed_gust'][0];

$visibility = MetarMainPart::$allMetarMainPartsByNames['vis']->result_str;
$precipitations = MetarMainPart::$allMetarMainPartsByNames['precip']->result_str;
$precipitations_array = explode(" ", $precipitations);
$precipitations_segmentArr = [];
$precipitations_segmentStrFr = '';
$precipitations_segmentStrEn = '';
foreach($precipitations_array as $precipitation)
{
	$precipitation_intensity = '';
	$precipitation_name = $precipitation;
	$precipitation_descr = $precipitation;
	$precipitation_intensity_strFr = '';
	$precipitation_intensity_strEn = '';
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
			$precipitation_intensity_strFr = 'faible';
			$precipitation_intensity_strEn = 'light';
			break;
		case '+':
			$precipitation_intensity_strFr = 'fort';
			$precipitation_intensity_strEn = 'heavy';
			break;
        default:
			//$precipitation_intensity_strFr = 'modéré';
			//$precipitation_intensity_strEn = 'moderate';
            break;
	}
	if(strlen($precipitation_name) === 4)
	{
		$precipitation_descr = substr($precipitation_name, 0, 2);
		$precipitation_name = substr($precipitation_name, 2, 2);
	}
	$precipitation_descr_strFr = METAR_PRECIP_DESCR_NAMES[$precipitation_descr]['fr'];
	$precipitation_name_strFr = METAR_PRECIP_NAMES[$precipitation_name]['fr'];
	$precipitation_descr_strEn = METAR_PRECIP_DESCR_NAMES[$precipitation_descr]['en'];
	$precipitation_name_strEn = METAR_PRECIP_NAMES[$precipitation_name]['en'];
	if(strlen($precipitation_name_strFr) > 0)
	{
		$precipitations_segmentArrFr[] = (( strlen($precipitation_intensity_strFr) > 0) ? $precipitation_intensity_strFr.' ':'').(( strlen($precipitation_descr_strFr) > 0) ? $precipitation_descr_strFr.' ':'').$precipitation_name_strFr;
	}
	if(strlen($precipitation_name_strEn) > 0)
	{
		$precipitations_segmentArrEn[] = (( strlen($precipitation_intensity_strEn) > 0) ? $precipitation_intensity_strEn.' ':'').(( strlen($precipitation_descr_strEn) > 0) ? $precipitation_descr_strEn.' ':'').$precipitation_name_strEn;
	}
}

if(count($precipitations_segmentArrFr) > 0) $precipitations_segmentStrFr = implode(" , ", $precipitations_segmentArrFr);

if(count($precipitations_segmentArrEn) > 0) $precipitations_segmentStrEn = implode(" , ", $precipitations_segmentArrEn);

$clouds = MetarMainPart::$allMetarMainPartsByNames['clouds']->result_str;
$clouds_array = explode(" ", $clouds);
$cloudLayers_segmentArr = [];
$cloudLayers_segmentFr = '';
$cloudLayers_segmentEn = '';
foreach($clouds_array as $cloudLayer)
{
	$type = substr($cloudLayer, 0, 3);
    //$unit = '(fr)';
    $unit = '';
	$alt = WrapNumberWhole((+substr($cloudLayer, 3, 3)).'00').$unit;
    
	$retStrFr = '';
	$retStrEn = '';
	switch($type)
	{
		case 'FEW':
			$retStrFr = 'quelques nuages à '.$alt;
			$retStrEn = 'few clouds at '.$alt;
			break;
		case 'SCT':
			$retStrFr = 'épars à '.$alt;
			$retStrEn = 'scattered clouds at '.$alt;
			break;
		case 'BKN':
			$retStrFr = 'fragmenté à '.$alt;
			$retStrEn = $alt.' broken';
			break;
		case 'OVC':
			$retStrFr = $alt.' couvert';
			$retStrEn = $alt.' overcast';
			break;
	}
	$cloudLayers_segmentArrFr[] = $retStrFr;
	$cloudLayers_segmentArrEn[] = $retStrEn;
}
$cloudLayers_segmentStrFr = implode(" , ", $cloudLayers_segmentArrFr);
$cloudLayers_segmentStrEn = implode(" , ", $cloudLayers_segmentArrEn);
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
global $windDirection;
if(strtoupper($windDirection) !== 'VRB')
{
    $windDirection += 20;
    $windDirection = ($windDirection > 360)? $windDirection - 360 : $windDirection;
    while(strlen($windDirection) < 3)
    {
	    $windDirection = '0'.$windDirection;
    }
}





$atisResultEn = New AtisConstructor();
$atisResultFr = New AtisConstructor();



//Build String

if($bilingualDemanded)
{

    $basicInformations = New AtisSectionConstructor();

    $basicInformations->addSection(  GetAirportNameString($airportICAO, 'fr').' renseignement'.json_decode('"\u00A0"').WrapLetter($infoPhonetic) );
    $basicInformations->addSection( 'météo à '.WrapNumberSpell(substr($infoZuluTime, 1, 4)).' Zulu' );
    $atisResultFr->addSection( $basicInformations->returnResult() );

    $windsEtc = New AtisSectionConstructor();
    $windsEtc->addSection( 'vent '. ( $windDirection === 'VRB' ? 'variable' : WrapNumberSpell($windDirection) ).' à '.WrapNumberWhole($windSpeed_kts).($windSpeed_gust > 0 ? ' rafales à '.WrapNumberWhole($windSpeed_gust) : '').(strlen($windVariaton) > 0 ? " variant entre ".$windVariatonList[0].' et '.$windVariatonList[1] : '') );
    $windsEtc->addSection( 'visibilité '.WrapNumberSpell($visibility) );
    $atisResultFr->addSection( $windsEtc->returnResult() );

    $precip = New AtisSectionConstructor();
    $precip->addSection( $precipitations_segmentStrFr );
    $atisResultFr->addSection( $precip->returnResult() );

    $clouds = New AtisSectionConstructor();
    if (strlen($cloudLayers_segmentStrFr) > 0 ){
        $clouds->addSection( $cloudLayers_segmentStrFr );
    } else {
        $clouds->addSection( 'aucun nuage');
    }
    $atisResultFr->addSection( $clouds->returnResult() );

    $baroEtc = New AtisSectionConstructor();
    $baroEtc->addSection( 'température '.WrapNumberSpell(MetarMainPart::$allMetarMainPartsByNames['temp']->result_str).'[,] point de rosée'.WrapNumberSpell($temp_dewpoint) );
    $baroEtc->addSection( 'point de rosée '.WrapNumberSpell($temp_dewpoint) );
    $baroEtc->addSection( 'altimètre '.WrapNumberSpell($altimeter_hg) );
    $atisResultFr->addSection( $baroEtc->returnResult() );

    $procedures = New AtisSectionConstructor();
    $procedures->addSection( 'approches IFR '.GetAirportAppRwysString($app_rwys, $app_type, 'fr') );
    $procedures->addSection( 'départs '.GetAirportDepRwysString($dep_rwys, 'fr') );
    $atisResultFr->addSection( $procedures->returnResult() );

    $notams = New AtisSectionConstructor();
    if($notamsDemanded)
    {
	    //echo "\n\n";

        foreach($GLOBALS['ACTIVE_NOTAMS_IDS'] as $notam)
        {
            //var_dump($notam);
    
	        //echo "\nddddd\n";
            //var_dump($notam->GetIdent());
            //var_dump($GLOBALS['ACTIVE_NOTAMS_IDS']);
    
	        //echo "\n\n";
            if(in_array($notam->GetIdent(), $atisEnabledAirports))
            {
                $this_notam_text = $notam->GetText();
    
                $this_notam_text = NotamTextAdjustments::AdjustAndReturnText($this_notam_text);

                $this_notam_text = strtolower($this_notam_text);
    
	            $notams->addSection( $this_notam_text );
            }
        }
        $atisResultFr->addSection( $notams->returnResult() );

    }

    $ending = New AtisSectionConstructor();
    $ending->addSection("Avisez l'ATC que vous avez l'information".json_decode('"\u00A0"').WrapLetter($infoPhonetic) );
    $atisResultFr->addSection( $ending->returnResult() );

}

$basicInformations = New AtisSectionConstructor("Avisez l'ATC que vous avez l'information".WrapLetter($infoPhonetic));
$basicInformations->addSection( GetAirportNameString($airportICAO, 'en').' information'.utf8_decode(json_decode('"\u00A0"')).WrapLetter($infoPhonetic) );
$basicInformations->addSection( 'weather at '.WrapNumberSpell(substr($infoZuluTime, 1, 4)).' Zulu' );
$atisResultEn->addSection( $basicInformations->returnResult() );

$windsEtc = New AtisSectionConstructor();
$windsEtc->addSection( 'wind '. ( $windDirection === 'VRB' ? 'Variable' : WrapNumberSpell($windDirection) ).' at '.WrapNumberWhole($windSpeed_kts).($windSpeed_gust > 0 ? ' , gusting '.WrapNumberWhole($windSpeed_gust) : '').(strlen($windVariaton) > 0 ? " , varying between ".$windVariatonList[0].' and '.$windVariatonList[1] : '') );
$windsEtc->addSection( 'visibility '.WrapNumberSpell($visibility) );
$atisResultEn->addSection( $windsEtc->returnResult() );

$precip = New AtisSectionConstructor();
$precip->addSection( $precipitations_segmentStrEn );
$atisResultEn->addSection( $precip->returnResult() );

$clouds = New AtisSectionConstructor();
if (strlen($cloudLayers_segmentStrEn) > 0 ){
    $clouds->addSection( $cloudLayers_segmentStrEn );
} else {
    $clouds->addSection( 'sky clear');
}
$atisResultEn->addSection( $clouds->returnResult() );

$baroEtc = New AtisSectionConstructor();
$baroEtc->addSection( 'temperature '.WrapNumberSpell(MetarMainPart::$allMetarMainPartsByNames['temp']->result_str).' [,] dew point '.WrapNumberSpell($temp_dewpoint) );
$baroEtc->addSection( 'altimeter '.WrapNumberSpell(MetarMainPart::$allMetarMainPartsByNames['baro']->result_str) );
$atisResultEn->addSection( $baroEtc->returnResult() );

$procedures = New AtisSectionConstructor();
$procedures->addSection( 'IFR approach '.GetAirportAppRwysString($app_rwys, $app_type, 'en') );
$procedures->addSection( 'departures '.GetAirportDepRwysString($dep_rwys, 'en') );
$atisResultEn->addSection( $procedures->returnResult() );

$notams = New AtisSectionConstructor();
if($notamsDemanded)
{

    foreach($GLOBALS['ACTIVE_NOTAMS_IDS'] as $notam)
    {
        if(in_array($notam->GetIdent(), $atisEnabledAirports))
        {
            $this_notam_text =  $notam->GetText();
    
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
    }


}
    $atisResultEn->addSection( $All_Notams_man_Txt );

//var_dump($atisResult);
$ending = New AtisSectionConstructor();
$ending->addSection( "Advise ATC that you have information".utf8_decode(json_decode('"\u00A0"')).WrapLetter($infoPhonetic) );
$atisResultEn->addSection( $ending->returnResult() );





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


//$outputEnglishText = $outputEnglishText.' A B C D E F G H J K L M N O P Q R S T U V W X Y Z ';
//$outputEnglishText = $outputEnglishText;














$search = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', 'Œ', 'œ', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', 'Š', 'š', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', 'Ÿ', '?', '?', '?', '?', 'Ž', 'ž', '?', 'ƒ', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?');
$replace = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?');



$endString = '';

if($bilingualDemanded) $endString .= "\r\t\t".'(('."\r".$atisResultFr->returnResult()."\t\t".'))'."\r\r\t";

$endString .=  iconv('WINDOWS-1252', 'UTF-8//TRANSLIT', str_replace($search, $replace,  $atisResultEn->returnResult()));


$endString = preg_replace ( '/(?<=\W|^)(\d{2}[R|D|L|G|C]?)\/(\d{2}[R|D|L|G|C]?)(?=\W|$)/' , "$1".json_decode('"\u2013"')."$2" , $endString);


$endString = iconv('UTF-8', 'WINDOWS-1252//TRANSLIT', $endString);

$endString = "\t".$endString ;

if($capitalDemanded)
{
    $endString = strToUpper($endString);
}

echo $endString;





/*

public static Regex StationId = new Regex ("^[A-Z]{4}$", RegexOptions.Compiled);
public static Regex ReportTime = new Regex ("([0-9]{2})([0-9]{2})([0-9]{2})Z", RegexOptions.Compiled);
public static Regex Wind = new Regex ("([0-9]{3}|VRB)([0-9]{2,3})G?([0-9]{2,3})?(KT|MPS|KMH)", RegexOptions.Compiled);
public static Regex Visibility = new Regex ("^([0-9]{4})([NS]?[EW]?)$", RegexOptions.Compiled);
public static Regex Clouds = new Regex ("^(VV|FEW|SCT|SKC|CLR||BKN|OVC)([0-9]{3}|///)(CU|CB|TCU|CI)?$", RegexOptions.Compiled);
public static Regex TempAndDew = new Regex ("^(M?[0-9]{2})/(M?[0-9]{2})?$", RegexOptions.Compiled);
public static Regex PressureHg = new Regex ("A([0-9]{4})", RegexOptions.Compiled);
public static Regex PressureMb = new Regex ("Q([0-9]{4})", RegexOptions.Compiled);
public static Regex Weather = new Regex ("^(VC)?(-|\\+)?(MI|PR|BC|DR|BL|SH|TS|FZ)?((DZ|RA|SN|SG|IC|PL|GR|GS|UP)+)?(BR|FG|FU|VA|DU|SA|HZ|PY)?(PO|SQ|FC|SS)?$", RegexOptions.Compiled);
	

METAR REGEX

    METAR example "CCCC YYGGggZ dddff(f)(Gfmfm) (KMH ou KT ou MPS) (dndndnVdxdxdx) VVVV(Dv) (VxVxVxVx(Dv)) ou CAVOK (RDRDR/VRVRVRVRI ou RDRDR/VRVRVR VRVVRVRVRVRI) w?w?(ww) (NsNsNshshshs ou VVhshshs ou SKC) T?T?/T?dT?d QPHPHPHPH REw'w' (WS TKOF RWYDRDR et/ou WS LDG RWYDRDR)"
    https://fr.wikipedia.org/wiki/METAR


*/
?>