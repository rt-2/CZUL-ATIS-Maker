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
                'fr' => 'patches', ],
    'BL' => [ 'en' => 'blowing',
		        'fr' => 'blowing', ],
    'DR' => [ 'en' => 'low drifting',
		        'fr' => 'low drifting', ],
    'FZ' => [ 'en' => 'freezing',
		        'fr' => 'freezing', ],
    'MI' => [ 'en' => 'shallow',
		        'fr' => 'shallow', ],
    'PR' => [ 'en' => 'partial',
		        'fr' => 'partial', ],
    'SH' => [ 'en' => 'shower',
		        'fr' => 'shower', ],
    'TS' => [ 'en' => 'thunderstorm',
		        'fr' => 'thunderstorm', ],
];

const METAR_PRECIP_NAMES =
[
	'DZ' => [  'en' => 'drizzle',
				'fr' => 'drizzle', ],
	'GR' => [  'en' => 'hail',
				'fr' => 'hail', ],
	'GS' => [  'en' => 'snow pellets',
				'fr' => 'snow pellets', ],
	'IC' => [  'en' => 'ice crystals',
				'fr' => 'ice crystals', ],
	'PL' => [  'en' => 'ice pellets',
				'fr' => 'ice pellets', ],
	'RA' => [  'en' => 'rain',
				'fr' => 'rain', ],
	'SG' => [  'en' => 'snow Grains',
				'fr' => 'snow Grains', ],
	'SN' => [  'en' => 'snow',
				'fr' => 'snow', ],
	'UP' => [  'en' => 'unknown precipitation',
				'fr' => 'unknown precipitation', ],
	'BR' => [  'en' => 'mist',
				'fr' => 'mist', ],
	'DU' => [  'en' => 'widespread dust',
				'fr' => 'widespread dust', ],
	'FG' => [  'en' => 'fog',
				'fr' => 'fog', ],
	'FU' => [  'en' => 'smoke',
				'fr' => 'smoke', ],
	'HZ' => [  'en' => 'haze',
				'fr' => 'haze', ],
	'PY' => [  'en' => 'spray',
				'fr' => 'spray', ],
	'SA' => [  'en' => 'sand',
				'fr' => 'sand', ],
	'VA' => [  'en' => 'volcanic ash',
				'fr' => 'volcanic ash', ],
	'DS' => [  'en' => 'dust storm',
				'fr' => 'dust storm', ],
	'FC' => [  'en' => 'funnel cloud',
				'fr' => 'funnel cloud', ],
	'PO' => [  'en' => 'well-developed',
				'fr' => 'well-developed', ],
	'SQ' => [  'en' => 'squalls',
				'fr' => 'squalls', ],
	'SS' => [  'en' => 'sandstorm',
			    'fr' => 'sandstorm', ],
];
?>