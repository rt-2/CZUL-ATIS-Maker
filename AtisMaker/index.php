<?php// Euroscope Compatibilityheader('Content-Type: text/plain; charset=ISO-8859-1');//header('Content-Type: text/plain; charset=UTF-8');require_once('./includes/class.metarPart.inc.php');require_once('./includes/functions.inc.php');include_once('./resources/metars.lib.inc.php');include_once('./resources/airports.lib.inc.php');include_once('./resources/notams.lib.inc.php');?><?phpdefine('DEBUG', false);$metarMatches = [];$metar = $_GET['metar'];$metarMainParts = [];$metarMainPartStrings = [];$metar_regex = '';foreach(MetarMainPart::$allMetarMainPartsByNames as $name => $metarMainPart_obj){		$metar_regex .= $metarMainPart_obj->regex.'\s?';}$metar_regex = '/'.$metar_regex.'/mu';/*echo $metar_regex;$main_local_regex.'\s?'.$main_wind_regex.'\s?'.$main_visibility_regex.'\s?'.$main_precipitation_regex.'\s?'.$main_cloud_regex.'\s?'.$main_temp_regex.'\s?'.$main_altimeter_regex.'\s?RMK\s'.$main_remark_regex;$winds_regex = '/(?P<icao>\w{4})\s(?P<time_day>\d{2})(?P<time_zulu>\d{4})Z\s(?P<winds>\d{5}(?:G\d{2})?KT)\s(?P<visibility>\d{1,2})SM\s(?P<precipitations>(?:(?:(?:\-|\+)?[A-Z]{2})\s){0,})(?P<clouds>(?:(?:FEW|BKN|SCT|OVC)\d{3}\s){0,})(?P<temperature>\d{2})\/(?P<dewPoint>\d{2})\sA(?P<altimeter>\d{4}) RMK (?P<remarks>[[:ascii:]]*)';*///(?P<icao>\w{4})\s(?P<time_day>\d{2})(?P<time_zulu>\d{4})Zif(DEBUG){	echo "\n\n";	echo "Parameters :\n";	echo json_encode($_GET);	echo "\n\n";	echo "Metar :\n";	echo $metar;	echo "\n\n";	echo "Main Regex :\n";	echo $metar_regex;	echo "\n\n";}preg_match_all($metar_regex, $metar, $matches);foreach(MetarMainPart::$allMetarMainPartsByNames as $name => $metarMainPart_obj){	$metarMainPart_obj->SetResultString($matches[$name][0]);}MetarMainPart::$allMetarMainPartsByNames['local']->addSubPart(['airport_icao','issue_date','issue_time'], '/^(?P<airport_icao>\w{4})\s(?P<issue_date>\d{2})(?P<issue_time>\d{4})Z$/');MetarMainPart::$allMetarMainPartsByNames['winds']->addSubPart(['wind_degree','wind_speed','wind_variaton'], '/^(?P<wind_degree>\w{3})(?P<wind_speed>\w{2}(?:G\w{2})?KT)(?:\s(?P<wind_variaton>\d{3}V\d{3}))?$/');if(DEBUG){	foreach(MetarMainPart::$allMetarMainPartsByNames as $name => $metarMainPart_obj)	{		//echo "\n\n";		echo $name.' : ';		echo '   ';		echo MetarMainPart::$allMetarMainPartsByNames[$name]->result_str;		echo "\n";		foreach($metarMainPart_obj->subPartsByNames as $name => $metarPart_obj)		{			echo '   '.$name.' : ';			echo $metarPart_obj->result_str;			echo "\n";					}			}}	//echo "\n\n";	//echo "\n\n";	//echo json_encode($matches);$airportICAO = MetarMainPart::$allMetarMainPartsByNames['local']->subPartsByNames['airport_icao']->result_str;function GetAirportNameString($icao, $lang){	$return_value = '['.$icao.']';	if(@strlen($GLOBALS['cityNames_array'][$icao][$lang]) > 0)	{		$return_value = $GLOBALS['cityNames_array'][$icao][$lang];	}	return $return_value;}//var_dump(MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_degree']->result_str);//var_dump(MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_variaton']->result_str);//var_dump(MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_speed']->result_str);$infoLetter = $_GET['info'];$infoZuluTime = str_replace('Z', 'Z', MetarMainPart::$allMetarMainPartsByNames['local']->subPartsByNames['issue_time']->result_str);$windDirection = MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_degree']->result_str;$windVariaton = MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_variaton']->result_str;$windVariatonList = explode('V', $windVariaton);$windSpeeds = MetarMainPart::$allMetarMainPartsByNames['winds']->subPartsByNames['wind_speed']->result_str;preg_match_all('/(?P<speed_kts>(?<=^)\d\d)(?:G(?P<speed_gust>(?<=G)\d\d))?KT$/', $windSpeeds, $speedMatches);$windSpeed_kts = +$speedMatches['speed_kts'][0];$windSpeed_gust = +$speedMatches['speed_gust'][0];$visibility = str_replace('SM', '', MetarMainPart::$allMetarMainPartsByNames['visibility']->result_str);$precipitations = MetarMainPart::$allMetarMainPartsByNames['precipitations']->result_str;$precipitations_array = explode(" ", $precipitations);$precipitations_segmentArr = [];$precipitations_segmentStr = '';foreach($precipitations_array as $precipitation){	$precipitation_intensity = '';	$precipitation_name = $precipitation;	$precipitation_descr = $precipitation;	$precipitation_intensity_str = '';	$precipitation_descr_str = '';	$precipitation_name_str = '';	if(substr($precipitation, 0, 1) === '-' || substr($precipitation, 0, 1) === '+')	{		$precipitation_intensity = substr($precipitation, 0, 1);		$precipitation_name = substr($precipitation, 1, 2);	}		switch($precipitation_intensity)	{		case '-':			$precipitation_intensity_str = 'light';			break;		case '':			$precipitation_intensity_str = 'moderate';			break;		case '+':			$precipitation_intensity_str = 'heavy';			break;	}	if(strlen($precipitation_name) === 4)	{		$precipitation_descr = substr($precipitation_name, 0, 2);		$precipitation_name = substr($precipitation_name, 2, 2);	}	$precipitation_descr_str = METAR_PRECIP_DESCR_NAMES[$precipitation_descr]['en'];	$precipitation_name_str = METAR_PRECIP_NAMES[$precipitation_name]['en'];	if(strlen($precipitation_name_str) > 0)	{		$precipitations_segmentArr[] = $precipitation_intensity_str.' '.(( strlen($precipitation_descr_str) > 0) ? $precipitation_descr_str.' ':'').$precipitation_name_str;	}}$precipitations_segmentStr = implode(" , ", $precipitations_segmentArr);$clouds = MetarMainPart::$allMetarMainPartsByNames['clouds']->result_str;$clouds_array = explode(" ", $clouds);$cloudLayers_segmentArr = [];$cloudLayers_segmentStr = '';foreach($clouds_array as $cloudLayer){	$type = substr($cloudLayer, 0, 3);	$alt = WrapNumberWhole((+substr($cloudLayer, 3, 3)).'00');	$retStr = '';	switch($type)	{		case 'FEW':			$retStr = 'few clouds at '.$alt;			break;		case 'SCT':			$retStr = 'scattered clouds at '.$alt;			break;		case 'BKN':			$retStr = $alt.' broken';			break;		case 'OVC':			$retStr = $alt.' overcast';			break;	}	$cloudLayers_segmentArr[] = $retStr;}$cloudLayers_segmentStr = implode(" , ", $cloudLayers_segmentArr);$temps = MetarMainPart::$allMetarMainPartsByNames['temps']->result_str;$temps_array = explode("/", str_replace("M", "-", $temps));$temp_celcius = +preg_replace('((?=0)\d)', '$1', $temps_array[0]);$temp_dewpoint = +preg_replace('((?=0)\d)', '$1', $temps_array[1]);$altimeter_hg = substr(MetarMainPart::$allMetarMainPartsByNames['altimeter']->result_str, 1);$dep_rwys = $_GET['dep'];$dep_rwys_str = '';function GetAirportDepRwysString($dep_rwys, $lang){	$dep_rwys_list = [];	foreach(explode(',', $dep_rwys) as $rwy)	{		$dep_rwys_list[] = ($lang === 'fr'? 'piste '.str_replace(['R', 'L'], ['D', 'G'], $rwy): 'runway '.$rwy);	}	return implode(($lang === 'fr'? ' et ': ' and '), $dep_rwys_list);}$app_rwys = $_GET['arr'];$app_type = $_GET['apptype'];$app_rwys_str = '';function GetAirportAppRwysString($app_rwys, $app_type, $lang){	$app_rwys_list = [];	foreach(explode(',', $app_rwys) as $rwy)	{		$app_rwys_list[] = $app_type.($lang === 'fr'? ' piste '.str_replace(['R', 'L'], ['D', 'G'], $rwy): ' runway '.$rwy);	}	return implode(($lang === 'fr'? ' et ': ' and '), $app_rwys_list);}if(strtoupper($windDirection) !== 'VRB'){    $windDirection += 20;    $windDirection = ($windDirection > 360)? $windDirection - 360 : $windDirection;    while(strlen($windDirection) < 3)    {	    $windDirection = '0'.$windDirection;    }}//Fetch NOTAMs$thisArptNotams = [];if(in_array($airportICAO, array_keys($GLOBALS['notams_array']))){	$thisArptNotams = $GLOBALS['notams_array'][$airportICAO];}if(DEBUG){	echo "\n\n";	echo '== FINAL ATIS ==';	echo "\n\n";}//Build String$outputEnglishText = GetAirportNameString($airportICAO, 'en').' information '.WrapLetter($infoLetter)." , ";$outputEnglishText .= 'weather at '.WrapNumberSpell($infoZuluTime)."Z. ";$outputEnglishText .= 'wind '.WrapNumberSpell($windDirection).' at '.WrapNumberWhole($windSpeed_kts).($windSpeed_gust > 0 ? ' , gusting '.WrapNumberWhole($windSpeed_gust) : '').(strlen($windVariaton) > 0 ? " , varying between ".$windVariatonList[0].' and '.$windVariatonList[1] : '').". ";$outputEnglishText .= 'visibility '.WrapNumberSpell($visibility);$outputEnglishText .= (strlen($precipitations_segmentStr) > 0 ? " , ".$precipitations_segmentStr.'. ' : ". ");$outputEnglishText .= (strlen($cloudLayers_segmentStr) > 0 ? $cloudLayers_segmentStr.' , ' : 'Sky clear , ');$outputEnglishText .= 'temperature '.WrapNumberSpell($temp_celcius)." , dew point ".WrapNumberSpell($temp_dewpoint)." , ";$outputEnglishText .= 'altimeter '.WrapNumberSpell($altimeter_hg).". ";$outputEnglishText .= 'IFR approach '.GetAirportAppRwysString($app_rwys, $app_type, 'en')." , ";$outputEnglishText .= 'departures '.GetAirportDepRwysString($dep_rwys, 'en').". ";foreach($thisArptNotams as $notam){    $this_notam_text =  $notam['en'];        $this_notam_text = preg_replace ( '/(?<=\W)A(?=\W)/' , 'ALPHA' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)B(?=\W)/' , 'BRAVO' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)C(?=\W)/' , 'CHARLIE' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)D(?=\W)/' , 'DELTA' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)E(?=\W)/' , 'ECHO' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)F(?=\W)/' , 'FOXTROT' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)G(?=\W)/' , 'GOLF' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)H(?=\W)/' , 'HOTEL' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)I(?=\W)/' , 'INDIA' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)J(?=\W)/' , 'JULIET' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)K(?=\W)/' , 'KILO' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)L(?=\W)/' , 'LEEMA' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)M(?=\W)/' , 'MICK' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)N(?=\W)/' , 'NOVEMBER' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)O(?=\W)/' , 'OSCAR' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)P(?=\W)/' , 'PAPA' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)Q(?=\W)/' , 'QUEBEC' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)R(?=\W)/' , 'ROMEO' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)S(?=\W)/' , 'SIERRA' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)T(?=\W)/' , 'TANGO' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)U(?=\W)/' , 'UNIFORM' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)V(?=\W)/' , 'VICTOR' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)W(?=\W)/' , 'WHISKEY' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)X(?=\W)/' , 'X-RAY' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)Y(?=\W)/' , 'YANKEE' , $this_notam_text);    $this_notam_text = preg_replace ( '/(?<=\W)Z(?=\W)/' , 'ZULU' , $this_notam_text);        $this_notam_text = strtolower($this_notam_text);    $this_notam_text[0] = strtoupper($this_notam_text);	$outputEnglishText .= $this_notam_text.'. ';}$outputEnglishText .= 'Advise ATC that you have information '.WrapLetter($infoLetter).'.';$outputFrenchText = ''.GetAirportNameString($airportICAO, 'fr').' information '.$infoLetter.'[,] ';$outputFrenchText .= 'météo à  '.$infoZuluTime.', ';$outputFrenchText .= 'vent '.$windDirection.' à  '.WrapNumberWhole($windSpeed_kts).($windSpeed_gust > 0 ? ' rafales à  '.WrapNumberWhole($windSpeed_gust) : '').(strlen($windVariaton) > 0 ? ' variant entre '.$windVariatonList[0].' et '.$windVariatonList[1] : '').', ';$outputFrenchText .= 'visibilité '.$visibility.', ';$outputFrenchText .= (strlen($precipitations_segmentStr) > 0 ? $precipitations_segmentStr.', ' : '');$outputFrenchText .= (strlen($cloudLayers_segmentStr) > 0 ? $cloudLayers_segmentStr.', ' : 'Sky clear, ');$outputFrenchText .= 'température '.+$temp_celcius.', point de rosée '.+$temp_dewpoint.', ';$outputFrenchText .= 'altimètre '.$altimeter_hg.', '."\r";$outputFrenchText .= 'approches IFR '.GetAirportAppRwysString($app_rwys, $app_type, 'fr').', ';$outputFrenchText .= 'departs '.GetAirportDepRwysString($dep_rwys, 'fr').'[.] ';foreach($thisArptNotams as $notam){	$outputFrenchText .= strtolower($notam['fr']).'. '."\r";}$outputFrenchText .= 'Avisez l\'ATC que vous avez l\'informaton '.WrapLetter($infoLetter).'.';/*$windDirection = +$metarMatches['wind_dir'][0];$windDirection += 20;$windDirection = ($windDirection > 360)? $windDirection - 360 : $windDirection;$outputEnglishText = $metarMatches['icao'][0].' information '.$_GET['info'][0].', ';$outputEnglishText .= $metarMatches['winds'][0].', ';//$outputEnglishText .= 'wind '.$windDirection.' at '.$metarMatches['wind_kts'][0].', ';$outputEnglishText .= 'visibility '.$metarMatches['visibility'][0].' miles, ';$outputEnglishText .= $metarMatches['precipitations'][0].', ';$outputEnglishText .= $metarMatches['clouds'][0].', ';//$outputEnglishText = str_replace(["\r\n","\n","\r"], "", $outputText);//$outputEnglishText = str_replace(['CYUL','CYOW'], ['Montreal','Ottawa'], $outputText);*/$outputEnglishText = $outputEnglishText.' A B C D E F G H J K L M N O P Q R S T U V W X Y Z ';$outputEnglishText = $outputEnglishText;    $outputEnglishText = preg_replace ( '/(?<=\W)(\d{2}[r|l|c]?)\/(\d{2}[R|L|C]?)(?=\W)/' , "$1 $2" , $outputEnglishText);    $outputEnglishText = preg_replace ( '/([0-9]+)ft/' , "~$1 feet" , $outputEnglishText);    $outputEnglishText = preg_replace ( '/(?<=\W)twy(?=\W)/' , 'taxiway' , $outputEnglishText);    $outputEnglishText = preg_replace ( '/(?<=\W)rwy(?=\W)/' , 'runway' , $outputEnglishText);    $outputEnglishText = preg_replace ( '/(?<=\W)btn(?=\W)/' , 'between' , $outputEnglishText);    $outputEnglishText = preg_replace ( '/(?<=\W)avbl(?=\W)/' , 'available' , $outputEnglishText);    $outputEnglishText = preg_replace ( '/(?<=\W)clsd(?=\W)/' , 'closed' , $outputEnglishText);$outputEnglishText = preg_replace ( '/(?<=\W)(\d{2}?)r(?=\W)/' , "$1R" , $outputEnglishText);$outputEnglishText = preg_replace ( '/(?<=\W)(\d{2}?)c(?=\W)/' , "$1C" , $outputEnglishText);$outputEnglishText = preg_replace ( '/(?<=\W)(\d{2}?)l(?=\W)/' , "$1L" , $outputEnglishText);$outputEnglishText = $outputEnglishText."\t\t";echo $outputEnglishText;//echo "\t\r\t";//echo $outputFrenchText;?>