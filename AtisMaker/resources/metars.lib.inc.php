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
                'fr' => '', ]
    'BL' => [ 'en' => 'blowing',
		        'fr' => '', ]
    'DR' => [ 'en' => 'low drifting',
		        'fr' => '', ]
    'FZ' => [ 'en' => 		'freezing',
		        'fr' => '', ]
    'MI' => [ 'en' => 'shallow',
		        'fr' => '', ]
    'PR' => [ 'en' => 'partial',
		        'fr' => '', ]
    'SH' => [ 'en' => 'shower',
		        'fr' => '', ]
    'TS' => [ 'en' => 'thunderstorm',
		        'fr' => '', ]
]

const METAR_PRECIP_NAMES =
[
		case 'DZ': = ['en' => 'drizzle',
				'fr' => '',
			];
			break;
		case 'GR': = ['en' => 'hail',
				'fr' => '',
			];
			break;
		case 'GS': = ['en' => 'snow pellets',
				'fr' => '',
			];
			break;
		case 'IC': = ['en' => 'ice crystals',
				'fr' => '',
			];
			break;
		case 'PL': = ['en' => 'ice pellets',
				'fr' => '',
			];
			break;
		case 'RA': = ['en' => 'rain',
				'fr' => '',
			];
			break;
		case 'SG': = ['en' => 'snow Grains',
				'fr' => '',
			];
			break;
		case 'SN': = ['en' => 'snow',
				'fr' => '',
			];
			break;
		case 'UP': = ['en' => 'unknown precipitation',
				'fr' => '',
			];
			break;
		case 'BR': = ['en' => 'mist',
				'fr' => '',
			];
			break;
		case 'DU': = ['en' => 'widespread dust',
				'fr' => '',
			];
			break;
		case 'FG': = ['en' => 'fog',
				'fr' => '',
			];
			break;
		case 'FU': = ['en' => 'smoke',
				'fr' => '',
			];
			break;
		case 'HZ': = ['en' => 'haze',
				'fr' => '',
			];
			break;
		case 'PY': = ['en' => 'spray',
				'fr' => '',
			];
			break;
		case 'SA': = ['en' => 'sand',
				'fr' => '',
			];
			break;
		case 'VA': = ['en' => 'volcanic ash',
				'fr' => '',
			];
			break;
		case 'DS': = ['en' => 'dust storm',
				'fr' => '',
			];
			break;
		case 'FC': = ['en' => 'funnel cloud',
				'fr' => '',
			];
			break;
		case 'PO': = ['en' => 'well-developed',
				'fr' => '',
			];
			break;
		case 'SQ': = ['en' => 'squalls',
				'fr' => '',
			];
			break;
		case 'SS': = ['en' => 'sandstorm',
				'fr' => '',
			];
]
?>