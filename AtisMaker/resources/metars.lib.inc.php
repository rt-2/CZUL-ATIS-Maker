<?php

(new MetarMainPart())->SetNew('local', '\w{4}\s\d{2}\d{4}Z');
(new MetarMainPart())->SetNew('winds', '(?>VRB|\d{3})\d{2}(?:G\d{2})?KT(?:\s\d{3}V\d{3})?');
(new MetarMainPart())->SetNew('visibility', '(?:\d{1,2}|\d\/\d|\d\s\d\/\d)SM');
(new MetarMainPart())->SetNew('precipitations', '(?:\-?[A-Z]{2}(?:\s?))*', false);
(new MetarMainPart())->SetNew('clouds', 'SKC|(?:\s?(?:FEW|BKN|SCT|OVC)\d{3}){0,}');
(new MetarMainPart())->SetNew('temps', 'M?\d\d\/M?\d\d');
(new MetarMainPart())->SetNew('altimeter', 'A\d{4}');
(new MetarMainPart())->SetNew('remarks', 'RMK [[:ascii:]]*');



?>