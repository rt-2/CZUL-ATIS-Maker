<?php

(new MetarMainPart())->SetNew('local', '(?<=^)\w{4}\s\d{2}\d{4}Z(?=\s)');
(new MetarMainPart())->SetNew('winds', '(?<=\s)(?>VRB|\d{3})\d{2}(?:G\d{2})?KT(?:\s\d{3}V\d{3})?(?=\s)');
(new MetarMainPart())->SetNew('visibility', '(?<=\s)(?:\d{1,2}|\d\/\d)SM(?=\s)');
(new MetarMainPart())->SetNew('precipitations', '(?<=\s)(?:(?:\-|\+)?(?:[A-Z]{2}){1,3}(?=\s)){0,}(?=\s)', false);
(new MetarMainPart())->SetNew('clouds', '(?<=\s)SKC|(?:\s?(?:FEW|BKN|SCT|OVC)\d{3}){0,}(?=\s)');
(new MetarMainPart())->SetNew('temps', '(?<=\s)M?\d\d\/M?\d\d(?=\s)');
(new MetarMainPart())->SetNew('altimeter', '(?<=\s)A\d{4}(?=\s)');
(new MetarMainPart())->SetNew('remarks', '(?<=\s)RMK [[:ascii:]]*');



?>