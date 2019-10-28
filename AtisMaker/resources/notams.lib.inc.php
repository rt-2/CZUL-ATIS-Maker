<?php


$GLOBALS['notams_array'] = [
	'CYUL' => [
		[
			'en' => 'RWY 06L/24R CLSD',
			'fr' => 'Piste 06G/24D fermé',
        ],
		[
			'en' => 'TWY E BTN TWY I AND RWY 10/28 CLSD',
			'fr' => 'Taxiway E fermé entre le taxiway B et la piste 06G/24D',
		],
		[
			"en" => "RWY 10/28 CLSD AVBL AS TWY",
			"fr" => "Piste 10/28 fermé, disponble comme taxiway",
		],
		[
			"en" => "TWY G CLSD",
			"fr" => "Taxiway G fermé",
		]
	],
	"CYHU" => [ 
		[
			"en" => "TWY B BTN ENA AND 240 FT FM ENA CLSD",
			"fr" => "TWY B ENTRE ENA ET 240 FT DE ENA FERMÉ",
		],
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
			"en" => "Taxiway A closed between taxiway H and holding bay 11",
			"fr" => "Taxiway A fermé entre le taxiway H et l\'air d\'attente piste 11",
		],
	],
];


$GLOBALS['phonetic_alphabet'] = [
	'A' => 'ALPHA',
	'B' => 'BRAVO',
	'C' => 'CHARLIE',
	'D' => 'DELTA',
	'E' => 'ECHO',
	'F' => 'FOXTROT',
	'G' => 'GOLF',
	'H' => 'HOTEL',
	'I' => 'INDIA',
	'J' => 'JULIET',
	'K' => 'KILO',
	'L' => 'LEEMA',
	'M' => 'MICK',
	'N' => 'NOVEMBER',
	'O' => 'OSCAR',
	'P' => 'PAPA',
	'Q' => 'QUEBEC',
	'R' => 'ROMEO',
	'S' => 'SIERRA',
	'T' => 'TANGO',
	'U' => 'UNIFORM',
	'V' => 'VICTOR',
	'W' => 'WHISKEY',
	'X' => 'X-RAY',
	'Y' => 'YANKEE',
	'Z' => 'ZULU',
];

foreach($GLOBALS['phonetic_alphabet'] as $letter => $sound)
{
    New NotamTextAdjustments($letter , $sound);
}

New NotamTextAdjustments('TWY', 'TAXIWAY');
New NotamTextAdjustments('FM', 'FROM');
New NotamTextAdjustments('RWY', 'RUNWAY');
New NotamTextAdjustments('BTN', 'BETWEEN');
New NotamTextAdjustments('AVBL', 'AVAILABLE');
New NotamTextAdjustments('CLSD', 'CLOSED');



?>