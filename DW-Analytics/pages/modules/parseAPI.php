<?php
	/**
	  * @uses parseInit to connect to Parse Database
	  */
	require_once ($_SERVER["DOCUMENT_ROOT"]."/pages/modules/parseInit.php");
	/**
	  * @uses vendor/parse/php-sdk/src/Parse/ For Namespaces
	  */
	use Parse\ParseObject;
	use Parse\ParseQuery;
	use Parse\ParseUser;
	use Parse\ParseException;

	class ParseAPI {
		/**
		 * @var String $eventId   This is set upon the users clicked. Set to the current Id by default.
		 * @var String $eventCode This is set when the eventId is set.
		 */
		var $eventId;
		var $eventCode;

		private function parseQuery($className) {
			return new ParseQuery($className);
		}

		/* ------------ Attendee Functions ------------ */
		private function getEventInfo($id) {
			$query = $this->parseQuery("Event");
			$query->equalTo("objectId", $id);
			return $query->first();
		}
		
		private function getNotification($savedNotification) {
			$query = $this->parseQuery("Notification");
			$query->equalTo("objectId", $savedNotification->get("notificationId"));
			return $query->find();
		}
		public function getTimezone($eventId = null) {
			if($eventId == null) $eventId = $_SESSION['eventId'];
			$timezone = $this->parseQuery("Event");
			$timezone->equalTo("objectId", $eventId);
			return $timezone->first()->get("timeZone");
		}
		/**
		 * @note This function is a recursive function to obtain all the events of the current user
		 * @param $id The Object Id of the user
		 * @param $skip Set to false by default but if the query exceeds 1000 results we need to skip queries for the next call
		 * @param $limit Set to 1 by default but this is a value that skips the number of results by this multiplied by 1000
		 * @param $beacon_passed Set to NULL by default but is the array that contains all the events from the previous query
		 * @param $counter Set to 0 by default but contains the number of objects obtained from the query
		 * @param $last Set to false by default but if there is nothing left to query it is set to true to stop the function
		 * @return Array of event ids
		 */
		private function getAllEventsForUser($userId, $skip = false, $limit = 1, $events_passed = NULL, $counter = 0, $last = false) {
			$all_events = ($events_passed == NULL) ? array() : $events_passed;
			$query = $this->parseQuery("BeaconInteraction");
			$query->equalTo("userId", $userId);
			if($skip)
				$query->skip($limit*1000);
			$query->limit(1000);
			$results = $query->find();
			foreach($results as $res) {
				$counter++;
				if(!in_array($res->get("eventId"), $all_events))
					array_push($all_events, $res->get("eventId"));
			}
			if($counter >= 1000) {
				$value = $counter / 1000;
				if(floor($value) == $value && !$last)
					return $this->getAllEventsForUser($userId, true, $value, $all_events, $counter, false);
				else if(floor($value) != $value && !$last)
					return $this->getAllEventsForUser($userId, true, $value, $all_events, $counter, true);
				else if($last){
					return $all_events;
				}
			} else {
				return $all_events;
			}
		}
		public function buildEventsTableAttendee($userId) {
			$allEvents = $this->getAllEventsForUser($userId);
			$html = "";
			if($allEvents == null) return;
			for ($i = 0; $i < count($allEvents); $i++) {
				$event = $this->getEventInfo($allEvents[$i]);
				$html .= "<tr data-href='/Organizer/attendee/".$name."'>"
						."<td>". $event->get("name") . "</td>"
						."<td>". $event->get("startDate")->format("Y-m-d") . "</td>"
						."<td>". $event->get("location") . "</td>"
						."<td>". $event->get("city") . "</td>"
						."<td><a href='/Organizer/attendee/".$event->get("name").'/'.$userId."'>Library</a></td>"
						."</tr>";
			}
			return $html;
		}

		public function buildSpecificEventInfo($eventName) {
			$eventId = $this->getEventId($eventName);
			$query = $this->parseQuery("Event");
			$query->equalTo("objectId", $eventId);
			$speaker_session = $this->parseQuery("Session");
			$speaker_session->equalTo("eventId", $eventId);
			$speakers = $speaker_session->find();
			$allSpeakers = array();
			foreach ($speakers as $speak) {
				array_push($allSpeakers, $this->getSpeakerInfo($speak->get("speakerId")));
			}
			return array(
				'session' 	=> $query->find(),
				'speaker'	=> $allSpeakers
			);
		}

		public function buildContentLibrary($userId, $event) {
			$eventId = $this->getEventId($event);
			$content = $this->parseQuery("SavedNotification");
			$content->equalTo("userId", $userId);
			$content->equalTo("eventId", $eventId);
			$results = $content->find();
			for ($i = 0; $i < count($results); $i++) {
				$notification = $this->getNotification($results[$i]);
				$html .=  "<tr>"
						 ."<td>". $notification[0]->get('title') . "</td>"
						 ."<td><a target='_blank' href='". $notification[0]->get('pdfFile')->getURL() . "'>download</a></td>"
						 ."<td><a target='_blank' href='". $notification[0]->get("url") . "'>visit</a></td>"
						 . "</tr>";
			}
			return $html;
		}
		/* ------------ Organizer Functions ------------ */

		/* Getter functions */
		// Temp public
		public function getEventLocationDate($id) {
			$event_info = $this->getEventInfo($id);
			$city = $event_info->get("city");
			$state = $event_info->get("state");
			$date = $event_info->get("startDate")->format('Y-m-d');
			$locn_date = "\"".$city.", ".$state.", ".$date."\"";
			return $locn_date;
		}
		public function getEventName($id) {
			if($id == "Default") return;
			$query = $this->parseQuery("Event");
			$query->equalTo("objectId", $id);
			return $query->first()->get("name");
		}
		private function getEventId($eventName) {
			$query = $this->parseQuery('Event');
			$query->equalTo("name", $eventName);
			return $query->first()->getObjectId();
		}
		private function getAllBeacons() {
			$query = $this->parseQuery("Beacon");
			return $query->find();
		}
		public function allBeaconsAtEvent($id) {
			$query = $this->parseQuery("BeaconInteraction");
			$query->select(['beaconId', 'eventId', 'timeIn']);
			$query->equalTo('eventId', $id);
			$query->limit(1000);
			return $query->find();
		}
		public function allBeaconsEventDetails($id) {
		    $query = $this->parseQuery("Beacon");
			$query->equalTo("eventId", $id);
			$query->select(['objectId']);
			$query->select(['name']);
			$query->select(['companyId']);
			return $query->find();	
		}
		
		public function allBeaconsForExhibitor($eventId, $exhibitorId) {
		    $query = $this->parseQuery("Beacon");
			$query->equalTo("eventId", $eventId);
			$query->equalTo("companyId", $exhibitorId);
			$query->select(['objectId']);
			$query->select(['name']);
			$query->select(['companyId']);
			return $query->find();		
		}
		public function allBeaconsEvent($id) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("eventId", $id);
			$query->select(['objectId']);
			return $query->find();
		}
		private function getBeaconCurrentEvent($id) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("objectId", $id);
			return $query->first()->get("eventId");
		}
		
		/*
		 * $id - beacon id
		 * returns all interactions for that beacon
		 */
		private function getBeaconInteractionsForBeacon($id) {
		    $query = $this->parseQuery("BeaconInteraction");
		    $query->equalTo("beaconId", $id);
		    return $query->find();	
		}
		
		
		
		/**
		 * @note This function is a recursive function to obtain all the beacons at the event by their ids
		 * @param $id The event Id To query for - This is the only param required
		 * @param $skip Set to false by default but if the query exceeds 1000 results we need to skip some querys for the next call
		 * @param $limit Set to 1 by default but this is a value that skips the number of results by this multiplied by 1000
		 * @param $beacon_passed Set to NULL by default but is the array that contains all the beacons from the previous query
		 * @param $counter Set to 0 by default but contains the number of objects obtained from the query
		 * @param $last Set to false by default but if there is nothing left to query it is set to true to stop the function
		 * @return Array of beacons at the event
		 */
		private function getAllBeaconsNew($id, $skip = false, $limit = 1, $beacon_passed = NULL, $counter = 0, $last = false) {
			$query = $this->parseQuery("BeaconInteraction");
			$query->equalTo("eventId", $id);
			$query->select(['beaconId']);
			$beacon_array = ($beacon_passed == NULL) ? array() : $beacon_passed;
			$query->limit(1000);
			if($skip)
				$query->skip($limit*1000);
			$results = $query->find();
			foreach($results as $res) {
				$counter++;
				if(!in_array($res->get("beaconId"), $beacon_array))
					array_push($beacon_array, $res->get("beaconId"));
			}
			if($counter >= 1000) {
				$value = $counter / 1000;
				if(floor($value) == $value && !$last)
					return $this->getAllBeaconsNew($id, true, $value, $beacon_array, $counter, false);
				else if(floor($value) != $value && !$last)
					return $this->getAllBeaconsNew($id, true, $value, $beacon_array, $counter, true);
				else if($last){
					return $beacon_array;
				}
			} else {
				return $beacon_array;
			}
		}
		private function getBeaconId($beacon) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("name", $beacon);
			$query->select(['objectId']);
			return $query->first()->getObjectId();
		}
		private function getBeaconIdForEvent($beacon,$eventId) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("name", $beacon);
			$query->equalTo("eventId", $eventId);
			$query->select(['objectId']);
			return $query->first()->getObjectId();
		}
		private function getBeaconInfo($id) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("objectId", $id);
			return $query->first();
		}
		private function getBeaconName($id) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("objectId", $id);
			return $query->first()->get("name");
		}
		public function getAllInteractionsOfBeacon($beacon, $event) {
			$admins = $this->discardAdminInteractions();
			$query = $this->parseQuery("BeaconInteraction");
			$query->equalTo("eventId", $event);
			$query->equalTo("beaconId", $beacon);
			if ($_SESSION['userRole'] == 'o') {
			$query->notContainedIn('userId', $admins);
			}
			$query->select(['beaconId','eventId', 'userId','timeIn','timeSpentMillis']);
			$query->limit(1000);
			return $query->find();
		}
		public function getUniqueInteractionsOfBeacon($beacon, $event) {
			$admins = $this->discardAdminInteractions();
			$query = $this->parseQuery("BeaconInteraction");
			$query->equalTo("eventId", $event);
			$query->equalTo("beaconId", $beacon);

			if ($_SESSION['userRole'] == 'o') {
			$query->notContainedIn('userId', $admins);
			}
			$query->select(['beaconId','userId','eventId', 'timeIn']);
			$query->limit(1000);
			return $query->find();
		}
		private function getUserInfo($userId) {
			$query = ParseUser::query();
			$query->equalTo("objectId", $userId);
			return $query->first();
		}
		private function getUsername($userId) {
			$regex = '/^[0-9]+$/';
			$user = $this->getUserInfo($userId);
			return $user ? preg_match($regex, $user->get('username')) || count($user->get('username')) > 20 ? $user->get("email") : $user->get("username") : 'No Username';
		}
		private function getQuestionName($questionId) {
			$query = $this->parseQuery("SurveyQuestion");
			$query->equalTo("objectId", $questionId);
			$query->select(['question']);
			return $query->first()->get("question");
		}
		private function getMaxTime($interactions) {
			$len = count($interactions) - 1;
			$max = -1;
			while($len) {
				$dateParseIn = $interactions[$len]->get("timeIn")->format("Y-m-d H:i:s");
				$formated_date = date('H', $dateParseIn);
				if($formated_date > $max) {
					$max = $formated_date;
				}
				$len--;
			}
			return $max;
		}
		public function isBeacon($beacon_name) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("name", $beacon_name);
			return ($query->count() > 0);
		}
		private function getSpecificSpeakerRatings($speakerId) {
			$default_users = $this->discardAdminInteractions();
			$query = $this->parseQuery("SurveyResponse");
			$query->containedIn("speakerId", [$speakerId]);
			$query->notContainedIn('userId', $default_users);
			$query->select(["rating"]);
			return  $query->find();
		}

		private function getSpeakerInfo($speakerId) {
			$query = $this->parseQuery("Speaker");
			$query->equalTo("objectId", $speakerId);
			$query->select(['objectId', 'firstName', 'lastName', 'avatar']);
			return $query->first();
		}
		private function getAllUsersAtEvent($eventId) {
			$query = ParseUser::query();
			$query->equalTo("currentEventId", $eventId);
			return $query->find();
		}
		
		private function getNonAdminUsers() {
			$users = ParseUser::query();
			//$users->exists();
			$users->equalTo("seed", null);
			$users->select(['objectId']);
			$results_array = array();
			$results = $users->find();
			foreach($results as $users) {
				    array_push($results_array, $users->getObjectId());
			}
			return $results_array;
		}
		
		private function arrayOfBeaconsAtEventSingle($allBeaconsAtEvent) {
			$allBeacons = array();
			$isRepeated = false;
			for($i = 0; $i < count($allBeaconsAtEvent); $i++) {
				if(count($allBeaconsAtEvent > 0)) {
					for ($j = 0; $j < count($allBeacons); $j++) {
						if($allBeacons[$j] == $allBeaconsAtEvent[$i]->get('beaconId')) {
							$isRepeated = true;
						}
					}
					if(!$isRepeated) {
						array_push($allBeacons, $allBeaconsAtEvent[$i]->get('beaconId'));
					}
					$isRepeated = false;
				} else {
					array_push($allBeacons, $allBeaconsAtEvent[$i]->get("beaconId"));
				}
			}
			return $allBeacons;
		}
		
		private function buildSessionAttendanceActual($eventId) {
			$session = $this->parseQuery("Session");
			$session->equalTo("eventId", $eventId);
			$session->select(['objectId', 'title', 'beaconId']);
			$result_session = $session->find();
			
			//$nonAdmin = $this->getNonAdminUsers();
			$admins = $this->discardAdminInteractions();
			
			$beacon_interactions = $this->parseQuery("BeaconInteraction");
			$beacon_interactions->select(['beaconId']);
			$beacon_interactions->equalTo('eventId', $eventId);
			//$beacon_interactions->containedIn('userId', $nonAdmin);
			$beacon_interactions->notContainedIn('userId', $admins);
			$interactions = 0;
			$returnedArray = array();
			for($i = 0; $i < count($result_session); $i++) {
				$interactions = $beacon_interactions->equalTo('beaconId', $result_session[$i]->get('beaconId'))->count();
				if($interactions > 0) {
					array_push($returnedArray, array('key' => substr($result_session[$i]->get('title'), 0, 25) . '...', 'value' => $interactions, 'company' => $result_session[$i]->get('title')));
				}
				$interactions = 0;
			}
			return $returnedArray;
		}
		
		private function buildSpeakerRankingActual($eventId) {
			$query = $this->parseQuery("Speaker");
			$query->equalTo("eventId", $eventId);
			$query->select(['objectId']);
			$query->select(['firstName']);
			$query->select(['lastName']);
			$query->select(['avatar']);
			$speakers = $query->find();
			$result_array = array();
			
			//$nonAdmin = $this->getNonAdminUsers();
			$admins = $this->discardAdminInteractions();
			
			$query = $this->parseQuery("SurveyResponse");
			//echo var_dump($speakers);
			//echo var_dump($nonAdmin);
			foreach($speakers as $speaker) {
				$query->equalTo("speakerId", $speaker->getObjectId());
				$query->notContainedIn("userId", $admins);
				//$query->containedIn("userId", $nonAdmin);
				$query->select(['rating']);
				$results = $query->find();
				$average = 0;
				foreach($results as $rating) {
					$average += $rating->get("rating");
				}
				//echo var_dump($speaker->get("firstname"));
				//echo var_dump($average);
				if($average) {
					//$person = $this->getSpeakerInfo($speaker->getObjectId());
					array_push($result_array, array('key' => $speaker->get("firstName") . ' ' . $speaker->get("lastName"), 'value' => round($average/count($results), 2, PHP_ROUND_HALF_DOWN), 'img' => $speaker->get("avatar") ? $speaker->get("avatar")->getURL() : '	/assets/img/avatar2.png'));
				}
			}
			//echo var_dump($result_array);
			return $result_array;
		}
		
		private function buildSpeakerAttendanceActual($eventId) {

			$query = $this->parseQuery("Speaker");
			$query->equalTo("eventId", $eventId);
			$query->select(['objectId']);
			$query->select(['firstName']);
			$query->select(['lastName']);
			$query->select(['avatar']);
			$speakerList = $query->find();

			$returnedArray = array();
			foreach ($speakerList as $speak){
				$query = $this->parseQuery("Session");
				$query->equalTo("eventId", $eventId);
				$query->equalTo("speakerIds", $speak->getObjectId());
				$query->select(['objectId']);
				$query->select(['beaconId']);
				$result_session = $query->first();
				//$results = array();

				$query = $this->parseQuery("BeaconInteraction");
				$query->select(['userId']);
				$query->equalTo('eventId', $eventId);
				$query->equalTo('beaconId', $result_session->get('beaconId'));
				$query->equalTo("sessionId", $result_session->getObjectId());
				$results = $query->find();
				$user_array = array();
					
				//  unique users
				foreach($results as $res){
					array_push($user_array,$res->get('userId'));
					$user_array= array_unique($user_array);
				//  echo var_dump($user_array);
				}
				if(count($user_array)>0){
					array_push($returnedArray, array('key' => $speak->get("firstName") . ' ' . $speak->get("lastName"), 'value' =>count($user_array)));
				}

			}

			return $returnedArray;
		}
			
			

			
			/*$query = $this->parseQuery("Session");
			$query->equalTo("eventId", $eventId);
			//$query->equalTo("speakerIds", $speaker->getObjectId());
			$query->containedIn("speakerIds", $speakerId_array);
			$query->select(['objectId', 'beaconId', 'speakerIds']);
			$result_session = $query->find();
			
			
			
			$session_users = array();
			$query = $this->parseQuery("BeaconInteraction");
			foreach($result_session as $sess) {
				$query->select(['userId']);
				$query->equalTo('eventId', $eventId);
				$query->equalTo('beaconId', $sess->get('beaconId'));
				$query->equalTo("sessionId", $sess->getObjectId());
				$results = $query->find();
				
				//Get the unique users count
				$user_array = array();
				$user_array = array_unique($results);
				
				array_push($session_users, array('key' => $sess->getObjectId(), 'value' => $user_array));
			}
				
		$query = $this->parseQuery("Speaker");
			$query->equalTo("eventId", $eventId);
			$query->select(['objectId']);
			$query->select(['firstName']);
			$query->select(['lastName']);
			$query->select(['avatar']);
			$speakerList = $query->find();
		//echo var_dump($speakerList);
		    
			$query = $this->parseQuery("Session");
        foreach($speakerList as $speaker){
			$query->equalTo("eventId", $eventId);
			$query->equalTo("speakerIds", $speaker->getObjectId());
			$query->select(['objectId', 'beaconId', 'speakerIds']);
			$result_session = $query->find();
			//echo var_dump($result_session);
			$query = $this->parseQuery("BeaconInteraction");
		//foreach($result_session as $session){
			//$nonAdmin = $this->getNonAdminUsers();
			//(to be uncommented)$admins = $this->discardAdminInteractions();
			for($i = 0; $i < count($result_session); $i++) {
			$query->select(['userId']);
			$query->equalTo('eventId', $eventId);
			$query->equalTo('beaconId', $result_session[$i]->get('beaconId'));
			$query->equalTo("sessionId", $result_session[$i]->getObjectId());
			$results = $query->count();
			echo var_dump($results);
			//$beacon_interactions->containedIn('userId', $nonAdmin);	
			//(to uncomment)$beacon_interactions->notContainedIn('userId', $admins);
			//$results = 0;
			$returnedArray = array();
			
			
			//for($i = 0; $i < count($result_session); $i++) {
			//$results = $beacon_query->equalTo('beaconId', $result_session[$i]->get('beaconId'))->count();
				
			
				if($results > 0) {
					array_push($returnedArray, array('key' => $speaker->get("firstName") . ' ' . $speaker->get("lastName"), 'value' => $results));
				}
				$results = 0;
			}}
			return $returnedArray;
		}
		
			
			
			
			
		
		/*$query = $this->parseQuery
		 * "Speaker");
			$query->equalTo("eventId", $eventId);
			$query->select(['objectId']);
			$query->select(['firstName']);
			$query->select(['lastName']);
			$query->select(['avatar']);
			$speakers = $query->find();
			$result_array = array();
			
			//$nonAdmin = $this->getNonAdminUsers();
			//(To uncomment)$admins = $this->discardAdminInteractions();
			
			$query = $this->parseQuery("SurveyResponse");
			//echo var_dump($speakers);
			//echo var_dump($nonAdmin);
			foreach($speakers as $speaker) {
				$query->equalTo("speakerId", $speaker->getObjectId());
				//(To uncomment)$query->notContainedIn("userId", $admins);
				//$query->containedIn("userId", $nonAdmin);
				$query->select(['userId']);
				$results = $query->count();
				$average = 0;
				//echo var_dump($speaker->get("firstname"));
				//echo var_dump($average);
				if($results > 0) {
					//$person = $this->getSpeakerInfo($speaker->getObjectId());
					array_push($result_array, array('key' => $speaker->get("firstName") . ' ' . $speaker->get("lastName"), 'value' => $results, 'img' => $speaker->get("avatar") ? $speaker->get("avatar")->getURL() : '	/assets/img/avatar2.png'));
				}
			}
			//echo var_dump($result_array);
			return $result_array;
		}
		*/
		/*
		 * $id - event id
		 * $userid - exhibitor id
		 */
		public function buildBeaconsInteractionsForExhibitorEvent($id, $userId) {
			$allInteractionsOut = array();
			//$admins = $this->discardAdminInteractions();
				
			/*
			 * Get exhibitor id using event id and user id
			 */
			$query = $this->parseQuery("UserExhibitorRoles");
			$query->equalTo("UserId", $userId);
			$query->equalTo("EventId", $id);
			$query->limit(1);
			$userExhibitor = $query->first();
			if ($userExhibitor) {
				/*
				 * we can get the exhibitor id
				 */
				$exhibitorId = $userExhibitor->get("ExhibitorId");

				$allInteractions = array();

				$allBeaconsDetails = $this->allBeaconsForExhibitor($id, $exhibitorId);
				//echo var_dump($allBeaconsDetails);
					
				/////////////////////////////////
					$user_array = array();
					$total_visitors = array();
                    foreach($allBeaconsDetails as $becon) {
					$allInteractions = $this->getAllInteractionsOfBeacon($becon->getObjectId(), $id);
                    }
						foreach ($allInteractions as $total){
							array_push($user_array,$total->get('userId'));
							//echo var_dump($user_array);
							$user_array= array_unique($user_array);
							$dwell_time = 0;
							$dwell_time += ($total->get('timeSpentMillis'))/1000;
							//echo var_dump($dwell_time);
							//$totalNumber = count($total_visitors);
						}
						$Average_Dwell_Time = ($dwell_time)/(count($total));
						
						echo ("Total # of Booth Visitors : " .count($user_array)."<br>");
						echo ("Average Visitor Dwell Time : " .$Average_Dwell_Time . " secs"."<br>");
						//echo ("\n");
						//echo "<br>";
						

			/////////////////////////////////////
					foreach($allBeaconsDetails as $becon) {
					$allInteractions = $this->getAllInteractionsOfBeacon($becon->getObjectId(), $id);
					//echo var_dump(count($allInteractions));
				
					
					$event_info = $this->getEventInfo($id);
					//$real_t = $this->getTimezone($eventId);
					$real_t = $event_info->get("timeZone");
					$interactions = 0;
					$all_int = array();
					 
					for($i = 0; $i < count($allInteractions); $i++) {
						$dateParseIn = $allInteractions[$i]->get("timeIn")->format("Y-m-d H:i:s");
						$dateParseIn = $this->_settimezone($dateParseIn, $real_t);
						$next_time = strtotime($dateParseIn);
						$dateParseIn = (date('H', $next_time));
					//	echo var_dump($dateParseIn);
						if($dateParseIn >= 7 && $dateParseIn <= 20) {
							$interactions++;
						}
					}//maxtime <20 to >8
				//		echo var_dump($interactions);	
					if ($interactions > 0) {
						array_push($allInteractionsOut, array('key' => $becon->get("name"), 'value' => $interactions, 'company' =>  ' ('. $this->getCompanyNameForExhibitor($becon->get('companyId')). ')'));
					}
				}
			}
			return $allInteractionsOut;
		}
		
		/*
		 * $id - event id for which top interactions are needed
		 */
		public function buildBeaconsInteractionsForEvent($id) {
			$interactions = 0;
			$allInteractions = array();
			if ($_SESSION['userRole'] == 'o') {
			  $admins = $this->discardAdminInteractions();
			}
				
			$allBeaconsDetails = $this->allBeaconsEventDetails($id);
			//echo var_dump($allBeaconsDetails);	
			$allBeaconsDetailsAtEvent = array();
			foreach($allBeaconsDetails as $becon) {
				$BeaconInteractionQ = $this->parseQuery("BeaconInteraction");
				$BeaconInteractionQ->equalTo("beaconId", $becon->getObjectId());
				//$BeaconInteractionQ->equalTo("companyId", $becon->get('companyId'));
				$BeaconInteractionQ->equalTo("eventId", $id);
				if ($_SESSION['userRole'] == 'o') {
				    $BeaconInteractionQ->notContainedIn("userId", $admins);
				}
				$interactions = $BeaconInteractionQ->count();
				//$company = $becon->get('companyId');
				//$companyName = $this->getCompanyName($company);
				//echo var_dump($companyName);
				if ($interactions > 0) {
					if($_SESSION['userRole'] == 'o'){
						array_push($allInteractions, array('key' => $becon->get("name"), 'value' => $interactions, 'company' =>  ' ('. $this->getCompanyName($becon->get('companyId')). ')'));
					}
					elseif($_SESSION['userRole'] == 'e'){
						array_push($allInteractions, array('key' => $becon->get("name"), 'value' => $interactions, 'company' =>  ' ('. $this->getCompanyNameForExhibitor($becon->get('companyId')). ')'));
					}
				}
				//array_push($allInteractions, array('key' => $becon->get("name"), 'value' => $interactions));
			}
			return $allInteractions;
		}
		
	    /*
		 * $id - event id for which top interactions are needed
		 */
		public function buildTopInteractions($id) {
			//echo "Krsna inside buildTopInteractions";
			//$interactions = 0;
			$allInteractions = array();
			$html = '';
				
			//$allBeaconsAtEvent = $this->getAllBeaconsNew($id);
			//$nonAdmin = $this->getNonAdminUsers();
			//$admins = $this->discardAdminInteractions();
			//echo count($nonAdmin);
			//echo var_dump($nonAdmin);
			
			/*
			 * Avoid the above recursive call, rather we can
			 * get it easily by query beacon table and not
			 * beacon interaction
			 */
			
			//$allBeaconsDetails = $this->allBeaconsEventDetails($id);
			//echo var_dump($allBeaconsDetails);
			
			//$allBeaconsDetailsAtEvent = array();
			//foreach($allBeaconsDetails as $becon) {
				//$BeaconInteractionQ = $this->parseQuery("BeaconInteraction");
				//$BeaconInteractionQ->equalTo("beaconId", $becon->getObjectId());
				//$BeaconInteractionQ->equalTo("eventId", $id);
				//$BeaconInteractionQ->containedIn("userId", $nonAdmin);
				//$BeaconInteractionQ->notContainedIn("userId", $admins);
				//$interactions = $BeaconInteractionQ->count();
				//array_push($allInteractions, array('key' => $becon->get("name"), 'value' => $interactions));
			//}
			
			//$allBeacons = $this->allBeaconsEvent($id);
			//echo get("title");
			//$allBeaconsAtEvent = array();
			//foreach($allBeacons as $beacon) {
				//array_push($allBeaconsAtEvent, $beacon->getObjectId());
			//}
			
			
				
			//$allBeaconsDetails = $this->allBeaconsEventDetails($id);
			$allInteractions = $this->buildBeaconsInteractionsForEvent($id);
			if (count($allInteractions) == 0) {
				return $html;
			}
			//echo var_dump($allInteractions);
			$interactionCount = array();
			/*foreach($allBeaconsAtEvent as $beacon) {
				$beacon_info = $this->getBeaconInfo($beacon);
				$BeaconInteractionQ = $this->parseQuery("BeaconInteraction");
				$BeaconInteractionQ->equalTo("beaconId", $beacon);
				$BeaconInteractionQ->equalTo("eventId", $id);
				//$BeaconInteractionQ->notContainedIn("userId", $this->discardAdminInteractions());
				$interactions = $BeaconInteractionQ->count();
				array_push($allInteractions, array('key' => $beacon_info->get("name"), 'value' => $interactions));
			}
			
			echo "Radhe";
			echo var_dump($allInteractions);*/
			
			foreach ($allInteractions as $key => $row) {
				$interactionCount[$key] = $row['value'];
			}
			array_multisort($interactionCount, SORT_DESC, $allInteractions);

			
			$html = '<div class="col-md-4"><div class="panel panel-danger"><div class="panel-heading">';
			$html .= '<h3 class="panel-title"><em>Event Traffic</em> </h3></div>';
			$html .= '<div class="panel-body">';
			//$html .= '<table id="records" class="table table_pick datatable display table-hover">';
			$html .= '<table id="records-top-int" class="table table_pick datatable display no-pointer">';
			$html .= '<thead><tr></tr></thead><tbody>';
			//$html = '<table><thead><tr></tr></thead><tbody>';
			/*
			 * $allInteractions is map of beacons and its interactions count
			 */
			$beaconHighInteractions = array();
			$ind = 0;
			foreach ($allInteractions as $interact) {
				$ind++;
				array_push($beaconHighInteractions, $interact['key']);
			    $html .= '<tr><td><em>'.$interact['key'].'</em></td><td><em>'.$interact['value'].'</em></td></tr>';
			    if ($ind == 5)
			        break;	
			}
			$html .= '</tbody></table>';
			$url = '/Organizer/Admin/analytics/'.$id.'/Beacons';
			$html .= '<a href="'.$url.'">view all</a>';
			$html .= '</div></div></div>';
			
			if (count($beaconHighInteractions) == 0) {
				$html = '';
			}
			
			//echo var_dump($this->buildEventInteraction($id));
			//echo var_dump($beaconHighInteractions);
			return $html;
			//return $beaconHighInteractions;
		}
		
		public function buildTopExhibitorBeaconInteractions($eventId, $userId) {
						
			$html = '';

			$allInteractions = $this->buildBeaconsInteractionsForExhibitorEvent($eventId, $userId);
            //echo var_dump($allInteractions);
			
			if (count($allInteractions) == 0) {
				return $html;
			}
			
							
			
			$interactionCount = array();
			foreach ($allInteractions as $key => $row) {
				$interactionCount[$key] = $row['value'];
			}

			array_multisort($interactionCount, SORT_DESC, $allInteractions);

			$html = '<div class="col-md-4"><div class="panel panel-danger"><div class="panel-heading">';
			$html .= '<h3 class="panel-title"><em>Booth Traffic</em> </h3></div>';
			$html .= '<div class="panel-body">';
			//$html .= '<table id="records" class="table table_pick datatable display table-hover">';
			$html .= '<table id="records-top-int" class="table table_pick datatable display no-pointer">';
			$html .= '<thead><tr></tr></thead><tbody>';
			//$html = '<table><thead><tr></tr></thead><tbody>';
			/*
			 * $allInteractions is map of beacons and its interactions count
			 */
			$beaconHighInteractions = array();
			$ind = 0;
			foreach ($allInteractions as $interact) {
				$ind++;
				array_push($beaconHighInteractions, $interact['key']);
				$html .= '<tr><td><em>'.$interact['key'].'</em></td><td><em>'.$interact['value'].'</em></td></tr>';
				if ($ind == 5)
				break;
			}

			$html .= '</tbody></table>';
			$url = '/Exhibitor/Admin/analytics/'.$eventId.'/Beacons';
			$html .= '<a href="'.$url.'">view all</a>';
			$html .= '</div></div></div>';

			if (count($beaconHighInteractions) == 0) {
				$html = '';
			}

			//echo var_dump($beaconHighInteractions);

			return $html;
		}
		
	public function buildTopVisitorsByInteractions($eventId, $userId) {
			$html = '';

			$allInteractions = $this->buildVisitorInteractionsForExhibitorEvent($eventId, $userId);
            	//$allInteractions = $this->buildBeaconsInteractionsForExhibitorEvent($eventId, $userId);
			//echo var_dump($allInteractions);
			
			if (count($allInteractions) == 0) {
				return $html;
			}

			$interactionCount = array();
			foreach ($allInteractions as $key => $row) {
				$interactionCount[$key] = $row['value'];
			}

			array_multisort($interactionCount, SORT_DESC, $allInteractions);

			$html = '<div class="col-md-4"><div class="panel panel-danger"><div class="panel-heading">';
			$html .= '<h3 class="panel-title"><em>Number of Visitors</em> </h3></div>';
			$html .= '<div class="panel-body">';
			//$html .= '<table id="records" class="table table_pick datatable display table-hover">';
			$html .= '<table id="records-top-int" class="table table_pick datatable display no-pointer">';
			$html .= '<thead><tr></tr></thead><tbody>';
			//$html = '<table><thead><tr></tr></thead><tbody>';
			/*
			 * $allInteractions is map of beacons and its interactions count
			 */
			$beaconHighInteractions = array();
			$ind = 0;
			foreach ($allInteractions as $interact) {
				$ind++;
				array_push($beaconHighInteractions, $interact['key']);
				$html .= '<tr><td><em>'.$interact['key'].'</em></td><td><em>'.$interact['value'].'</em></td></tr>';
				
		    	if ($ind == 5)
				break;
			}

			$html .= '</tbody></table>';
			$url = '/Exhibitor/Admin/analytics/'.$eventId.'/Visitors';
			$html .= '<a href="'.$url.'">view all</a>';
			$html .= '</div></div></div>';

			if (count($beaconHighInteractions) == 0) {
				$html = '';
			}

			//echo var_dump($beaconHighInteractions);
			
			return $html;
		}
		
		public function cmp($a, $b) {
  			 if($a['userId'] == $b['userId'])
  			  return 0;
  			  else
			  return ($a['userId']<$b['userId'])?-1:1;
			}


		
		public function buildVisitorInteractionsForExhibitorEvent($id, $userId) {
			$allInteractionsOut = array();
			//$admins = $this->discardAdminInteractions();
				
			/*
			 * Get exhibitor id using event id and user id
			 */
			$query = $this->parseQuery("UserExhibitorRoles");
			$query->equalTo("UserId", $userId);
			$query->equalTo("EventId", $id);
			$query->limit(1);
			$userExhibitor = $query->first();
			if ($userExhibitor) {
				/*
				 * we can get the exhibitor id
				 */
				$exhibitorId = $userExhibitor->get("ExhibitorId");

				$allInteractions = array();

				$allBeaconsDetails = $this->allBeaconsForExhibitor($id, $exhibitorId);
				//echo var_dump($allBeaconsDetails);
					$unique_users = array();
					$user_names = array();
				foreach($allBeaconsDetails as $becon) {
					$allInteractions = $this->getAllInteractionsOfBeacon($becon->getObjectId(), $id);
					usort($allInteractions,"cmp");
					//echo var_dump($allInteractions);
				foreach($allInteractions as $visitor){
					array_push($user_names,$visitor->get("userId"));
			       // echo var_dump($user_names);
				
					$unique_visitors = array_unique($user_names);
					//echo var_dump($unique_visitors);
				}
					$event_info = $this->getEventInfo($id);
					//$real_t = $this->getTimezone($eventId);
					$real_t = $event_info->get("timeZone");
					$interactions = 0;
					$all_int = array();
					
						for($i = 0; $i < count($allInteractions); $i++) {
						$dateParseIn = $allInteractions[$i]->get("timeIn")->format("Y-m-d H:i:s");
						$dateParseIn = $this->_settimezone($dateParseIn, $real_t);
						$next_time = strtotime($dateParseIn);
						$dateParseIn = (date('H', $next_time));
					//	echo var_dump($dateParseIn);
						if($dateParseIn >= 7 && $dateParseIn <= 20 && ($allInteractions->get['userId'])==($unique_visitors->get['userId'])) {
							$interactions++;
						}
					}
					//maxtime <20 to >8
				   //echo var_dump($interactions);
		
					if (($interactions) > 0) {
						array_push($allInteractionsOut, array('key' => $becon->get("name"), 'value' =>count($unique_visitors), 'company' =>  ' ('. $this->getCompanyNameForExhibitor($becon->get('companyId')). ')'));
					}}
				}
			
			
			return $allInteractionsOut;
		}
		
		
		public function buildTopSpeakersByAttendance($id) {
			$speakerAttendance = array();
			$html = '';
		    $allSpeakers = $this->buildSpeakerAttendanceActual($id);
		    if (count($allSpeakers) == 0) {
		    	return $html;
		    }

		    foreach ($allSpeakers as $key => $row) {
				$speakerAttendance[$key] = $row['value'];
			}
			
			array_multisort($speakerAttendance, SORT_DESC, $allSpeakers);
			
			
			$html = '<div class="col-md-4"><div class="panel panel-danger"><div class="panel-heading">';
			$html .= '<h3 class="panel-title"><em>Top Speakers by Attendance</em> </h3></div>';
			$html .= '<div class="panel-body">';
			$html .= '<table id="records-top-speakers" class="table table_pick datatable display no-pointer">';
			$html .= '<thead><tr></tr></thead><tbody>';
			/*
			 * $allSessionAttendance is map of session and its attendance count
			 */
			$topSpeakers = array();
			$ind = 0;
			foreach ($allSpeakers as $speaker) {
				$ind++;
				array_push($topSpeakers, $speaker['key']);
			    $html .= '<tr><td><em>'.$speaker['key'].'</em></td><td><em>'.$speaker['value'].'</em></td></tr>';
			    if ($ind == 5)
			        break;	
			}
			
			$html .= '</tbody></table>';
			$url = '/Exhibitor/Admin/analytics/'.$id.'/SpeakersByAttendance';
			$html .= '<a href="'.$url.'">view all</a>';
			$html .= '</div></div></div>';
			
			if (count($topSpeakers) == 0) {
				$html = '';
			}
			
			return $html;
		}
		
		/*
		 * $id - event id for which top session attendances are needed
		 */
		public function buildTopSessionAttendance($id) {
			$attendanceCount = array();
			$html = '';
		    $allSessionAttendance = $this->buildSessionAttendanceActual($id);
		    if (count ($allSessionAttendance) == 0) {
		    	return $html;
		    }
		    
		    foreach ($allSessionAttendance as $key => $row) {
				$attendanceCount[$key] = $row['value'];
			}
			array_multisort($attendanceCount, SORT_DESC, $allSessionAttendance);
			
			
			$html = '<div class="col-md-4"><div class="panel panel-danger"><div class="panel-heading">';
			$html .= '<h3 class="panel-title"><em>Most Attended Sessions</em> </h3></div>';
			$html .= '<div class="panel-body">';
			$html .= '<table id="records-top-sessions" class="table table_pick datatable display no-pointer">';
			$html .= '<thead><tr></tr></thead><tbody>';
			/*
			 * $allSessionAttendance is map of session and its attendance count
			 */
			$sessionHighAttendance = array();
			$ind = 0;
			foreach ($allSessionAttendance as $session) {
				$ind++;
				array_push($sessionHighAttendance, $session['key']);
			    $html .= '<tr><td><em>'.$session['key'].'</em></td><td><em>'.$session['value'].'</em></td></tr>';
			    if ($ind == 5)
			        break;	
			}
			$html .= '</tbody></table>';
			$url = '/Organizer/Admin/analytics/'.$id.'/Sessions';
			$html .= '<a href="'.$url.'">view all</a>';
			$html .= '</div></div></div>';
			
			if (count($sessionHighAttendance) == 0) {
				$html = '';
			}
			return $html;
		}
		
		/*
		 * $id - event id for which top speaker ratings
		 */
		public function buildTopSpeakers($id) {
			$speakerRatings = array();
			$html = '';
		    $allSpeakers = $this->buildSpeakerRankingActual($id);
		    if (count($allSpeakers) == 0) {
		    	return $html;
		    }

		    foreach ($allSpeakers as $key => $row) {
				$speakerRatings[$key] = $row['value'];
			}
			
			array_multisort($speakerRatings, SORT_DESC, $allSpeakers);
			
			
			$html = '<div class="col-md-4"><div class="panel panel-danger"><div class="panel-heading">';
			$html .= '<h3 class="panel-title"><em>Top Speakers by Rank</em> </h3></div>';
			$html .= '<div class="panel-body">';
			$html .= '<table id="records-top-speakers" class="table table_pick datatable display no-pointer">';
			$html .= '<thead><tr></tr></thead><tbody>';
			/*
			 * $allSessionAttendance is map of session and its attendance count
			 */
			$topSpeakers = array();
			$ind = 0;
			foreach ($allSpeakers as $speaker) {
				$ind++;
				array_push($topSpeakers, $speaker['key']);
			    $html .= '<tr><td><em>'.$speaker['key'].'</em></td><td><em>'.$speaker['value'].'</em></td></tr>';
			    if ($ind == 5)
			        break;	
			}
			
			$html .= '</tbody></table>';
			$url = '/Organizer/Admin/analytics/'.$id.'/Speakers';
			$html .= '<a href="'.$url.'">view all</a>';
			$html .= '</div></div></div>';
			
			if (count($topSpeakers) == 0) {
				$html = '';
			}
			
			return $html;
		}
		
		/*
		 * $id - event id for which top notifications
		 */
		public function buildTopNotifications($id) {
			$html = '';
		    $query = $this->parseQuery("Notification");
			$allNotifications = array();
			$allBeacons = $this->allBeaconsEvent($id);
			//echo get("title");
			$beacon_array = array();
			foreach($allBeacons as $beacon) {
				array_push($beacon_array, $beacon->getObjectId());
			}
			if (count ($beacon_array) == 0) {
				return $html;
			}
			$query->containedIn("beaconId", $beacon_array);
			$results = $query->find();
			
			if (count($results) == 0) {
				return $html;
			}
			
			foreach($results as $not) {
				if ($not->get("title")) {
					array_push($allNotifications, array('key' => $not->get("title"), 'value' => $this->getNumberOfObjects('SavedNotification', $not->getObjectId(), 'notificationId','','')));
				}
			}
			
			if (count($allNotifications) == 0) {
				return $html;
			}
			
			$notificationCount = array();
			foreach ($allNotifications as $key => $row) {
				$notificationCount[$key] = $row['value'];
			}
			array_multisort($notificationCount, SORT_DESC, $allNotifications);

			
			$html = '<div class="col-md-4"><div class="panel panel-danger"><div class="panel-heading">';
			$html .= '<h3 class="panel-title"><em>Popular Content</em> </h3></div>';
			$html .= '<div class="panel-body">';
			$html .= '<table id="records-top-not" class="table table_pick datatable display no-pointer">';
			$html .= '<thead><tr></tr></thead><tbody>';
			/*
			 * $allNotifications is map of notification title and its saved notifications count
			 */
			$topNotifications = array();
			$ind = 0;
			foreach ($allNotifications as $notification) {
				$ind++;
				array_push($topNotifications, $notification['key']);
			    $html .= '<tr><td><em>'.$notification['key'].'</em></td><td><em>'.$notification['value'].'</em></td></tr>';
			    if ($ind == 5)
			        break;	
			}
			
			$html .= '</tbody></table>';
			$url = '/Organizer/Admin/analytics/'.$id.'/Notifications';
			$html .= '<a href="'.$url.'">view all</a>';
			$html .= '</div></div></div>';
			
			if (count($topNotifications) == 0) {
				$html = '';
			}
			return $html;
		}
		
		public function buildTopExhibitorNotifications($eventId, $userId) {
			$html = '';
			
			/*
			 * Get exhibitor id using event id and user id
			 */
			$query = $this->parseQuery("UserExhibitorRoles");
			$query->equalTo("UserId", $userId);
			$query->equalTo("EventId", $eventId);
			$query->limit(1);
			$userExhibitor = $query->first();
			if ($userExhibitor) {
				$exhibitorId = $userExhibitor->get("ExhibitorId");

				$allBeaconsDetails = $this->allBeaconsForExhibitor($eventId, $exhibitorId);
				
				$beacon_array = array();
				foreach($allBeaconsDetails as $beacon) {
					array_push($beacon_array, $beacon->getObjectId());
				}
				
				if (count ($beacon_array) == 0) {
					return $html;
				}
				
				$query = $this->parseQuery("Notification");
				$allNotifications = array();
				$query->containedIn("beaconId", $beacon_array);
				$results = $query->find();
					
				if (count($results) == 0) {
					return $html;
				}
				
				foreach($results as $not) {
					if ($not->get("title")) {
						array_push($allNotifications, array('key' => $not->get("title"), 'value' => $this->getNumberOfObjects('SavedNotification', $not->getObjectId(), 'notificationId','','')));
					}
				}
					
				if (count($allNotifications) == 0) {
					return $html;
				}
				
				$notificationCount = array();
				foreach ($allNotifications as $key => $row) {
					$notificationCount[$key] = $row['value'];
				}
				array_multisort($notificationCount, SORT_DESC, $allNotifications);

					
				$html = '<div class="col-md-4"><div class="panel panel-danger"><div class="panel-heading">';
				$html .= '<h3 class="panel-title"><em>Popular Content</em> </h3></div>';
				$html .= '<div class="panel-body">';
				$html .= '<table id="records-top-not" class="table table_pick datatable display no-pointer">';
				$html .= '<thead><tr></tr></thead><tbody>';
				/*
				 * $allNotifications is map of notification title and its saved notifications count
				 */
				$topNotifications = array();
				$ind = 0;
				foreach ($allNotifications as $notification) {
					$ind++;
					array_push($topNotifications, $notification['key']);
					$html .= '<tr><td><em>'.$notification['key'].'</em></td><td><em>'.$notification['value'].'</em></td></tr>';
					if ($ind == 5)
					break;
				}
					
				$html .= '</tbody></table>';
				$url = '/Exhibitor/Admin/analytics/'.$eventId.'/Notifications';
				$html .= '<a href="'.$url.'">view all</a>';
				$html .= '</div></div></div>';
					
				if (count($topNotifications) == 0) {
					$html = '';
				}
				
			}
			return $html;
		}
		
		/*
		 * Need to call this function in buildExhibitorInteractions 
		 * as this is redundant
		 */
		private function buildExhibitorInteractionsActual($eventId) {
			$company_array = $this->getAllExhibitors($eventId);
			$beacons_q = $this->parseQuery("Beacon");
			$beacons_q->containedIn("companyId", $company_array);
			$beacons_q->select(['name', 'objectId', 'companyId', 'logo']);
			$beacons = $beacons_q->find();
			$interactions = $this->parseQuery("BeaconInteraction");
			//$nonAdmin = $this->getNonAdminUsers();
			$admins = $this->discardAdminInteractions();
			//$interactions->containedIn('userId', $nonAdmin);
			$interactions->notContainedIn('userId', $admins);
			$interactions->equalTo("eventId", $eventId);
			if($interactions == null) return;
			$returnedArray = array();
			foreach($beacons as $beacon) {
				$interactions->equalTo("beaconId", $beacon->getObjectId());
				if (!$beacon->get('companyId')) {
					continue;
				}
				if($interactions->count()) {
					array_push($returnedArray, array('key' => $this->getCompanyName($beacon->get('companyId')), 'value' => $interactions->count(), 'img' => $this->getCompanyLogo($beacon->get("companyId"))));
				}
			}
			return $returnedArray;
		}
		
		/*
		 * $id - event id for which exhibitor interactions needed
		 */
		public function buildTopExhibitorInteractions($id) {
			//echo "<br> Krsna inside top exhibitor interactions</br>";
			//echo var_dump($id);
			//$this->buildExhibitorInteractionsTable("nJDkJOB5qw", "Halo Branded Solutios");
			$exhibitorInteractionCount = array();
			$html = '';
		    $allExhibitors = $this->buildExhibitorInteractionsActual($id);
		    //echo var_dump($allExhibitors);
		    
		    if (count($allExhibitors) == 0) {
		    	return $html;
		    }
 
		    foreach ($allExhibitors as $key => $row) {
				$exhibitorInteractionCount[$key] = $row['value'];
			}
			
			array_multisort($exhibitorInteractionCount, SORT_DESC, $allExhibitors);
			
			
			$html = '<div class="col-md-4"><div class="panel panel-danger"><div class="panel-heading">';
			$html .= '<h3 class="panel-title"><em>Exhibitor Interactions</em> </h3></div>';
			$html .= '<div class="panel-body">';
			$html .= '<table id="records-top-exhibit" class="table table_pick datatable display no-pointer">';
			$html .= '<thead><tr></tr></thead><tbody>';
			/*
			 * $allSessionAttendance is map of session and its attendance count
			 */
			$topExhibitors = array();
			$ind = 0;
			foreach ($allExhibitors as $exhibitor) {
				$ind++;
				array_push($topExhibitors, $exhibitor['key']);
			    $html .= '<tr><td><em>'.$exhibitor['key'].'</em></td><td><em>'.$exhibitor['value'].'</em></td></tr>';
			    if ($ind == 5)
			        break;	
			}
			
			//if (count($topExhibitors) == 0) {
				/*
				 * Build html table data as None, if there are no 
				 * exhibitor interactions
				 */
				/*for ($ind = 0; $ind < 5; $ind++) {
				    $html .= '<tr><td><em>None</em></td></tr>';
				}
			}*/
			
			$html .= '</tbody></table>';
			$url = '/Organizer/Admin/analytics/'.$id.'/Exhibitors-Interactions';
			$html .= '<a href="'.$url.'">view all</a>';
			$html .= '</div></div></div>';
			
			//echo var_dump($topExhibitors);
			if (count($topExhibitors) == 0) {
				$html = '';
			}
			return $html;
		    
		}
		
		/*
		 * $id - event id for which top content libraries needed
		 */
		public function buildTopContentLibrary($eventId) {
			$query = $this->parseQuery("Content");
			$html = '';
			 
			$query->equalTo("EventId", $type);
			//$query->equalTo("EventId", "A4wnxn5dd3");

			$results = $query->find();
			
			if (count($results) == 0) {
				return $html;
			}
		    $allContents = array();
			foreach($results as $content) {
				$pdf_count = $this->getNumberOfObjects('ContentTracking', $content->getObjectId(), 'ContentId', 'PDF', 'ContentType');
				$url_count = $this->getNumberOfObjects('ContentTracking', $content->getObjectId(), 'ContentId', 'URL', 'ContentType');
				
				if ($content->get("title")) {
				    array_push($allContents, array('key' => $content->get("title"), 'value' => ($pdf_count+$url_count)));
				}
			}
			
			$contentCount = array();
			foreach ($allContents as $key => $row) {
				$contentCount[$key] = $row['value'];
			}
				
			array_multisort($contentCount, SORT_DESC, $allContents);
				
			
			$html = '<div class="col-md-4"><div class="panel panel-danger"><div class="panel-heading">';
			$html .= '<h3 class="panel-title"><em>Your Top Content Library</em> </h3></div>';
			$html .= '<div class="panel-body">';
			$html .= '<table id="records-top-content" class="table table_pick datatable display no-pointer">';
			$html .= '<thead><tr></tr></thead><tbody>';
			/*
			 * $allContents is map of cotent title and its count of pdf and url
			 */
			$topContents = array();
			$ind = 0;
			foreach ($allContents as $content) {
				$ind++;
				array_push($topContents, $content['key']);
				$html .= '<tr><td><em>'.$content['key'].'</em></td><td><em>'.$content['value'].'</em></td></tr>';
				if ($ind == 5)
				break;
			}
			/*if ($ind < 5) {
				for($i = 0; $i < 5 - $ind; $i++) {
					$html .= '<tr><td><em>None</em></td></tr>';
				}
			}*/
			
			$html .= '</tbody></table>';
			$url = '/Organizer/Admin/analytics/'.$eventId.'/Content';
			$html .= '<a href="'.$url.'">view all</a>';
			$html .= '</div></div></div>';
			
			if (count($topContents) == 0) {
				$html = '';
			}
			
			return $html;
		}
		
		private function getAllSpeakersAtEvent($id) {
			$query = $this->parseQuery("Speaker");
			$query->select(['objectId']);
			$query->equalTo("eventId", $id);
			return $query->find();
		}
		/**
		  * @param Array of key => value
		  *
		  * @return Reversed JSON array for JavaScript Key : Value
		  */
		private function makeJSONArray($arr) {
			return json_encode(array_reverse($arr));
		}
		/**
		 * @return All Array of Users with the seed attribute to remove from queries
		 */
		private function discardAdminInteractions() {
			$users = ParseUser::query();
			$users->equalTo("seed", true);
			$users->select(['objectId']);
			$results_array = array();
			$results = $users->find();
			foreach($results as $users) {
				array_push($results_array, $users->getObjectId());
			}
			return $results_array;
		}
		/**
		  * @param Start time for the event, End time for the event, and the time you want to check
		  *
		  * @return boolean that returns true if the time is between the event times and false otherwise
		  */
		private function isBetweenTimes($start, $end, $check) {
			$start = strtotime($start);
			$end = strtotime($end);
			$check = strtotime($check);
			$isBetweenTimes = true;
			if($start != $end && $check != $start && $check != $end) {
				if(date('d', $start) == date("d", $end)) {
					$isBetweenTimes = (date('H', $start) < date('H', $check) && date("H", $check) < date("H", $end));
				} else if(date('m', $start) == date("m", $end)) {
					$isBetweenTimes = (date('m', $start) < date('m', $cmeck) && date("m", $cmeck) < date("m", $end));
				}
			} else {
				$isBetweenTimes = false;
			}
			return $isBetweenTimes;
		}
		
		/**
		 * @param $eventId   The eventId of the selected graph
		 * @param $beacon    The name of the beacon selected
		 * @param $timein    The time that is selected
		 * @return String    HTML string of table for Ajax call
		 */
		public function buildBeaconPersonTable($eventId, $beacon, $timein) {
			$specificPeople = $this->parseQuery("BeaconInteraction");
			$specificPeople->equalTo("eventId", $eventId);
			$specificPeople->equalTo('beaconId', $this->getBeaconId($beacon));
			if ($_SESSION['userRole'] == 'o') {
			$specificPeople->notContainedIn("userId", $this->discardAdminInteractions());
			}
			$specificPeople->ascending('userId');
			$specificPeople->limit(1000);
			$specificPeople->select(['objectId', 'timeIn', 'userId', 'timeSpentMillis']);
			$people = $specificPeople->find();
			if(count($people) == 0) return;
			$all_people = $this->allUserInteractionsBeacon($people, $timein);
			$html = "";

			foreach ($all_people as $user) {
				$extra = $this->extraInteractionInfo($user['data_bind'], $eventId);
				$html .= "<tr><td class='details-control'></td><td>".$this->getUsername($user['userId'])."</td>"
				."<td>".$this->MMSS($user["average"]). "</td>" . $extra . '</tr>';
			}
			return $html;
		}
		
		/**
		 * @param the object ID of the event to find in DB
		 *
		 * @return Array with JSON info for JavaScript file
		 */
		// Builder functions
		public function buildEventInteractionNew($id) {
			if($id == 'Default') return;
			
			if ($_SESSION['userRole'] == 'e') {
				$allInteractions = $this->buildBeaconsInteractionsForExhibitorEvent($id, $_SESSION['userID']);
			} else {
			    $allInteractions = $this->buildBeaconsInteractionsForEvent($id);	
			}
			
			//echo var_dump($allInteractions);
			//$locn_date = $this->getEventLocationDate($id);
			$event_info = $this->getEventInfo($id);
			$city = $event_info->get("city");
			$state = $event_info->get("state");
			$date = $event_info->get("startDate")->format('Y-m-d');
			$locn_date = "\"".$city.", ".$state.", ".$date."\"";
			//echo $locn_date;
			return array(
					'title'   	   => $event_info->get("name"),
			        'event_name'   => $event_info->get("name"),
				    'locn_date'    => $locn_date,
			//'date'         => $event_info->get('startDate')->format('Y-m-d'),
					'extra'  	   => ' <i class="fa fa-arrow-right"></i> All Beacon Interactions',
					'beacon_graph' => $this->makeJSONArray($allInteractions),
					'event_code'   => $event_info->get("eventCode"),
					'event_type'   => 'Event-All'
					);
		}
	    
	    public function buildVisitorInteraction($id) {
			if($id == 'Default') return;
			
			if ($_SESSION['userRole'] == 'e') {
				$allInteractions = $this->buildVisitorInteractionsForExhibitorEvent($id, $_SESSION['userID']);
			} else {
			    $allInteractions = $this->buildBeaconsInteractionsForEvent($id);	
			}
			
			//echo var_dump($allInteractions);
			//$locn_date = $this->getEventLocationDate($id);
			$event_info = $this->getEventInfo($id);
			$city = $event_info->get("city");
			$state = $event_info->get("state");
			$date = $event_info->get("startDate")->format('Y-m-d');
			$locn_date = "\"".$city.", ".$state.", ".$date."\"";
			//echo $locn_date;
			return array(
					'title'   	   => $event_info->get("name"),
			        'event_name'   => $event_info->get("name"),
				    'locn_date'    => $locn_date,
			//'date'         => $event_info->get('startDate')->format('Y-m-d'),
					'extra'  	   => ' <i class="fa fa-arrow-right"></i> Visitor Beacon Interactions',
					'beacon_graph' => $this->makeJSONArray($allInteractions),
					'event_code'   => $event_info->get("eventCode"),
					'event_type'   => 'Event-Visitor'
					);
		}
	    
	    /**
		  * @param the object ID of the event to find in DB
		  *
		  * @return Array with JSON info for JavaScript file
		  */
		// Builder functions
		public function buildEventInteraction($id) {
			if($id == 'Default') return;
			$interactions = 0;
			$allInteractions = array();
			$allBeaconsAtEvent = $this->getAllBeaconsNew($id);
		//	echo var_dump($allBeaconsAtEvent);
			if($this->checkDisplayGraphInfo($allBeaconsAtEvent, $id)) {
				foreach($allBeaconsAtEvent as $beacon) {
					$beacon_info = $this->getBeaconInfo($beacon);
					$BeaconInteractionQ = $this->parseQuery("BeaconInteraction");
					$BeaconInteractionQ->equalTo("beaconId", $beacon);
					$BeaconInteractionQ->equalTo("eventId", $id);
					$admins = $this->discardAdminInteractions();
					if ($_SESSION['userRole'] == 'o') {
					$BeaconInteractionQ->notContainedIn("userId", $admins);
					}
					$interactions = $BeaconInteractionQ->count();
					array_push($allInteractions, array('key' => $beacon_info->get("name"), 'value' => $interactions, 'company' =>  ' ('. $this->getCompanyName($beacon_info->get('companyId')). ')'));
				}
				 
				//$locn_date = $this->getEventLocationDate($id);
				$event_info = $this->getEventInfo($id);
				$city = $event_info->get("city");
				$state = $event_info->get("state");
				$date = $event_info->get("startDate")->format('Y-m-d');
				$locn_date = "\"".$city.", ".$state.", ".$date."\"";
				//echo $locn_date;
				return array(
					//'title'   	   => $event_info->get("name"),
				    //'locn_date'    => $locn_date,
				    //'date'         => $event_info->get('startDate')->format('Y-m-d'),
					//'extra'  	   => ' <i class="fa fa-arrow-right"></i> All Beacon Interactions',
					'beacon_graph' => $this->makeJSONArray($allInteractions),
					'event_code'   => $event_info->get("eventCode"),
					'event_type'   => 'Event-All'
				);
			}
		}
		
		private function getEventCode($id) {
			$query = $this->parseQuery("Event");
			$query->equalTo("objectId", $id);
			return $query->first()->get("eventCode");
		}
		private function getStandardTime($time) {
			if($time == 12)
				return $standardTime .= ($time) . 'p' . '-' . ((1)) . 'p';
			else if($time > 12)
				return $standardTime .= ($time - 12) . 'p' . '-' . (($time+1) - 12) . 'p';
			else if($time == 11)
				return $standardTime .= ($time) . 'a' . '-' . ($time+1). 'p';
			else
				return $standardTime .= ($time) . 'a' . '-' . ($time+1). 'a';
		}
		/**
		 * Sets up Hourly Interactions for beacon
		 * @param  String $beacon  The name of the beacon selected
		 * @param  String $eventId The objectId of the event
		 * @return Array           1. JSON object for D3. 2. Title for page. 3. The event code for the event 4. The event type for download
		 */
		public function buildBeaconHourlyInteractions($beacon, $eventId) {
			//echo "<br> Krsna2 before call getAllInteractionsOfBeacon";
			$beaconId = $this->getBeaconId($beacon);
			$allInteractions = $this->getAllInteractionsOfBeacon($beaconId, $eventId);
			$event_info = $this->getEventInfo($eventId);
			//$real_t = $this->getTimezone($eventId);
			$real_t = $event_info->get("timeZone");
			$interactions = 0;
			$all_int = array();
			$maxTime = 20;
			while($maxTime) {
				for($i = 0; $i < count($allInteractions); $i++) {
					//echo var_dump($allInteractions[0]);
					$dateParseIn = $allInteractions[$i]->get("timeIn")->format("Y-m-d H:i:s");
					//echo var_dump($dateParseIn);
					$dateParseIn = $this->_settimezone($dateParseIn, $real_t);
					$next_time = strtotime($dateParseIn);
					$dateParseIn = (date('H', $next_time));
					if($dateParseIn == $maxTime) {
						$interactions++;
					}
				}//maxtime <20 to >8
				if($maxTime <= 20 && $maxTime >= 7) {
					array_push($all_int, array('key' => $this->getStandardTime($maxTime), 'value' => $interactions, 'decide' => 'org'));
				}
				$maxTime--;
				$interactions = 0;
			}
			
			return array(
				"beacon_graph" => $this->makeJSONArray($all_int),
				'title' 	   => $beacon,
				'event_name'   => $event_info->get("name"), 
				'extra'        =>  ' <i class="fa fa-arrow-right"></i> Hourly Interaction Data',
				//'event_code'   => $this->getEventCode($eventId),
			    'event_code'   => $event_info->get("eventCode"),
				'event_type'   => 'Event-Hourly'
			);
		}
		
		
		public function buildVisitorBeaconHourlyInteractions($beacon, $eventId) {
			//echo "<br> Krsna2 before call getAllInteractionsOfBeacon";
			$user_array = array();
			$beaconId = $this->getBeaconId($beacon);
			$allInteractions = $this->getAllInteractionsOfBeacon($beaconId, $eventId);
			foreach($allInteractions as $allUsers){
			array_push($user_array,$allUsers->get['userId']);
			$user_array = array_unique($user_array);
			$event_info = $this->getEventInfo($eventId);
			//$real_t = $this->getTimezone($eventId);
			$real_t = $event_info->get("timeZone");
			$interactions = 0;
			$all_int = array();
			$maxTime = 20;
			while($maxTime) {
				for($i = 0; $i < count($allInteractions); $i++) {
					//echo var_dump($allInteractions[0]);
					$dateParseIn = $allInteractions[$i]->get("timeIn")->format("Y-m-d H:i:s");
					//echo var_dump($dateParseIn);
					$dateParseIn = $this->_settimezone($dateParseIn, $real_t);
					$next_time = strtotime($dateParseIn);
					$dateParseIn = (date('H', $next_time));
					if($dateParseIn == $maxTime&& $allInteractions->get['userId']== $user_array->get['userId'] ) {
						$interactions++;
					}
				}//maxtime <20 to >8
				if($maxTime <= 20 && $maxTime >= 7&& $allInteractions->get['userId']== $user_array->get['userId']) {
					array_push($all_int, array('key' => $this->getStandardTime($maxTime), 'value' => $user_array, 'decide' => 'org'));
				}
				$maxTime--;
				$interactions = 0;
			}
		}
			return array(
				"beacon_graph" => $this->makeJSONArray($all_int),
				'title' 	   => $beacon,
				'event_name'   => $event_info->get("name"), 
				'extra'        =>  ' <i class="fa fa-arrow-right"></i> Hourly Interaction Data',
				//'event_code'   => $this->getEventCode($eventId),
			    'event_code'   => $event_info->get("eventCode"),
				'event_type'   => 'Visitor-Hourly'
			);
		}
		
		/**
		 * @param $time        The time returned from the database
		 * @param $newzone     The New timezone to set the date to.
		 * @param $defaultzone The default zone to set the date up to. Default to UTC
		 */
		private function _settimezone($time,$newzone,$defaultzone = 'UTC') {
			//echo "<br> Krsna inside settimezone";
			$date = new DateTime($time, new DateTimeZone($defaultzone));
			$date->setTimezone(new DateTimeZone($newzone));
			return $date->format('Y-m-d H:i:s');
		}
		public function buildBeaconAllInteractions($eventId, $interactionTime) {
			$eventName = $this->getEventName($eventId);
			$allBeacons = $this->getAllBeacons();
			$interactions = 0;
			$timezone = $this->getTimezone($eventId);
			$allBeaconsAtEvent = $this->allBeaconsAtEvent($eventId);
			$singleBeaconArray = $this->arrayOfBeaconsAtEventSingle($allBeaconsAtEvent);
			$returnedArray = array();
			$regexCheck = "/[-]/";
			$timeRef = preg_match($regexCheck, substr($interactionTime, 0, 2)) ? substr($interactionTime, 0, 1) : substr($interactionTime, 0, 2);
			$startValue = 0;
			while($startValue < count($singleBeaconArray)) {
				for($i = 0; $i < count($allBeaconsAtEvent); $i++) {
					$dateParseIn = $allBeaconsAtEvent[$i]->get("timeIn")->format("Y-m-d H:i:s");
					$formated_date = $this->_settimezone($dateParseIn, $timezone);
					$formated_date = date('H', $formated_date);
					if(($formated_date == $timeRef) && ($singleBeaconArray[$startValue] == $allBeaconsAtEvent[$i]->get("beaconId"))) {
						$interactions++;
					}
				}
				if($interactions > 0) {
					array_push($returnedArray, array('key' => $this->getBeaconName($singleBeaconArray[$startValue]), 'value' => $interactions));
					$interactions = 0;
				}
				$startValue++;
			}
			return array(
				'beacon_graph' => $this->makeJSONArray($returnedArray)
			);
		}
		/**
		 * @param $interactions All Server Data from Parse BeaconInteractions specified by parent method
		 * @param $time         Specific time (hour) that is being searched for NULL by default
		 * @return Array        Of users, average dwell time, and Array of BeaconInteractionId's
		 */
		private function allUserInteractionsBeacon($interactions, $time = null) {
			$users_array = array();
			$returnedArray = array();
			$obj_array = array();
			$times = 0;
			$average = 0;
			$index = -1;
			$next = 1;
			$timezone = $this->getTimezone();
			foreach ($interactions as $user) {
				$time_q = $user->get("timeIn")->format('Y-m-d H:i:s');
				$time_t = $this->_settimezone($time_q, $timezone);
				$users_time = intval(date('H', strtotime($time_t)));
				$end_array = (end($interactions)->getObjectId() == $user->getObjectId());
				if ($users_time == intval($time) || $time == null) {
					if (!in_array($user->get("userId"), $users_array)) {
						array_push($users_array, $user->get("userId"));
						$index++;
					}
					array_push($obj_array, $user->getObjectId());
					$average += $user->get("timeSpentMillis");
					$times++;
					if ($end_array || $interactions[$next]->get("userId") != $user->get("userId")) {
						array_push($returnedArray, array('userId' => $users_array[$index], 'average' => round($average/$times, 2, PHP_ROUND_HALF_DOWN), 'data_bind' => $obj_array));
						$times = 0;
						$average = 0;
						$obj_array = array();
					}
				} else if ($end_array) {
					if ($time == $users_time || $time == null) {
						if (!in_array($user->get("userId"), $users_array)) {
							array_push($users_array, $user->get("userId"));
							$index++;
						}
						array_push($obj_array, $user->getObjectId());
						$average += $user->get("timeSpentMillis");
						$times++;
					}
					if ($times > 0) {
					    array_push($returnedArray, array('userId' => $users_array[$index], 'average' => round($average/$times, 2, PHP_ROUND_HALF_DOWN), 'data_bind' => $obj_array));
					}
					$times = 0;
					$average = 0;
					$obj_array = array();
				}
				if (count($interactions) != $next)
					$next++;
			}
			return $returnedArray;
		}
		private function MMSS($millis) {
			$seconds = floor(($millis / 1000));
			$minutes = '00';
			if ($seconds >= 60) {
				$minutes = floor(($seconds / 60));
				$seconds = intval($seconds - (intval($minutes) * 60));
			}
			$seconds = (intval($seconds) >= 10) ? $seconds : ('0'.(string)$seconds);
			return (string)($minutes .':'. $seconds);
		}
		private function extraInteractionInfo($obj_array, $eventId) {
			//echo "extra Interation <br>";
			$query = $this->parseQuery("BeaconInteraction");
			$query->equalTo("eventId", $eventId);
			$query->containedIn("objectId", $obj_array);
			$query->limit(1000);
			$query->descending('timeIn');
			$results = $query->find();
			
			$timeIn = array();
			foreach ($results as $key => $row) {
				$timeIn[$key] = $row->get("timeIn");
			}
			array_multisort($timeIn, SORT_DESC, $results);
			
			$html = '';
			$timezone = $this->getTimezone();
			foreach ($results as $interaction) {
				$html .= '<td class="extra-info"><i class="fa fa-arrow-circle-right"> </i> '.$this->_settimezone($interaction->get("timeIn")->format("Y-m-d H:i:s"), $timezone).'</td>'.
					 	 '<td class="extra-info">'."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->MMSS($interaction->get("timeSpentMillis")) .'</td>';
			}
			return $html;
		}
		
		private function getSessionId($session, $eventId) {
			$query = $this->parseQuery("Session");
			$query->equalTo('eventId', $eventId);
			$query->equalTo("title", $session);
			$query->select(['objectId']);
			return $query->first()->getObjectId();
		}
	public function buildSessionAttendance($eventId) {
			$returnedArray = $this->buildSessionAttendanceActual($eventId);
			
			$event_info = $this->getEventInfo($eventId);
			$city = $event_info->get("city");
			$state = $event_info->get("state");
			$date = $event_info->get("startDate")->format('Y-m-d');
			$locn_date = "\"".$city.", ".$state.", ".$date."\"";
			//echo var_dump($locn_date);
			return array(
				'beacon_graph' 	=> $this->makeJSONArray($returnedArray),
			    'title'         => $event_info->get("name"),
			    'event_name'    => $event_info->get("name"),
			    'locn_date'     => $locn_date,  
				//'title'			=> 'Session Attendence',
				'event_type'    => 'Event-Sessions',
			    'event_code'    => $event_info->get("eventCode"),
				'extra'			=> ' <i class="fa fa-arrow-right"></i> Session Attendance'
			);
			
			
		}
		public function buildSpeakerAttendance($eventId) {
			$returnedArray = $this->buildSpeakerAttendanceActual($eventId);
			
			$event_info = $this->getEventInfo($eventId);
			$city = $event_info->get("city");
			$state = $event_info->get("state");
			$date = $event_info->get("startDate")->format('Y-m-d');
			$locn_date = "\"".$city.", ".$state.", ".$date."\"";
			//echo var_dump($locn_date);
			return array(
				'beacon_graph' 	=> $this->makeJSONArray($returnedArray),
			    'title'         => $event_info->get("name"),
			    'event_name'    => $event_info->get("name"),
			    'locn_date'     => $locn_date,  
				//'title'			=> 'Session Attendence',
				'event_type'    => 'Event-SpeakerAttendance',
			    'event_code'    => $event_info->get("eventCode"),
				'extra'			=> ' <i class="fa fa-arrow-right"></i> Speakers By Attendance'
			);
			
		}
		
		private function buildSessionUserTable($session, $eventId) {
			$sessionId = $this->getSessionId($session, $eventId);
			$be = $this->parseQuery("BeaconInteraction");
			$be->equalTo("sessionId", $sessionId);
			$be->notContainedIn('userId', $this->discardAdminInteractions());
			$be->select(['userId', 'timeIn', 'timeSpentMillis']);
			$html = '';
			$timezone = $this->getTimezone();
			$results = $be->find();
			
			$all_session_int = $this->allUserInteractionsBeacon($results, null);
			
			foreach($all_session_int as $users) {
				$extra = $this->extraInteractionInfo($users['data_bind'], $eventId);
				$html .= "<tr><td class='details-control'></td><td>".$this->getUsername($users['userId'])."</td>"
				."<td>".$this->MMSS($users["average"]). "</td>" . $extra . '</tr>';
				
			}
			return $html;
		}
		
		private function buildVisitorUserTable($beacon, $eventId) {
				
			$beaconId = $this->getBeaconIdForEvent($beacon, $eventId);
			//echo var_dump($beaconId);
			$be = $this->parseQuery("BeaconInteraction");
			$be->equalTo("beaconId", $beaconId);
		//$be->notContainedIn('userId', $this->discardAdminInteractions());
			$be->select(['userId', 'timeIn', 'timeSpentMillis']);
			$html = '';
			$timezone = $this->getTimezone();
			$results = $be->find();
			//echo var_dump($results);
			
			$all_visitor_int = $this->allUserInteractionsBeacon($results, null);
			//echo var_dump($all_visitor_int);
			foreach($all_visitor_int as $users) {
				$extra = $this->extraInteractionInfo($users['data_bind'], $eventId);
				$html .= "<tr><td class='details-control'></td><td>".$this->getUsername($users['userId'])."</td>"
				."<td>".$this->MMSS($users["average"]). "</td>" . $extra . '</tr>';
				
			}
			return $html;
		}
		
		private function buildSpeakersByAttendanceUserTable($speaker,$eventId) {
	
		    $speakerId = $this->getSpeakerId($eventId, preg_split('/\s/', $speaker)[1]);
			$query = $this->parseQuery("Session");
			$query->equalTo("eventId", $eventId);
			$query->equalTo("speakerIds", $speakerId);
			$query->select(['objectId', 'beaconId', 'speakerIds']);
			$result_session = $query->find();
			//echo var_dump($result_session);
			$query = $this->parseQuery("BeaconInteraction");
		foreach($result_session as $session){
			//$nonAdmin = $this->getNonAdminUsers();
			//(to be uncommented)$admins = $this->discardAdminInteractions();
	        
			$query->select(['userId','timeIn', 'timeSpentMillis']);
			$query->equalTo('eventId', $eventId);
			$query->equalTo("sessionId", $session->getObjectId());
			$results = $query->find();
			$timezone = $this->getTimezone();
			
			$all_speaker_int = $this->allUserInteractionsBeacon($results, null);
			
			//echo var_dump($results);
			//$beacon_interactions->containedIn('userId', $nonAdmin);	
			//(to uncomment)$beacon_interactions->notContainedIn('userId', $admins);
			//$interactions = 0;
			//$returnedArray = array();
			
			
			//for($i = 0; $i < count($session); $i++) {
			//	$interactions = $beacon_interactions->equalTo('beaconId', $session[$i]->get('beaconId'))->count();
				
				
		   foreach($all_speaker_int as $users) {
				//echo var_dump($users->getObjectId());
				$extra = $this->extraInteractionInfo($users['data_bind'], $eventId);
				$html .= "<tr><td class='details-control'></td><td>".$this->getUsername($users['userId'])."</td>"
				."<td>".$this->MMSS($users["average"]). "</td>" . $extra . '</tr>';
				
				
			}}
			return $html;
		}
		
		public function buildSpecificSpeakerRank($eventId, $speaker, $raw_output) {
			if ($raw_output) {
				$speakerId = $speaker;
			} else {
				$speakerId = $this->getSpeakerId($eventId, preg_split('/\s/', $speaker)[1]);
			}
			$query = $this->parseQuery("SurveyResponse");
			$query->equalTo("speakerId", $speakerId);
			$query->notContainedIn('userId', $this->discardAdminInteractions());
			$query->select(['userId', 'rating', 'questionId']);
			$query->limit(1000);
			$responses = $query->find();
			$all_users = $this->getAverageRating($responses);
			if ($raw_output) {
				return $all_users;
			}
			$html = '';
			foreach ($all_users as $user) {
				$extra = $this->extraSpeakerInfo($user['questions'], $user['userId'], $speakerId);
            $html .= "<tr><td class='details-control'></td><td>".$this->getUsername($user['userId'])."</td>"
						."<td>"."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$user["average"]. "</td>". $extra . '</tr>';
				
			}
			return $html;
		}
		
		public function buildSpeakerRanking($eventId) {
			$query = $this->parseQuery("Speaker");
			$query->equalTo("eventId", $eventId);
			$query->select(['objectId']);
			$speakers = $query->find();
			$result_array = array();
			$first_name = array();
			//$query = $this->parseQuery("SurveyResponse");
			//$test_sum = 0;
			foreach($speakers as $speaker) {
				
				$all_users = $this->buildSpecificSpeakerRank($eventId, $speaker->getObjectId(), 1);
				$speaker_rating_sum = 0;
				foreach($all_users as $user_rating) {
					$speaker_rating_sum += $user_rating["average"];
				}
				if (count($all_users) > 0) { 
				    $spearker_rating_avg = round($speaker_rating_sum/count($all_users), 2, PHP_ROUND_HALF_DOWN);
				} 
				
				
				//$query->equalTo("speakerId", $speaker->getObjectId());
				//$query->notContainedIn("userId", $this->discardAdminInteractions());
				//$query->select(['userId', 'rating']);
				//$results = $query->find();
				//$average = 0;
				//foreach($results as $rating) {
					//if ($speaker->getObjectId() == "mQiS09axTP") {
						//echo var_dump($rating->get("userId"));
						//echo var_dump($rating->get("rating"));
						//$test_sum += $rating->get("rating");
					//}
					//$average += $rating->get("rating");
				//}
				if($speaker_rating_sum) {
					$person = $this->getSpeakerInfo($speaker->getObjectId());
					//if ($speaker->getObjectId() == "mQiS09axTP") {
						//echo var_dump($average);
						//echo var_dump($results);
						//echo round($average/count($results), 2, PHP_ROUND_HALF_DOWN);
						
					//}
					array_push($first_name, $person->get("firstName"). ' '. $person->get("lastName"));
					array_push($result_array, array('key' => $person->get("firstName") . ' ' . $person->get("lastName"), 'value' => $spearker_rating_avg, 'img' => $person->get("avatar") ? $person->get("avatar")->getURL() : '	/assets/img/avatar2.png'));
				}
			}
			$first_name_lower = array_map('strtolower', $first_name);
			//echo var_dump($first_name_lower);
			array_multisort($first_name_lower, SORT_DESC, SORT_STRING, $result_array);
			//echo var_dump($result_array);
			$event_info = $this->getEventInfo($eventId);
			$city = $event_info->get("city");
			$state = $event_info->get("state");
			$date = $event_info->get("startDate")->format('Y-m-d');
			$locn_date = "\"".$city.", ".$state.", ".$date."\"";
			//echo var_dump($locn_date);
			return array(
				'beacon_graph' => $this->makeJSONArray($result_array),
				//'title' 			 => "Speaker Ratings",
				'title'        => $event_info->get("name"),
			    'event_name'   => $event_info->get("name"),
			    //'locn_date'    => $locn_date,      
				//'extra'			=> ' <i class="fa fa-arrow-right"></i> Speaker Ratings',
				'event_code'   => $event_info->get("eventCode"),
				'event_type'   => 'Event-Speakers'
			);
		}
		/**
		 * @param $responses All Parse Instances of SurveyResponse for current Event
		 * @return Array of users with their overall average rating
		 */
		private function getAverageRating($responses) {
			$questions_array = array();
			$users_array = array();
			$returnedArray = array();
			$times = 0;
			$average = 0;
			$index = -1;
			$next = 1;
			foreach ($responses as $user) {
				if (in_array($user->get("userId"), $users_array))
					$average += $user->get("rating");
				else {
					array_push($users_array, $user->get("userId"));
					$average += $user->get("rating");
					$index++;
				}
				array_push($questions_array, $user->get("questionId"));
				$times++;
				if (end($responses)->getObjectId() == $user->getObjectId() || $responses[$next]->get("userId") != $user->get("userId")) {
					array_push($returnedArray, array('userId' => $users_array[$index], 'average' => round($average/$times, 2, PHP_ROUND_HALF_DOWN), 'questions' => $questions_array));
					$times = 0;
					$average = 0;
					$questions_array = array();
				}
				if(count($responses) != $next+1)
					$next++;
			}
			return $returnedArray;
		}
		private function extraSpeakerInfo($questions, $userId, $speaker = NULL) {
			$query = $this->parseQuery("SurveyResponse");
			$query->equalTo("userId", $userId);
			if ($speaker)
				$query->equalTo("speakerId", $speaker);
			$query->containedIn("questionId", $questions);
			$query->select(['rating', 'questionId']);
			$results = $query->find();
			$html = '';
			foreach ($results as $question) {
				$html .= '<td class="extra-info">'. $this->getQuestionName($question->get("questionId")).'</td>'.
						 '<td class="extra-info">'. $question->get("rating") .'</td>';
			}
			return $html;
		}
		
		private function getSpeakerId($eventId, $last, $first = '') {
			$query = $this->parseQuery("Speaker");
			$query->equalTo("eventId", $eventId);
			$query->equalTo("lastName", $last);
			if($first != '')
				$query->equalTo("firstName", $first);
			$query->select(['objectId']);
			return $query->first()->getObjectId();
		}
		private function getExhibitorInfo($id) {
			$query = $this->parseQuery("Exhibitor");
			$query->equalTo("objectId", $id);
			return $query->first();
		}
		private function getCompanyLogo($id) {
			$query = $this->parseQuery("Company");
			$query->equalTo('objectId', $id);
			$results = $query->first()->get('logo');
			return ($results != NULL) ? $results->getURL() : '/assets/img/logo_placeholder.png';
		}
		private function getAllExhibitors($eventId) {
			$exhibitor_q = $this->parseQuery("Exhibitor");
			$exhibitor_q->equalTo("eventId", $eventId);
			$exhibitor_q->select(['CompanyId', 'logo']);
			$exhibitors = $exhibitor_q->find();
			$company_array = array();
			foreach($exhibitors as $exhibitor) {
				if ($exhibitor->get("CompanyId")) {
					array_push($company_array, $exhibitor->get("CompanyId"));
				}
			}
			return $company_array;
		}
		private function buildExhibitorInteractions($eventId) {
			$company_array = $this->getAllExhibitors($eventId);
			$beacons_q = $this->parseQuery("Beacon");
			$beacons_q->containedIn("companyId", $company_array);
			$beacons_q->select(['name', 'objectId', 'companyId', 'logo']);
			$beacons = $beacons_q->find();
			$interactions = $this->parseQuery("BeaconInteraction");
			$interactions->notContainedIn('userId', $this->discardAdminInteractions());
			$interactions->equalTo("eventId", $eventId);
			if($interactions == null) return;
			$returnedArray = array();
			foreach($beacons as $beacon) {
				$interactions->equalTo("beaconId", $beacon->getObjectId());
				if($interactions->count())
					array_push($returnedArray, array('key' => $this->getCompanyName($beacon->get('companyId')), 'value' => $interactions->count(), 'img' => $this->getCompanyLogo($beacon->get("companyId"))));
			}
			$event_info = $this->getEventInfo($eventId);
			$city = $event_info->get("city");
			$state = $event_info->get("state");
			$date = $event_info->get("startDate")->format('Y-m-d');
			$locn_date = "\"".$city.", ".$state.", ".$date."\"";
			//echo var_dump($locn_date);
			return array(
				'beacon_graph' => $this->makeJSONArray($returnedArray),
			    'title' => $event_info->get("name"),
			    'event_name' => $event_info->get("name"),   
			    'event_type'   => 'Event-Exhibitor-Interactions', 
			    'event_code'   => $event_info->get("eventCode"),    
				//'title' => 'Exhibitor Beacon Interactions',
				'locn_date' => $locn_date,
				'extra' => ' <i class="fa fa-arrow-right"></i> Exhibitor Interactions'
			);
		}
		
		private function getExhibitorId($exhibitor, $eventId) {
			$query = $this->parseQuery("Exhibitor");
			$query->equalTo("eventId", $eventId);
			$query->equalTo("name", $exhibitor);
			$query->select(['objectId']);
			return $query->first()->getObjectId();
		}
		/**
		 * @note The table displays the userId because some of the users don't exist
		 */
		public function buildExhibitorUserTable($exhibitor, $eventId) {
			$exhibitorId = $this->getExhibitorId($exhibitor, $eventId);
			$query = $this->parseQuery("ExhibitorRating");
			$query->equalTo("ExhibitorId", $exhibitorId);
			$query->notContainedIn("UserId", $this->discardAdminInteractions());
			$query->select(['UserId', 'Rating']);
			$results = $query->find();
			$html = '';
			foreach ($results as $ex) {
				$html .= "<tr><td>".$this->getUsername($ex->get("UserId"))."</td>"
						."<td>".$ex->get("Rating"). "</td></tr>";
			}
			return $html;
		}
		/* Does not work in BEEP-DEV */
		public function buildExhibitorRating($eventId) {
			$query = $this->parseQuery("Exhibitor");
			$query->equalTo("eventId", $eventId);
			$query->select(['objectId']);
			$all_exhibitors = $query->find();
			if(empty($all_exhibitors)) return;
			$exhibitors = array();
			foreach($all_exhibitors as $exhibitor) {
				if(!array_key_exists($exhibitor, $all_exhibitors))
					array_push($exhibitors, $exhibitor->getObjectId());
			}
			$ratings_q = $this->parseQuery("ExhibitorRating");
			$ratings_q->notContainedIn('UserId', $this->discardAdminInteractions());
			$returnedArray = array();
			foreach ($exhibitors as $exhibitor) {
				$ratings_q->equalTo("ExhibitorId", $exhibitor);
				$ratings_q->select(['Rating']);
				$results = $ratings_q->find();
				$times = 0;
				$average = 0;
				foreach ($results as $rating) {
					$average += ($rating->get('Rating'));
					$times++;
				}
				$logo = $this->getExhibitorInfo($exhibitor)->get("logo") ? $this->getExhibitorInfo($exhibitor)->get('logo')->getURL() : '/assets/img/logo_placeholder.png';
				if($average)
						array_push($returnedArray, array('key' => $this->getExhibitorInfo($exhibitor)->get('name'), 'value' => round($average/$times, 2, PHP_ROUND_HALF_DOWN), 'img' => $logo));
			}
			return array(
				'beacon_graph' => $this->makeJSONArray($returnedArray),
				'title' 			 => 'Exhibitor Ratings',
				'event_code' 	 => $this->getEventCode($eventId),
				'event_type'   => 'Event-Exhibitor-Rating'
			);
		}
	public function buildExhibitorInteractionsTable_additional($eventId, $exhibitor) {
		//	echo "Krsna inside buildExhibitorInteractionsTable";
		//	echo var_dump($eventId);
		//	echo var_dump($exhibitor);
			$query = $this->parseQuery("Exhibitor");
			$query->equalTo("eventId", $eventId);
			$query->equalTo("objectId", $this->getExhibitorId($exhibitor, $eventId));
			$ex = $query->first();
			$beacons_q = $this->parseQuery("Beacon");
			$beacons_q->equalTo('eventId', $eventId);
			$beacons_q->equalTo("companyId", $ex->get("CompanyId"));
			$beacons_q->select(['objectId']);
			$beacons = $beacons_q->find();
			$beacon_array = array();
			foreach ($beacons as $beacon) {
				array_push($beacon_array, $beacon->getObjectId());
			}
			$interactions = $this->parseQuery("BeaconInteraction");
			$interactions->equalTo("eventId", $eventId);
			$interactions->notContainedIn('userId', $this->discardAdminInteractions());
			$interactions->containedIn('beaconId', $beacon_array);
			$user_results = $interactions->find();
		//	echo var_dump($user_results);
			if($user_results == null) return $default_html; //return $false;
			$html = '';
			$allUsers = $this->allUserInteractionsBeacon($user_results);
			//echo var_dump($allUsers);
			foreach($allUsers as $user) {
				$extra = $this->extraInteractionInfo($user['data_bind']);
					$html .= "<tr><td class='details-control'></td><td>".$this->getUsername($user['userId'])."</td>"
						."<td>".$this->MMSS($user['average']). "</td>". $extra ."</tr>";
			}
			return $html;
		}
		/**
		 * @param $eventId     The EventId of the current Event
		 * @param $exhibitor   The name of the exhibitor clicked on
		 */
		public function buildExhibitorInteractionsTable($eventId, $exhibitor) {
			echo "Krsna inside buildExhibitorInteractionsTable";
		//	echo var_dump($eventId);
		//	echo var_dump($exhibitor);
			$query = $this->parseQuery("Exhibitor");
			$query->equalTo("eventId", $eventId);
			$query->equalTo("objectId", $this->getExhibitorId($exhibitor, $eventId));
			$ex = $query->first();
			
			$beacons_q = $this->parseQuery("Beacon");
			$beacons_q->equalTo('eventId', $eventId);
			$beacons_q->equalTo("companyId", $ex->get("CompanyId"));
			$beacons_q->select(['objectId']);
			$beacons = $beacons_q->find();
			$beacon_array = array();
			foreach ($beacons as $beacon) {
				array_push($beacon_array, $beacon->getObjectId());
			}
			
			$interactions = $this->parseQuery("BeaconInteraction");
			$interactions->equalTo("eventId", $eventId);
			$interactions->notContainedIn('userId', $this->discardAdminInteractions());
			$interactions->containedIn('beaconId', $beacon_array);
			$user_results = $interactions->find();
			//echo var_dump($user_results);
			if($user_results == null) return false; //return $false;
			$html = '';
			$allUsers = $this->allUserInteractionsBeacon($user_results);
			//echo var_dump($allUsers);
			foreach($allUsers as $user) {
				$extra = $this->extraInteractionInfo($user['data_bind']);
					$html .= "<tr><td class='details-control'></td><td>".$this->getUsername($user['userId'])."</td>"
						."<td>".$this->MMSS($user['average']). "</td>". $extra ."</tr>";
			}
			return $html;
		}
		private function checkSpeakerResults($all_speakers) {
			$hasResults = true;
			$query = $this->parseQuery("SurveyResponse");
			foreach($all_speakers as $speaker) {
				$query->equalTo("speakerId", $speaker->getObjectId());
				$results = $query->count();
				$hasResults = (!$results) ? false : true;
			}
			return $hasResults;
		}
		/* ------------ Exhibitor Functions ------------ */
		private function getAllBeaconsExhibitor($companyId) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo('companyId', $companyId);
			return $query->find();
		}
		private function getInteractionsOfBeaconEx($beacon, $company) {
			$query = $this->parseQuery("BeaconInteraction");
			$query->select(['beaconId','companyId', 'timeIn']);
			$query->equalTo("beaconId", $beacon);
			$query->equalTo("companyId", $company);
			$query->limit(1000);
			return $query->find();
		}
		// Temp public function
		public function getCompanyName($companyId) {
			if($companyId) {
				$query = $this->parseQuery("Company");
				$query->equalTo('objectId', $companyId);
				$query->select(['objectId', 'name']);
				$results = $query->first();
				if ($results) {
					if ($results->get('name')) {
						return $results->get('name');
					} else {
						return 'No Company';
					}
				} else {
					return 'No Company';
				}
			} return false;
		}

		public function getCompanyNameForExhibitor($companyId) {
			if($companyId) {
				$query = $this->parseQuery("Exhibitor");
				$query->equalTo('objectId', $companyId);
				$query->select(['objectId', 'name']);
				$results = $query->first();
				if ($results) {
					if ($results->get('name')) {
						return $results->get('name');
					} else {
						return 'No Company';
					}
				} else {
					return 'No Company';
				}
			} return false;
		}
		
		public function buildAttendeeInteractionsEx($companyId) {
			$allBeacons = $this->getAllBeaconsExhibitor($companyId);
			$interactions_query = $this->parseQuery("BeaconInteraction");
			$interactions_query->equalTo('companyId', $companyId);
			$interactions = 0;
			$returnedArray = array();
			for($i = 0; $i < count($allBeacons); $i++) {
				$interactions = count($interactions_query->equalTo('beaconId', $allBeacons[$i]->getObjectId())->find());
				if($interactions > 0) {
					array_push($returnedArray, array('key' => $allBeacons[$i]->get('name'), 'value' => $interactions));
				}
			}
			return array(
				'beacon_graph'	=> $this->makeJSONArray($returnedArray)
			);
		}

		public function buildAttendeeHourlyEx($companyId, $beacon) {
			$beaconId = $this->getBeaconId($beacon);
			$allInteractions = $this->getInteractionsOfBeaconEx($beaconId, $companyId);
			$maxTime = $this->getMaxTime($allInteractions);
			$interactions = 0;
			$all_int = array();
			$timezone = $this->getTimezone();
			/* TODO: Big-o = N^2 */
			while($maxTime) {
				for($i = 0; $i < count($allInteractions); $i++) {
					$dateParseIn = $allInteractions[$i]->get("timeIn")->format("Y-m-d H:i:s");
					$formated_date = $this->_settimezone($dateParseIn, $timezone);
					$formated_date = date("H", $formated_date);
					if($formated_date == $maxTime) {
						$interactions++;
					}
				}
				if($maxTime <= 20 && $maxTime >= 7) {
					array_push($all_int, array('key' => $maxTime. '-' . intval($maxTime+1), 'value' => $interactions, 'decide' =>"ex"));
					$interactions = 0;
				}
				$maxTime--;
			}
			return array(
				"beacon_graph" => $this->makeJSONArray($all_int)
			);
		}

		public function buildAttendeeHourlyTableEx($companyId, $beacon, $timein) {
			$specificPeople = $this->parseQuery("BeaconInteraction");
			$specificPeople->equalTo('beaconId', $this->getBeaconId($beacon));
			$specificPeople->equalTo("companyId", $companyId);
			$people = $specificPeople->find();
			$timezone = $this->getTimezone();
			$html = "";
			for($i = 0; $i < count($people); $i++) {
				$person = $this->getUserInfo($people[$i]->get("userId"));
				$name = $person[0]->get("name");
				$company = $person[0]->get('company');
				$title = $person[0]->get('title');
				$timeIn = $people[$i]->get("timeIn")->format("Y-m-d H:i:s");
				$dateParseIn = $this->_settimezone($timeIn, $timezone);
				if($dateParseIn == $timein) {
					$html .= "<tr>"
								."<td>". $name . "</td>"
								."<td>". $title  . "</td>"
								."<td>". $company . "</td>"
								."<td>" . $timein /*$timeIn->format("Y-m-d H:i:s") */."</td>"
							."</tr>";
				}
			}
			return $html;
		}
		/**
		  * @param $data Array from Parse from BeaconInteraction
		  * @param $id Object Id of event
		  * @return Boolean returns True if there is info in BeaconInteraction Table false otherwise
		  */
		private function checkDisplayGraphInfo($data, $id) {
			$hasInfo = false;
			foreach($data as $beacon) {
				$query = $this->parseQuery("BeaconInteraction");
				$query->equalTo("eventId", $id);
				$query->equalTo("beaconId", $beacon);
				$results = $query->count();
				if($results > 0) {
					$hasInfo = true;
				}
			}
			return $hasInfo;
		}
		/**
		  * @return JSON Array for bar graphs to display Default Data
		  */
		public function buildDefaultGraph() {
			$fake_data = array(
				array('key' => 'Example Data 7', 'value' => 51),
				array('key' => 'Example Data 6', 'value' => 101),
				array('key' => 'Example Data 5', 'value' => 12),
				array('key' => 'Example Data 4', 'value' => 93),
				array('key' => 'Example Data 3', 'value' => 32),
				array('key' => 'Example Data 2', 'value' => 290),
				array('key' => 'Example Data 1', 'value' => 41)
			);
			return array('beacon_graph' => $this->makeJSONArray($fake_data), 'title' => 'Default Graph Example');
		}
		/*private function getNumberOfObjects($class, $id, $specific = '') {
			$query = $this->parseQuery($class);
			if($specific != '')
				$query->equalTo($specific, $id);
			else
				$query->equalTo("objectId", $id);
			return $query->count();
		}*/
		
		 private function getNumberOfObjects($class, $id, $specific = '', $id1, $specific1 = '') {
            $query = $this->parseQuery($class);
            if($specific != '') {
                $query->equalTo($specific, $id);
				if ($specific1 != '') {
					$query->equalTo($specific1, $id1);
				}
			}
            else
                $query->equalTo("objectId", $id);
            return $query->count();
        }
		
		public function buildNotificationAnalytics($type, $specific = null) {
			//echo "Event Code : ";
			//echo $type;
			
			switch ($specific) {
				case 'Notifications':
					return $this->buildNotifications($type);
					break;
				case 'ReceivedNotification':
					return $this->buildReceivedNotification($type);
					break;
				case 'SavedNotification':
					return $this->buildSavedNotification($type);
					break;
				case 'ClickedPdfTracking':
				   // echo "Inside buildNotificationAnalytics<br>";
					return $this->buildPdfNotificationTracking($type);
					break;
				case 'ClickedUrlTracking':
					return $this->buildUrlNotificationTracking($type);
					break;
				default:
					# code...
					break;
			}

		}
		
		
    public function buildContentAnalytics($type, $specific = null) {
		//	echo "Event Code:" $type ;
		//echo "Event Code : ";
			//echo $type;	
    	switch ($specific) {
				
				case 'Content':
					return $this->buildContent($type);
					break;
				case 'ContentPdfTracking':
					return $this->buildPdfContentTracking($type);
					break;
				case 'ContentUrlTracking':
					return $this->buildUrlContentTracking($type);
					break;
				
				
				default:
					# code...
					break;
			}

		}
		
     public function buildContent($type) {
     
			$query = $this->parseQuery("Content");
			 
			//$query->equalTo("EventId", $type);
			$query->equalTo("EventId", "A4wnxn5dd3");

			$results = $query->find();
			$html = '';
			foreach($results as $not) {
				$html .= '<tr>
					<td>'.$not->get("title").'</td>					
				<td><a href="/Organizer/Admin/analytics/'.$not->getObjectId().'/ContentPdfTracking">'. $this->getNumberOfObjects('ContentTracking', $not->getObjectId(), 'ContentId', 'PDF', 'ContentType').' </a></td>
				<td><a href="/Organizer/Admin/analytics/'.$not->getObjectId().'/ContentUrlTracking">'. $this->getNumberOfObjects('ContentTracking', $not->getObjectId(), 'ContentId', 'URL', 'ContentType').'</a></td>
				</tr>';
			}
			return $html;
		
     }
     
     private function allUserPdfContent($pdfcontent) {
			$users_array = array();
			foreach ($pdfcontent as $pdfcon) {
				if (!in_array($pdfcon->get("UserId"), $users_array)) {
					array_push($users_array, $pdfcon->get("UserId"));
				}
			}
			return $users_array;
		}
		
		public function buildPdfContentTracking($cid) {

		 	$query = $this->parseQuery("ContentTracking");
	        $query->equalTo('ContentId', $cid);
			$query->equalTo('ContentType', 'PDF');
            $results = $query->find();
            $html = '';
			$all_users = $this->allUserPdfContent($results);
            $i = 0;
			foreach ($all_users as $users) {
			
				$query->equalTo('UserId', $all_users[$i]);
				$user_details = $query->find();
			    $extra = $this->extraClickInfoContent($user_details, $all_users[$i], $cid);
				$html .= '<tr><td></td><td></td><td></td></tr><tr>
				<td class="details-control"></td><td>'.$this->getUsername($all_users[$i]).'</td>
				<td>'.$query->count().'</td>'.$extra.'</tr>';
				$i+=1;
			}
		return $html;
		}
		
		 private function allUserUrlContent($urlcontent) {
			$users_array = array();
			foreach ($urlcontent as $urlcon) {
				if (!in_array($urlcon->get("UserId"), $users_array)) {
					array_push($users_array, $urlcon->get("UserId"));
				}
			}
			return $users_array;
		}
		
		public function buildUrlContentTracking($cid) {

		 	$query = $this->parseQuery("ContentTracking");
	        $query->equalTo('ContentId', $cid);
			$query->equalTo('ContentType', 'URL');
            $results = $query->find();
            $html = '';
			$all_users = $this->allUserUrlContent($results);
            $i = 0;
			foreach ($all_users as $users) {
			
				$query->equalTo('UserId', $all_users[$i]);
				$user_details = $query->find();
			    $extra = $this->extraClickInfoContent($user_details, $all_users[$i], $cid);
				$html .= '<tr><td></td><td></td><td></td></tr><tr>
				<td class="details-control"></td><td>'.$this->getUsername($all_users[$i]).'</td>
				<td>'.$query->count().'</td>'.$extra.'</tr>';
				$i+=1;
			}
		return $html;
		}
		
		
		public function extraClickInfoContent($userDetails, $UserId, $id) {
			$html = '';
			$timezone = $this->getTimezone();
			foreach ($userDetails as $userClick) {
		
				$dateParseIn = $userClick->getCreatedAt()->format("Y-m-d H:i:s");
				$dateParseOut = $this->_settimezone($dateParseIn, $timezone);
                $html .= '<td class="extra-info"><i class="fa fa-arrow-circle-right"> </i> '.$dateParseOut.'</td>';						 
			}
			
			return $html;
		}
     
		private function buildNotifications($type) {
			$query = $this->parseQuery("Notification");
			
			$allBeacons = $this->allBeaconsEvent($type);
			//echo get("title");
			$beacon_array = array();
			foreach($allBeacons as $beacon) {
				array_push($beacon_array, $beacon->getObjectId());
			}
			$query->containedIn("beaconId", $beacon_array);
			$results = $query->find();
			$html = '';
			foreach($results as $not) {
				$html .= '<tr>
					<td>'.$not->get("title").'</td>
					<td><a href="/Organizer/Admin/analytics/'.$not->getObjectId().'/ReceivedNotification">'. $this->getNumberOfObjects('ReceivedNotification', $not->getObjectId(), 'notificationId','','') .' </a></td>
					<td><a href="/Organizer/Admin/analytics/'.$not->getObjectId().'/SavedNotification">'. $this->getNumberOfObjects('SavedNotification', $not->getObjectId(), 'notificationId','','') .' </a></td>
					<td><a href="/Organizer/Admin/analytics/'.$not->getObjectId().'/ClickedPdfTracking">'. $this->getNumberOfObjects('NotificationTracking', $not->getObjectId(), 'NotificationId', 'PDF', 'NotificationType').' </a></td>
					<td><a href="/Organizer/Admin/analytics/'.$not->getObjectId().'/ClickedUrlTracking">'. $this->getNumberOfObjects('NotificationTracking', $not->getObjectId(), 'NotificationId', 'URL', 'NotificationType').'</a></td>
				</tr>';
			}
			return $html;
		}
		private function buildReceivedNotification($id) {
			//echo "Inside buildReceivedNotification<br>";
			$query = $this->parseQuery("ReceivedNotification");
			$query->equalTo('notificationId', $id);
			$results = $query->find();
			$html = '';
			foreach ($results as $recv) {
				$html .= '<tr>
					<td>'.$this->getUsername($recv->get("userId")).'</td>
				</tr>';
			}
			return $html;
		}
		private function buildSavedNotification($id) {
			$query = $this->parseQuery("SavedNotification");
			$query->equalTo('notificationId', $id);
			$results = $query->find();
			$html = '';
			foreach ($results as $recv) {
				$html .= '<tr>
					<td>'.$this->getUsername($recv->get("userId")).'</td>
				</tr>';
			}
			return $html;
		}
		
		private function allUserPdfNotifications($pdfnotifications) {
			$users_array = array();
			foreach ($pdfnotifications as $pdfnot) {
				if (!in_array($pdfnot->get("UserId"), $users_array)) {
					array_push($users_array, $pdfnot->get("UserId"));
				}
			}
			return $users_array;
		}
		
		public function buildPdfNotificationTracking($id) {
	
		 	$query = $this->parseQuery("NotificationTracking");
	        $query->equalTo('NotificationId', $id);
			$query->equalTo('NotificationType', 'PDF');
            $results = $query->find();
			$html = '';
			$all_users = $this->allUserPdfNotifications($results);
            $i = 0;
			foreach ($all_users as $users) {
			
				$query->equalTo('UserId', $all_users[$i]);
				$user_details = $query->find();
			    $extra = $this->extraClickInfo($user_details, $all_users[$i], $id);
				$html .= '<tr><td></td><td></td><td></td></tr><tr>
				<td class="details-control"></td><td>'.$this->getUsername($all_users[$i]).'</td>
				<td>'.$query->count().'</td>'.$extra.'</tr>';
				$i+=1;
			}
		return $html;
		}
		
		public function extraClickInfo($userDetails, $UserId, $id) {
			//echo "Krsna5 inside extraClickInfo<br>";
			
			$html = '';
			$timezone = $this->getTimezone();
			foreach ($userDetails as $userClick) {
				//echo "Krsna trying to print <br>";
				//echo $userClick->get("UserId");
				//echo $userClick->get("NotificationId");
				//echo $userClick->get("createdAt");
				
				//$dateParseIn = $allInteractions[$i]->get("timeIn")->format("Y-m-d H:i:s");
				//$dateParseIn = $this->_settimezone($dateParseIn, $real_t);
				//echo "<br> Krsna2 before updatedAt";
				//echo var_dump($userDetails[0]->getCreatedAt());
				//echo var_dump($userDetails[0]->get("updatedAt"));
				$dateParseIn = $userClick->getCreatedAt()->format("Y-m-d H:i:s");
				//echo var_dump($dateParseIn);
				//echo "<br> Krsna after updatedAt";
				//echo var_dump($userDetails[0]);
				//echo $this->_settimezone($dateParseIn, $timezone);
			//	echo "<br>Krsna after print <br>";
				//$html .= "<tr><td class='extra-info'><i class='fa fa-arrow-circle-right'> </i> ".$this->_settimezone($dateParseIn, $timezone)."</td>".
					// 	 "<td class='extra-info'>".$userClick->get('UserId') ."</td></tr>";
				$dateParseOut = $this->_settimezone($dateParseIn, $timezone);
				//echo $dateParseOut;
				//echo "<br>";
				//$html .= '<tr><td class="extra-info">'.$dateParseOut.'</td></tr>';
						 //'<td class="extra-info">'. $userClick->get('UserId') .'</td>';					
                //$html .= '<tr><td class="extra-info"><i class="fa fa-arrow-circle-right"> </i> '.$dateParseOut.'</td>'.
				  //  	 '<td class="extra-info">'.$userClick->get('UserId') .'</td></tr>';
                $html .= '<td class="extra-info"><i class="fa fa-arrow-circle-right"> </i> '.$dateParseOut.'</td>';						 
			}
			
			return $html;
		}

		
	/*	private function extraInteractionInfo($obj_array, $eventId) {
			$query = $this->parseQuery("BeaconInteraction");
			$query->equalTo("eventId", $eventId);
			$query->containedIn("objectId", $obj_array);
			$query->limit(1000);
			$results = $query->find();
			$html = '';
			$timezone = $this->getTimezone();
			foreach ($results as $interaction) {
				$html .= '<td class="extra-info"><i class="fa fa-arrow-circle-right"> </i> '.$this->_settimezone($interaction->get("timeIn")->format("Y-m-d H:i:s"), $timezone).'</td>'.
					 	 '<td class="extra-info">'.$this->MMSS($interaction->get("timeSpentMillis")) .'</td>';
			}
			return $html;
		}*/
		
		private function allUserUrlNotifications($urlnotifications) {
			$users_array = array();
			foreach ($urlnotifications as $urlnot) {
				if (!in_array($urlnot->get("UserId"), $users_array)) {
					array_push($users_array, $urlnot->get("UserId"));
				}
			}
			return $users_array;
		}
		
		public function buildUrlNotificationTracking( $id) {
			$obj_array = array();
		 	$query = $this->parseQuery("NotificationTracking");
	        $query->equalTo('NotificationId', $id);
			$query->equalTo('NotificationType', 'URL');
            $results = $query->find();
			$html = '';
			$all_users = $this->allUserUrlNotifications($results);

            //echo count($all_users);			
			
			$i = 0;
			foreach ($all_users as $users) {
				//Get the count of url clicks for each unique user
				//echo "<br> $all_users[$i] <br>";
				$query->equalTo('UserId', $all_users[$i]);
				$user_details = $query->find();
				//echo "Krsna after filter<br>";
				//echo $query->count();
				//array_push($obj_array, $all_users[$i]->getObjectId());
				//echo $obj_array "<br>";
				
			    $extra = $this->extraClickInfo($user_details, $all_users[$i], $id);
				//echo "<br>";
				//echo var_dump($extra);
				//echo "<br> Krsna after extraClick <br>";
				//$html .= '<tr>
				//<td class="details-control"></td><td>'.$this->getUsername($all_users[$i]).'</td>
				//<td>'.$query->count().'</td>'.$extra.'</tr>';
							 
				$html .= "<tr><td></td><td></td><td></td></tr><tr><td class='details-control'></td><td>".$this->getUsername($all_users[$i])."</td>"
						."<td>".$query->count(). "</td>". $extra . '</tr>';

				
				$i+=1;
			}
		
			/*foreach ($results as $recv) {
				$html .= '<tr>
					<td>'.$this->getUsername($recv->get("UserId")).'</td>
					<td>'.$this->getNumberOfObjects('NotificationTracking', $recv->getObjectId(), 'NotificationId',$recv->get("UserId"), 'UserId' ).'</td>
				</tr>';
			}*/
			return $html;
		}
		
		public function buildCompleteAttendee($eventId) {
			$allBeacons = $this->getAllBeaconsNew($eventId);
			$query = $this->parseQuery("BeaconInteraction");
			$query->equalTo("eventId", $eventId);
			$query->containedIn("beaconId", $allBeacons);
			$query->limit(1000);
			$results = $query->find();
			$allUsers = $this->allUserInteractionsBeacon($results);
			$html = '';
			$used_app_q = $this->parseQuery("Attendee");
			$used_app_q->equalTo("EventId", $eventId);
			$used_app = $used_app_q->find();
			foreach($used_app as $user) {
				$html .= '<tr>
					<td>'.$this->getUsername($user->get("UserId")).'</td>
					<td><i class="fa fa-circle-o"></i></td>
				</tr>';
			}
			foreach ($allUsers as $user) {
				$html .= '<tr>
					<td>'.$this->getUsername($user["userId"]).'</td>
					<td><i class="fa fa-circle"></i></td>
				</tr>';
			}
			return $html;
		}
		/**
		 * @param $userId String of Object Id of current User
		 * @return String ObjectId of latest event or null if there is none set
		 */
		public function getLatestEvent($userId) {
			$userInfo = $this->getUserInfo($userId);
			if ($userInfo->get("currentEventId")) {
				$current = $userInfo->get("currentEventId");
			}
			return $current != NULL ? $current : 'Default';
		}
		/**
		 * @param $graph_type String of the specific $_GET passed
		 * @return Array Builds create graph based on @param
		 */
		public function buildCorrectGraph($graph_type) {
	          			switch ($graph_type) {
				case 'Beacons':
					//echo "<br> Krsna printing before Beacon <br>";
					return array($this->buildEventInteractionNew($_GET['event']));
					//echo "<br> Krsna printing after Beacon <br>";	
					break;
				case 'Visitors':
					//echo "<br> Krsna printing before Beacon <br>";
					return array($this->buildVisitorInteraction($_GET['event']));
					//echo "<br> Krsna printing after Beacon <br>";	
					break;
				case 'Sessions':
					return array($this->buildSessionAttendance($_GET['event']));
					break;
				case 'SpeakersByAttendance':
					return array($this->buildSpeakerAttendance($_GET['event']));
					break;
				case 'Speakers':
					return array($this->buildSpeakerRanking($_GET['event']));
					break;
				case 'Exhibitors':
					return array($this->buildExhibitorRating($_GET['event']));
					break;
				case 'Exhibitors-Interactions':
					return array($this->buildExhibitorInteractions($_GET['event']));
					break;
				default:
					if($this->isBeacon($graph_type)) {
						//echo "<br> Krsna printing if isBeacon <br>";	
					    return array($this->buildBeaconHourlyInteractions($graph_type, $_GET['event']));
					}else if ($this->isBeacon($graph_type) && $_SESSION['userRole'] == 'e') {
						//echo "<br> Krsna printing if isBeacon <br>";	
					    return array($this->buildVisitorBeaconHourlyInteractions($graph_type, $_GET['event']));  
					}
					
					else printf("Build is Incorrect");
					break;
			}
		}
		/**
		 * @param $eventId     The eventId of the current event
		 * @param $specific    The specific info for the call
		 * @param $type 	   The type of graph specified
		 * @param $extra       Extra info for certain functions. Set to null by default
		 * @return Specific info based on the type
		 */
		public function HandleAJAX($eventId, $specific, $type, $extra = null) {
		//	echo "<br> ajax starts here<br>";
			switch ($type) {
				case 'Speakers':
					//echo "<br> Krsna printing type";
					//echo $type;
					return $this->buildSpecificSpeakerRank($eventId, $specific, 0);
					break;
				case 'Beacons':
					return $this->buildBeaconHourlyInteractions($specific, $eventId);
					break;
				case 'Visitors':
					//return $this->buildVisitorBeaconHourlyInteractions($specific, $eventId);
					return $this->buildVisitorUserTable($specific,$eventId);
					break;
				case 'Hourly':
					return $this->buildBeaconPersonTable($eventId, $specific, $extra);
					break;
				case 'Exhibitors':
					return $this->buildExhibitorUserTable($specific, $eventId);
					break;
				case 'Sessions':
					return $this->buildSessionUserTable($specific, $eventId);
					break;
				case 'SpeakersByAttendance':
					return $this->buildSpeakersByAttendanceUserTable($specific, $eventId);
					break;
				case 'Exhibitors-Interactions':
					return $this->buildExhibitorInteractionsTable($eventId, $specific);
					break;
				default:
					printf("Error : Cannot load Ajax Call");
					break;
			}
		}
		
		 public function getCorrectTableHead($type) {
			switch ($type) {
				case 'Speakers':
					return '<tr><th></th><th>User&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Average Rating</th></tr>';
					break;
				case 'SpeakersByAttendance':	
					//return '<tr><th>User</th></tr>';
					return '<tr><th></th><th>User&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Average Dwell Time (MM:SS)</th></tr>';
					break;
				case 'Notifications':
					if ($_SESSION['userRole'] == 'o') {
					return '<tr><th>Title</th><th>Triggered</th><th>Saved</th><th>Click(PDF)</th><th>Click(URL)</th></tr>';
					}else if($_SESSION['userRole'] == 'e') {
					return '<tr><th>Title</th><th>Presented</th><th>Saved</th><th>Click(PDF)</th><th>Click(URL)</th></tr>';	
					}
					break;
				case 'Content':
					return '<tr><th>Title</th><th>Click(PDF)</th><th>Click(URL)</th></tr>';
					break;
				case 'ReceivedNotification':
					return '<tr><th>User</th></tr>';
					break;
				case 'SavedNotification':
					return '<tr><th>User</th></tr>';
					break;
				case 'ClickedPdfTracking':
				    //return '<tr><th>User</th></tr>';
					return '<tr><th></th><th>User</th><th>Clicks</th></tr>';
					break;
				case 'ClickedUrlTracking':
					//return '<tr><th>User</th></tr>';
					return '<tr><th></th><th>User</th><th>Clicks</th></tr>';
					break;
				case 'ContentPdfTracking':
				    //return '<tr><th>User</th></tr>';
					return '<tr><th></th><th>User</th><th>Clicks</th></tr>';
					break;
				case 'ContentUrlTracking':
				    //return '<tr><th>User</th></tr>';
					return '<tr><th></th><th>User</th><th>Clicks</th></tr>';
					break;
				case 'Exhibitors':
					return '<tr><th>User</th><th>Rating</th></tr>';
					break;
				case 'Sessions':
					return '<tr><th></th><th>User&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Average Dwell Time (MM:SS)</th></tr>';
					break;
				case 'Visitors':
					return '<tr><th></th><th>User&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Average Dwell Time (MM:SS)</th></tr>';
					break;
				case 'Exhibitors-Interactions':
					return '<tr><th></th><th>User</th><th>Average Dwell Time (MM:SS)</th></tr>';
					break;
				default:
					if($this->isBeacon($type)) return '<tr><th></th><th>User</th><th>Average Dwell Time (MM:SS)</th></tr>'; else return '<tr><th></th></tr>';
					printf("Cannot get Correct Head");
					break;
			}
		}
	}
