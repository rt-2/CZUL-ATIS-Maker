<?php
	//
	//
	//	FILE: CANotAPI (Canadian Notam API)
	//	BY: rt-2(http://www.rt-2.net)
	//	PROJECT: https://github.com/rt-2/CANotAPI/
	//		
	//
	//
    
    require_once(dirname(__FILE__).'/includes/definitions.inc.php');
    require_once(dirname(__FILE__).'/includes/notam.class.inc.php');
    require_once(dirname(__FILE__).'/../resources/notams.lib.inc.php');
    

	//
	//	FUNCTION: CANotAPI_GetReadableDate
	//	PURPOSE: returns the string of a readable date from a 10 digit date format
	//	ARGUMENTS:
	//		$date10char: 10 char date/time to be converted
	//		$fields: Array of key/value containng the query data (GET);
	//	RETURNS: A string with all data responsded.
	//
	function CANotAPI_GetReadableDate($date10char)
	{
		// Url-ify the data for the POST
        $fields_string = '';
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		// Open curl connection
		$ch = curl_init();
		// Set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url.'?'.$fields_string);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
		// Execute post
		ob_start();
		curl_exec($ch);
		$result = ob_get_contents();
		ob_end_clean();
		// Close connection
		curl_close($ch);
        return $result;
    }

	//
	//	FUNCTION: CANotAPI_GetUrlData
	//	PURPOSE: returns the string of data from a remote URL
	//	ARGUMENTS:
	//		$url: String of the url to be queried;
	//		$fields: Array of key/value containng the query data (GET);
	//	RETURNS: A string with all data responsded.
	//
	function CANotAPI_GetUrlData($url, $fields)
	{
		// Url-ify the data for the POST
        $fields_string = '';
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		// Open curl connection
		$ch = curl_init();
		// Set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url.'?'.$fields_string);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
		// Execute post
		ob_start();
		curl_exec($ch);
		$result = ob_get_contents();
		ob_end_clean();
		// Close connection
		curl_close($ch);
        return $result;
    }

	//
	//	FUNCTION: CANotAPI_GetNotamsString
	//	PURPOSE: returns the string of notams from an airport search
	//	ARGUMENTS:
	//		$airport: String of the canadian airport you want to search notams for;
	//		$search: String or array of strings of keyword(s) that notam must contain to be shown;
	//	RETURNS: A string with all relevant notams.
	//
	function CANotAPI_GetNotamsArray($airport, $search)
	{
		//
		// Variables
		//
		$ret = [];
		$fields_string = '';
		$airport = strtoupper($airport);
		$time_format = 'ymdHi';
		$time_obj = new DateTime("now", new DateTimeZone('UTC'));
		$time_now = $time_obj->format($time_format);
		$time_obj->add(new DateInterval('PT6H'));
		$time_soon = $time_obj->format($time_format);
		
		//
		// Access Remote Server
		//
		$result = CANotAPI_GetUrlData('https://plan.navcanada.ca/weather/api/search/en', [
			'filter[value]' => urlencode($airport),
			'_' => urlencode(time()),
        ]);
        //var_dump($result);
		$result_json = json_decode($result, true);
        $airportGeoPoint = $result_json['data'][0]['geometry']['coordinates'];
        $airportName = $result_json['data'][0]['properties']['displayName'];
		$result = CANotAPI_GetUrlData('https://plan.navcanada.ca/weather/api/alpha/', [
			'point' => urlencode($airportGeoPoint[0].','.$airportGeoPoint[1].','.$airport.',site'),
			'alpha' => urlencode('notam'),
			'_' => urlencode(time()),
        ]);

		//var_dump($result);
		$result_json = json_decode($result, true);
		//echo $result;
        
        $all_notams_list = $result_json['data'];
        
		foreach((array) $all_notams_list as $notam_data)
		{
            
			$this_notam_isSearched = false;
			$this_notam_isGoodAirport = false;
			$this_notam_isRelevant = false;
            $this_notam_text = $notam_data['text'];
            $regex = "/^\((?<id>\w\d{4}\/\d{2})\X+(?:A\)\s(?<icao>\w{4})\s)(?:B\)\s(?<time_from>\d{10}(?:\w{3})?)\s)(?:C\)\s(?<time_to>\d{10}(?:\w{3})?)\s)(?:D\)\s(?<time_human>\X+)\s)?(?:E\)\s(?:(?:(?<message_en>\X+)\sFR:\s(?<message_fr>\X+)\)$)|(?:(?<message>\X+)\)$)))/mUu";
            
            preg_match($regex, $this_notam_text, $matches);
            if(false)
            {
                echo '<textarea>';
                echo '<br>$regex<br>';
                echo '</textarea>';
                echo '<br>$notam_data<br>';
                echo '<br>$matches<br>';
            }

            $this_notam_obj = New Notam([
                'ident' => $matches['id'],
                'airport' => $matches['icao'],
                'time_from' => $matches['time_from'],
                'time_to' => $matches['time_to'],
                'time_human' => $matches['time_human'],
                'text' => ( isset($matches['message_en']) && strlen($matches['message_en']) > 0 ? $matches['message_en'] : $matches['message'] ),
            ]);

            if($this_notam_obj->GetAirport() === $airport)
            {
			    $this_notam_isGoodAirport = true;
            }

            if($this_notam_isGoodAirport)
            {

                if(strpos($this_notam_obj->GetText(), 'RWY') !== false || strpos($this_notam_obj->GetText(), 'TWY') !== false)
                {
                    $this_notam_isRelevant = true;
                }
                
                if($this_notam_isRelevant)
                {

			        if(!is_array($search))
			        {
				        //search is a string
				        if(strpos($this_notam_obj->GetText(), strtoupper($search)) !== false) $this_notam_isSearched = true;
			        }
			        else
			        {
				        //search is an array
				        foreach($search as $search_text)
				        {
					        if(strpos($this_notam_obj->GetText(), strtoupper($search_text)) !== false) $this_notam_isSearched = true;
				        }
			        }
            
                    //var_dump($this_notam_obj->GetText());
                    //var_dump($this_notam_isGoodAirport);
                    //var_dump($this_notam_isSearched);


			        // Check if the Notam is actually for the searched airport
			        if($this_notam_isSearched && $this_notam_isGoodAirport && $this_notam_isRelevant)
			        {
				        // Check if Notam has already been displayed

					        // Variables
					        $classes = 'CANotAPI_Notam';
					
					        // Check if Notam contains validity times
					        //if(isset( $this_notam_active_text[0] ))
					        //{
						        // Variables
						        //$this_notam_active_begin = substr($this_notam_active_text[0], 0, 10);
						        //$this_notam_active_end = substr($this_notam_active_text[0], -10);
						
						        // Check if Notam is active, not active, or active soon.
						        //if($this_notam_active_begin < $time_now and $time_now < $this_notam_active_end) {
							        // Notam is active
							        //$classes .= ' CANotAPI_Notam_active';
						        //} elseif ($this_notam_active_begin < $time_soon and $time_soon < $this_notam_active_end) {
							        // Notam is active soon
							        //$classes .= ' CANotAPI_Notam_soonActive';
						        //} else {
							        // Notam is not active
							        //$classes .= ' CANotAPI_Notam_inactive';
						        //}
					        //}
					        //else
					        //{
						        // Notam has no time specified
						        //$classes .= ' CANotAPI_Notam_timeUndef';
					        //}
					
                            if(strlen($this_notam_obj->GetText()) > 0)
                            {
					            // Add Notam to return string
					            $ret[] = $this_notam_obj;
                            }
				        //}
			        }
                }
            }
        }
        /*
		// Check every notams
		foreach($all_notams_indexes[0] as $key => $value)
		{
			// Variables
			$this_index = $all_notams_indexes[0][$key][1];
			$length = -1;
			if(isset($all_notams_indexes[0][$key+1])) $length = $all_notams_indexes[0][$key+1][1] - $this_index;
			$this_notam_id = +substr($formatted_text, $this_index, 6);
			$this_notam_text = substr($formatted_text, $this_index, $length);
			
			//Check if notam is wanted.
			$this_notam_isSearched = false;
			if(!is_array($search))
			{
				//search is a string
				if(strpos($this_notam_text, strtoupper($search))) $this_notam_isSearched = true;
			}
			else
			{
				//search is an array
				foreach($search as $search_text)
				{
					if(strpos($this_notam_text, strtoupper($search_text))) $this_notam_isSearched = true;
				}
			}
			
			// Eliminate notams from other airports
			$this_notam_isGoodAirport = preg_match('/(C[A-Z0-9]{3} [\/\-() A-Z0-9,.]+'.$airport.')/', $this_notam_text);
			
			// Check if the Notam is actually for the searched airport
			if($this_notam_isSearched && $this_notam_isGoodAirport)
			{
				// Check if Notam has already been displayed
				if(!isset($Already_Notam_List[$this_notam_id]))
				{
					// Variables
					$Already_Notam_List[$this_notam_id] = true;
					$classes = 'CANotAPI_Notam';
					preg_match('/[0-9]{10} TIL[ A-Z]+[0-9]{10}/', $this_notam_text, $this_notam_active_text);
					
					// Check if Notam contains validity times
					if(isset( $this_notam_active_text[0] ))
					{
						// Variables
						$this_notam_active_begin = substr($this_notam_active_text[0], 0, 10);
						$this_notam_active_end = substr($this_notam_active_text[0], -10);
						
						// Check if Notam is active, not active, or active soon.
						if($this_notam_active_begin < $time_now and $time_now < $this_notam_active_end) {
							// Notam is active
							$classes .= ' CANotAPI_Notam_active';
						} elseif ($this_notam_active_begin < $time_soon and $time_soon < $this_notam_active_end) {
							// Notam is active soon
							$classes .= ' CANotAPI_Notam_soonActive';
						} else {
							// Notam is not active
							$classes .= ' CANotAPI_Notam_inactive';
						}
					}
					else
					{
						// Notam has no time specified
						$classes .= ' CANotAPI_Notam_timeUndef';
					}
					
					// Add Notam to return string
					$ret .= '<span class="'.$classes.'">'.$this_notam_text.'</span><br><br>';
				}
			}
		}
        */
		// Add footer
		// Return array
		return $ret;
	}
	
	
    // Var(s)

?>