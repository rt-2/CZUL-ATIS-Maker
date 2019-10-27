<?php


$GLOBALS['notams_array'] = [
	'CYUL' => [
		191072 => [
			'en' => 'TWY E BTN RWY 06L/24R AND TWY B CLSD',
			//'fr' => 'Taxiway '. WrapLetter('E') .' fermÃ© entre la piste 06G/24D et le taxiway '. WrapLetter('B'),
		],
		191510 => [
			"en" => 'FIRST 2948FT RWY 06L CLSD',
			//"fr" => "Premier ".WrapNumberWhole("2948")."ft de la piste 06G fermÃ©",
		],
		191538 => [
			"en" => "RWY 10/28 CLSD AVBL AS TWY",
			//"fr" => "Piste 10/28 fermé, disponble comme taxiway",
		],
		[
			"en" => "TWY G CLSD",
			//"fr" => "Piste 10/28 fermé, disponble comme taxiway",
		]
	],
	"CYHU" => [
		[
			"en" => "T",
			"en" => "TWY B BTN ENA AND 240 FT FM ENA CLSD",
			//"fr" => "",
		],
		190430 => [
			"en" => "Runway 10/28 closed, available as taxiway",
			//"fr" => "Piste 10/28 fermé, disponble comme taxiway",
		],
	],
	"CYMX" => [
		190459 => [
			"en" => "Runway 11/29 closed between sunset and sunrise",
			//"fr" => "Piste 11/29 fermé entre le crépuscule et soir et le crépuscule du matin",
		],
		190465 => [
			"en" => "Taxiway A closed between taxiway H and holding bay 11",
			//"fr" => "Taxiway ".WrapLetter("A")." fermé entre le taxiway ".WrapLetter("H")." et l\'air d\'attente piste 11",
		],
	],
];



New NotamTextAdjustments('A' , 'ALPHA');New NotamTextAdjustments('B' , 'BRAVO');New NotamTextAdjustments('C' , 'CHARLIE');New NotamTextAdjustments('D' , 'DELTA');New NotamTextAdjustments('E' , 'ECHO');New NotamTextAdjustments('F' , 'FOXTROT');New NotamTextAdjustments('G' , 'GOLF');New NotamTextAdjustments('H' , 'HOTEL');New NotamTextAdjustments('I' , 'INDIA');New NotamTextAdjustments('J' , 'JULIET');New NotamTextAdjustments('K' , 'KILO');New NotamTextAdjustments('L' , 'LEEMA');New NotamTextAdjustments('M' , 'MICK');New NotamTextAdjustments('N' , 'NOVEMBER');New NotamTextAdjustments('O' , 'OSCAR');New NotamTextAdjustments('P' , 'PAPA');New NotamTextAdjustments('Q' , 'QUEBEC');New NotamTextAdjustments('R' , 'ROMEO');New NotamTextAdjustments('S' , 'SIERRA');New NotamTextAdjustments('T' , 'TANGO');New NotamTextAdjustments('U' , 'UNIFORM');New NotamTextAdjustments('V' , 'VICTOR');New NotamTextAdjustments('W' , 'WHISKEY');New NotamTextAdjustments('X' , 'X-RAY');New NotamTextAdjustments('Y' , 'YANKEE');New NotamTextAdjustments('Z' , 'ZULU');    New NotamTextAdjustments('TWY', 'TAXIWAY');New NotamTextAdjustments('FM', 'FROM');New NotamTextAdjustments('RWY', 'RUNWAY');New NotamTextAdjustments('BTN', 'BETWEEN');New NotamTextAdjustments('AVBL', 'AVAILABLE');New NotamTextAdjustments('CLSD', 'CLOSED');



?>