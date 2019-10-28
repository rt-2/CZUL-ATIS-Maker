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
                'fr' => 'bancs', ],
    'BL' => [ 'en' => 'blowing',
		        'fr' => 'chasse élevée', ],
    'DR' => [ 'en' => 'low drifting',
		        'fr' => 'chasse basse', ],
    'FZ' => [ 'en' => 'freezing',
		        'fr' => 'verglaçant', ],
    'MI' => [ 'en' => 'shallow',
		        'fr' => 'mince', ],
    'PR' => [ 'en' => 'partial',
		        'fr' => 'partiel', ],
    'SH' => [ 'en' => 'shower',
		        'fr' => 'averse', ],
    'TS' => [ 'en' => 'thunderstorm',
		        'fr' => 'orage', ],
];

const METAR_PRECIP_NAMES =
[
	'DZ' => [  'en' => 'drizzle',
				'fr' => 'Bruine', ],
	'GR' => [  'en' => 'hail',
				'fr' => 'grêle', ],
	'GS' => [  'en' => 'snow pellets',
				'fr' => 'neige roulée', ],
	'IC' => [  'en' => 'ice crystals',
				'fr' => 'cristaux de glace', ],
	'PL' => [  'en' => 'ice pellets',
				'fr' => 'granules de glace', ],
	'RA' => [  'en' => 'rain',
				'fr' => 'pluie', ],
	'SG' => [  'en' => 'snow Grains',
				'fr' => 'neige en grains', ],
	'SN' => [  'en' => 'snow',
				'fr' => 'neige', ],
	'UP' => [  'en' => 'unknown precipitation',
				'fr' => 'précipitation inconnue', ],
	'BR' => [  'en' => 'mist',
				'fr' => 'brume', ],
	'DU' => [  'en' => 'widespread dust',
				'fr' => 'Poussière', ],
	'FG' => [  'en' => 'fog',
				'fr' => 'brouillard', ],
	'FU' => [  'en' => 'smoke',
				'fr' => 'fumée', ],
	'HZ' => [  'en' => 'haze',
				'fr' => 'brume sèche', ],
	'PY' => [  'en' => 'spray',
				'fr' => 'embruns', ],
	'SA' => [  'en' => 'sand',
				'fr' => 'sable', ],
	'VA' => [  'en' => 'volcanic ash',
				'fr' => 'cendres volcaniques', ],
	'DS' => [  'en' => 'dust storm',
				'fr' => 'tempête de poussière', ],
	'FC' => [  'en' => 'funnel cloud',
				'fr' => 'nuage en entonnoir', ],
	'PO' => [  'en' => 'dust whirls',
				'fr' => 'tourbillons de poussière', ],
	'SQ' => [  'en' => 'squalls',
				'fr' => 'grains', ],
	'SS' => [  'en' => 'sandstorm',
			    'fr' => 'tempête de sable', ],
];
?>