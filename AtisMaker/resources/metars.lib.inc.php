<?php


(new MetarMainPart())->SetNew('local', '\w{4}\s\d{2}\d{4}Z');
(new MetarMainPart())->SetNew('winds', '(?>VRB|\d{3})\d{2}(?:G\d{2})?KT(?:\s\d{3}V\d{3})?');
(new MetarMainPart())->SetNew('visibility', '(?:\d{1,2}|\d\/\d|\d\s\d\/\d)SM');
(new MetarMainPart())->SetNew('precipitations', '(?:\-?[A-Z]{2}(?:\s?))*', false);
(new MetarMainPart())->SetNew('clouds', 'SKC|(?:\s?(?:FEW|BKN|SCT|OVC)\d{3}){0,}');
(new MetarMainPart())->SetNew('temps', 'M?\d\d\/M?\d\d');
(new MetarMainPart())->SetNew('altimeter', 'A\d{4}');
(new MetarMainPart())->SetNew('remarks', 'RMK [[:ascii:]]*');


const METAR_PRECIP_DESCR_NAMES =
[
    'BC' => [ 'en' => 'patches',
                'fr' => '', ],
    'BL' => [ 'en' => 'blowing',
		        'fr' => '', ],
    'DR' => [ 'en' => 'low drifting',
		        'fr' => '', ],
    'FZ' => [ 'en' => 'freezing',
		        'fr' => '', ],
    'MI' => [ 'en' => 'shallow',
		        'fr' => '', ],
    'PR' => [ 'en' => 'partial',
		        'fr' => '', ],
    'SH' => [ 'en' => 'shower',
		        'fr' => '', ],
    'TS' => [ 'en' => 'thunderstorm',
		        'fr' => '', ],
];

const METAR_PRECIP_NAMES =
[
	'DZ' => [  'en' => 'drizzle',
				'fr' => '', ],
	'GR' => [  'en' => 'hail',
				'fr' => '', ],
	'GS' => [  'en' => 'snow pellets',
				'fr' => '', ],
	'IC' => [  'en' => 'ice crystals',
				'fr' => '', ],
	'PL' => [  'en' => 'ice pellets',
				'fr' => '', ],
	'RA' => [  'en' => 'rain',
				'fr' => '', ],
	'SG' => [  'en' => 'snow Grains',
				'fr' => '', ],
	'SN' => [  'en' => 'snow',
				'fr' => '', ],
	'UP' => [  'en' => 'unknown precipitation',
				'fr' => '', ],
	'BR' => [  'en' => 'mist',
				'fr' => '', ],
	'DU' => [  'en' => 'widespread dust',
				'fr' => '', ],
	'FG' => [  'en' => 'fog',
				'fr' => '', ],
	'FU' => [  'en' => 'smoke',
				'fr' => '', ],
	'HZ' => [  'en' => 'haze',
				'fr' => '', ],
	'PY' => [  'en' => 'spray',
				'fr' => '', ],
	'SA' => [  'en' => 'sand',
				'fr' => '', ],
	'VA' => [  'en' => 'volcanic ash',
				'fr' => '', ],
	'DS' => [  'en' => 'dust storm',
				'fr' => '', ],
	'FC' => [  'en' => 'funnel cloud',
				'fr' => '', ],
	'PO' => [  'en' => 'well-developed',
				'fr' => '', ],
	'SQ' => [  'en' => 'squalls',
				'fr' => '', ],
	'SS' => [  'en' => 'sandstorm',
			    'fr' => '', ],
];
?>