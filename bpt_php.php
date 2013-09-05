<?php

class BptApi {

	function produce_XML_object_tree($raw_XML) {
		libxml_use_internal_errors(true);
			try {
				$xmlTree = new SimpleXMLElement($raw_XML);
			}

			catch (Exception $e) {
			// Something went wrong.
				$error_message = '<strong>Error retrieving XML. Please check your Client ID and Developer ID.</strong><br /><br />';
				#foreach(libxml_get_errors() as $error_line) {
				#	$error_message .= "\n" . $error_line->message;
				#}
				trigger_error($error_message);
				return false;
			}
	return $xmlTree;
	}


	function event_list_call( $dev_id, $client_id, $event_id ) {

		if ( isset( $event_id ) ) {
			$event_call = 'https://www.brownpapertickets.com/api2/eventlist?id='.$dev_id.'&client='.$client_id.'&event_id='.$event_id;
		}

		else {

			$event_call = 'https://www.brownpapertickets.com/api2/eventlist?id='.$dev_id.'&client='.$client_id;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $event_call);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$event_list_xml = curl_exec($ch);
		curl_close($ch);

		$event_xml = produce_XML_object_tree($event_list_xml);

		$events = array();

		if ($event_xml->result == "fail" || strlen($event_xml) == 0) {
				return '<div class="event_data error">Error retrieving event info.</div>';
		}

		else {
			foreach ($event_xml->event as $event) {
				$event_id = $event->event_id;

					$single_event = array(
						'event_id'=>$event_id,
						'event_title'=>$event->title,
						'event_address1'=>$event->e_address1,
						'event_address2'=>$event->e_address2,
						'event_city'=>$event->e_city,
						'event_state'=>$event->e_state,
						'event_zip'=>$event->e_zip,
						'short_description'=>$event->description,
						'full_description'=>$event->e_description,
						'dates'=>array( date_list_call( $dev_id, $event_id, $client_id ) )
					);

					$events[] = $single_event;
			}
		}
		return $events;
	}
function date_list_call($dev_id, $event_id) {

		$date_call = 'https://www.brownpapertickets.com/api2/datelist?id='.$dev_id.'&event_id='.$event_id;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $date_call);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$date_list_xml = curl_exec($ch);
		curl_close($ch);

		$date_xml = produce_XML_object_tree($date_list_xml);

		$dates = array();

		foreach ($date_xml->date as $date) {

			$date_id = $date->date_id;

			if ($date_xml->totaldates <= 1 and $date->live =="n") {

			}

			if ($date->live == "y") {

				$single_date = array(
					'date_id'=>$date_id,
					'date_start'=>$date->datestart,
					'date_end'=>$date->dateend,
					'time_start'=>$date->timestart,
					'time_end'=>$date->timeend,
					'date_available'=>$date->date_available,
					'prices'=>array( price_list_call( $dev_id, $event_id, $date_id ) )
				);

				$dates[] = $single_date;
			}
		}

		$dates = sort_by_key( $dates, 'date_start' );

		return $dates;

	}


	function price_list_call($dev_id, $event_id, $date_id) {

		$price_call = 'https://www.brownpapertickets.com/api2/pricelist?id='.$dev_id.'&event_id='.$event_id.'&date_id='.$date_id;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $price_call);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$price_list_xml = curl_exec($ch);
		curl_close($ch);

		$price_xml = produce_XML_object_tree($price_list_xml);

		$prices = array();

		foreach ($price_xml->price as $price) {

			$single_price = array(
				'price_id'=>$price->price_id,
				'price_name'=>$price->name,
				'price_value'=>$price->value,
				'price_service_fee'=>$price->service_fee,
				'price_venue_fee'=>$price->venue_fee,
				'price_live'=>$price->live,
			);

			$prices[] = $single_price;
		}

		$prices = sort_by_key( $prices, 'price_name' );

		return $prices;
	}

	function sort_by_key( $array, $key ) {

		//Loop through and get the values of our specified key
		foreach($array as $k=>$v) {
			$b[] = strtolower($v[$key]);
		}

		asort($b);

		foreach($b as $k=>$v) {
			$c[] = $array[$k];
		}

	return $c;

	}
}

?>