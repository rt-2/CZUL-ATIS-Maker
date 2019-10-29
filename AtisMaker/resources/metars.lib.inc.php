<?php

/*
(?:METAR|SPECI)?\s?(?<icao>[A-Z0-9]{4})\s?
(?<issue_year>\d{2})(?<issue_day>\d{2})(?<issue_hour>\d{2})Z\s?
(?<auto>AUTO)?\s?(?<winds>(?:VRB|\d{3})\d{2}(?:G\d{2})?KT)\s?
(?<wind_var>\d{3}V\d{3})?\s?(?:(?<vis>CAVOK|(?:\d{0,2}\s?(?:\d\/\d)?)SM))\s?
(?<rvr>R\d{2}[R|C|L]?\/(?:M|(?:\d{4}V))?\d{4}FT(?:\/D)?)?\s?
(?<precip>(?:\s?(?:\-|\+)?(?:[A-Z]{2}){1,3}(?=\s))*)\s?
(?<clouds>(?:SKC|CLR)|(?:\s?(?:FEW|BKN|SCT|OVC)\d{3}){0,})\s?
(?:VV(?<vert_vis>\d{3}))?\s?
(?<temp>M?\d{2})\/(?<dew>M?\d{2})\s?
(?<baro>(?:A|Q)\d{4})\s?
(?<precip_recent>(?:\s?RE(?:[A-Z]{2,4}))*)?\s?
(?:\s?WS\s(?<windshear_to>(?:ALL RWY|(?:(?:TKOF RWY|LDG RWY)\d{2}[R|C|L]))))?\s?
(?:\sRMK\s(?<rmk>.*))$
*/

(new MetarMainPart())->SetNew('icao', '[A-Z0-9]{4}');
(new MetarMainPart())->SetNew('issue_time', '\d{6}Z');
(new MetarMainPart())->SetNew('winds', '(?:VRB|\d{3})\d{2}(?:G\d{2})?KT');
(new MetarMainPart())->SetNew('wind_var', '\d{3}V\d{3}', false);
(new MetarMainPart())->SetNew('vis', 'CAVOK|(?:(?:\d{0,2})?\s?(?:\d\/\d)?)(?:SM)');
(new MetarMainPart())->SetNew('rvr', '(?:\s?(?:R\d{2}[R|C|L]?\/[M|P]?\d{4}(?:V[M|P]?\d{4})?FT(?:\/[A-Z]{1})?)?)*', false);
(new MetarMainPart())->SetNew('precip', '(?:\s?(?:\-|\+)?(?:[A-Z]{2}){1,3}(?=\s))*', false);
(new MetarMainPart())->SetNew('clouds', '(?:SKC|CLR)|(?:\s?(?:FEW|BKN|SCT|OVC)\d{3}){0,}', false);
(new MetarMainPart())->SetNew('vert_vis', '(?<=VV)\d{3}', false);
(new MetarMainPart())->SetNew('temp', 'M?\d{2}\/');
(new MetarMainPart())->SetNew('dew', '(?<=\/)M?\d{2}');
(new MetarMainPart())->SetNew('baro', '(?:A|Q)\d{4}');
(new MetarMainPart())->SetNew('precip_recent', '(?:\s?RE(?:[A-Z]{2,4}))*', false);
(new MetarMainPart())->SetNew('windshear', 'WS\s(?:ALL RWY|(?:(?:TKOF RWY|LDG RWY)\d{2}[R|C|L]))', false);
(new MetarMainPart())->SetNew('rmk', '(?:\sRMK\s).*');


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