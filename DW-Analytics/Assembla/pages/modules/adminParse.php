<?php 
	/** -- QUICK SEARCH --
		1. Namespaces
		2. Local Variables
		3. Connections
		4. Getter
		5. Setter
		6. Builder
		7. Event_build
		8. Submission
		9. Update
		10. Delete
	**/
	/**
	  * @uses parseInit to connect to Parse Database
	  */
	require_once ($_SERVER['DOCUMENT_ROOT']."/pages/modules/parseInit.php");
	/**
	  * @uses vendor/parse/php-sdk/src/Parse/ For Namespaces
	  */
	use Parse\ParseObject;
	use Parse\ParseQuery;
	use Parse\ParseUser;
	use Parse\ParseFile;
	use Parse\ParseException;

	class AdminParseAPI {
		/**
		  * @var String eventId 	 is Set with @method setEventId
		  * @var String event  		 is Set with @method setEventId
		  * @var Array tables 		 are pre-set to Parse Table names
		  */
		public $eventId;
		public $event;
		private $tables = array(
			'Beacon' 				=> 'ba_tb_000',
			'BeaconInteraction'		=> 'ba_tb_001',
			'Company' 				=> 'ba_tb_002',
			'CompanyRating' 		=> 'ba_tb_003',
			'Event' 				=> 'ba_tb_004',
			'EventCompany' 			=> 'ba_tb_005',
			'Notification' 			=> 'ba_tb_006',
			'ReceivedNotification' 	=> 'ba_tb_007',
			'SavedNotification'		=> 'ba_tb_008',
			'Session' 				=> 'ba_tb_009',
			'SessionRSVP' 			=> 'ba_tb_010',
			'Speaker' 				=> 'ba_tb_011',
			'Survey' 				=> 'ba_tb_012',
			'SurveyQuestion' 		=> 'ba_tb_013',
			'SurveyResponse'   	    => 'ba_tb_014',
			'Exhibitor'				=> 'ba_tb_015',
		    'Sponsor'               => 'ba_tb_016',
		    'Content'               => 'ba_tb_017'
		);
		private function parseQuery($className) {
			return new ParseQuery($className); 
		}
		/* Getter Functions */
		private function getSelectedTable($table) {
			return array_search($table, $this->tables);
		}
		private function getUserInfo($organizer) {
			$user = ParseUser::query();
			$user->equalTo("objectId", $organizer);
			$user->limit(1);
			return $user->first();
		}
		private function getSpeakerInfo($speakerId) {
			$query = $this->parseQuery("Speaker");
			$query->equalTo("objectId", $speakerId);
			$query->limit(1);
			return $query->first();
		}
	   
	  private function getSponsorInfo($sponsorId) {
			$query = $this->parseQuery("Sponsor");
			$query->equalTo("objectId", $sponsorId);
			$query->limit(1);
			return $query->first();
	  }
	  public function getEventInfoById($eventId) {
	  	 
	  	return $this->getEventInfoForEdit($eventId);
	  }		
	  public function getSpeakerInfoById($speakerId) {
	  	 
	  	return $this->getSpeakerInfo($speakerId);
	  }
	  public function getSessionInfoById($sessionId) {
			
			return $this->getSessionInfo($sessionId);
		}
	 public function getContentInfoById($contentId) {
			
			return $this->getContentInfo($contentId);
		}
	    public function getBeaconInfoById($beaconId) {
			
			return $this->getBeaconInfo($beaconId);
		}
	    public function getNotificationInfoById($notificationId) {
			
			return $this->getNotificationInfo($notificationId);
		}
	    public function getExhibitorInfoById($exhibitorId) {
			
			return $this->getExhibitorInfo($exhibitorId);
		}
	   public function getSponsorInfoById($sponsorId) {
			
			return $this->getSponsorInfo($sponsorId);
		}
	 /* public function getSurveyQuestionById($sponsorId) {
			
			return $this->getSurveyQuestionInfo($sponsorId);
		}*/
		
		private function getSurveyInfo($id) {
			$query = $this->parseQuery("Survey");
			$query->equalTo("objectId", $id);
			return $query->first();
		}
		public function getSurveyById($surveyId) {
			return $this->getSurveyInfo($surveyId);
		}
		private function getSpeakerQuestions($speakerId) {
			$questionsQ = $this->parseQuery("SurveyQuestion");
			$questionsQ->equalTo("speakerId", $speakerId);
			$questionsQ->select(['position', 'question']);
			$questionsQ->ascending("position");
			return $questionsQ->find();
		}
		private function getNotificationInfo($notificationId) {
			$query = $this->parseQuery("Notification");
			$query->equalTo("objectId", $notificationId);
			$query->limit(1);
			return $query->first();
		} 
		private function getCompanyId($company) {
			$query = $this->parseQuery("Company");
			$query->equalTo("name", $company);
			$query->limit(1);
			return $query->first();
		}

		public function getCompanyName($companyId) {
			if($companyId) {
				$query = $this->parseQuery("Company");
				$query->equalTo('objectId', $companyId);
				$query->select(['objectId', 'name']);
				$results = $query->first();
				return $results->get('name');
			} return;
		}
		private function getCompanyInfo($companyId) {
			$query = $this->parseQuery("Company");
			$query->equalTo("objectId", $companyId);
			$query->limit(1);
			return $query->first();
		}
		public function getEventId($eventName) {
			$eventName = $this->getCorrectGET($eventName);
			$query = $this->parseQuery("Event");
			$query->equalTo("name", $eventName);
			$query->limit(1);
			return $query->first();
			///$results = $query->first();
			//return ($results->get("objectId"));
		}
		public function getEventInfo($eventId) {
			$query = $this->parseQuery("Event");
			$query->equalTo("objectId", $eventId);
			$query->limit(1);
			return $query->first();
			//return $query->first()->getObjectId();
		}
		
		public function getEventById($eventId) {
			return $this->getEventInfo($eventId);
		}
		
		private function getSpeakerName($speakerId) {
			$query = $this->parseQuery("Speaker");
			$query->equalTo("objectId", $speakerId);
			$query->select(['firstName', 'lastName']);
			$query->limit(1);
			$results = $query->first();
			return ($results->get('firstName') . ' ' . $results->get("lastName"));
		}
		private function getSessionTitle($id) {
			$query = $this->parseQuery("Session");
			$query->equalTo("objectId", $id);
			$query->limit(1);
			return $query->first()->get("title");
		}
		private function getSessionInfo1($id) {
			$query = $this->parseQuery("Session");
			$query->equalTo("eventId", $id);
			$query->count("speakerIds")>0;
			//return $query->first();
			$query->limit(1);
			return $query->first();
		}
	   private function getSessionInfo($id) {
			$query = $this->parseQuery("Session");
			$query->equalTo("objectId", $id);
			//$query->count("speakerIds")>0;
			//return $query->first();
			$query->limit(1);
			return $query->first();
		}
		
		/*
		 * $id - event id
		 */
	   private function getNotificsWithBeacons($id) {
		    $query = $this->parseQuery("Notification");
			$query->equalTo("beaconId", $id);
			//$query.whereExists("beaconId");
			//$query->count("beaconId")>0;
			return $query->find();	
		}
		private function getContentInfo($id) {
			$query = $this->parseQuery("Content");
			$query->equalTo("objectId", $id);
			$query->limit(1);
			return $query->first();
		}
	   private function getSurveyQuestionInfo($id) {
			$query = $this->parseQuery("SurveyQuestion");
			$query->equalTo("objectId", $id);
			$query->limit(1);
			return $query->first();
		}
		public function getSurveyQuestionById($id) {
			return $this->getSurveyQuestionInfo($id);
		}
	   private function getExhibitorInfo($id) {
			$query = $this->parseQuery("Exhibitor");
			$query->equalTo("objectId", $id);
			$query->limit(1);
			return $query->first();
		}
	  private function getEventInfoForEdit($id) {
			$query = $this->parseQuery("Event");
			$query->equalTo("objectId", $id);
			$query->limit(1);
			return $query->first();
		}
	   private function getBeaconInfo($id) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("objectId", $id);
			$query->limit(1);
			return $query->first();
		}
		private function getAllEvents($organizer) {
			$company_event = $this->parseQuery("Event");
			$company_event->containedIn("organizers", [$organizer]);
			return $company_event->find();
		}
		private function getBeaconId($beacon) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("name", $beacon);
			$query->limit(1);
			return $query->first()->getObjectId();
		}
		private function getBeaconName($beacon) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("objectId", $beacon);
			$query->limit(1);
			return $query->first()->get("name");
		}
	    private function getEventName($event) {
			$query = $this->parseQuery("Event");
			$query->equalTo("objectId", $event);
			$query->limit(1);
			return $query->first()->get("name");
		}
		/**
		  * @return Array of Key => Value all Beacons from Event based on event ID
		  */
		private function getAllBeaconsForEvent($eventId) {
			//$eventId = $this->eventId;
			$query = $this->parseQuery("Beacon");
			$query->equalTo("eventId", $eventId);
			return $query->find();
		}
		private function getSpeakersAtSession($sessionId) {
			$query = $this->parseQuery("Session");
			$query->equalTo("objectId", $sessionId);
			return $query->first()->get("speakerIds");
		}
		private function getTwitterHash($hash) {
			$returnedArray = array();
			$regex = '/\#/';
			$matches = preg_split($regex, $hash);
			foreach($matches as $mat) {
				array_push($returnedArray, $mat);
			}
			$newArray = array_shift($returnedArray);
			return $returnedArray;
		}
		
		public function getAvailableBeacons() {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("IsAvailable", true);
		  //  $beacon_obj = $query->first();
		//	return $beacon_obj->get("major");
			return $query->first();
			//return $query->first()
		}
		
		/** @NOTE:
			Following function returns correct get variable. Coincides with .htaccess file		
		**/
		public function getCorrectGET($GET) {
			$regex = '/\-/';
			$matches = preg_replace($regex, ' ', $GET);
			if($matches) {
				return $matches;
			}
			return preg_replace('#[^0-9a-z]#i', '', $GET);
		}
		/** @NOTE:
			Following two functions need to redefine eventId because they are ajax calls
		**/
		private function getCompaniesAtEvent($event) {
			$query = $this->parseQuery("EventCompany");
			//$query->equalTo("eventId", $this->getEventId($event));
			$query->equalTo("eventId", $event);
			$query->equalTo("companyRole", 'exhibitor');
			return $query->find();
		}
		private function getSessionsAtEvent($event) {
			$eventId = $this->getEventId($event);
			$query = $this->parseQuery("Session");
			$query->equalTo("eventId", $eventId);
			return $query->find();
		}
		private function getSessionsByEventId($eventId) {
		    $query = $this->parseQuery("Session");
			$query->equalTo("eventId", $eventId);
			return $query->find();	
		}
		/* -- End Ajax -- */
		private function getBeaconEvent($beacon) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("objectId", $beacon);
			return $query->first()->get("eventId");
		}
		private function isEvent($event, $type = null) {
			$query = $this->parseQuery("Event");
			if($type == 'object')
				$query->equalTo("objectId", $event);
			else if($type == 'name')
				$query->equalTo("name", $this->getCorrectGET($event));
			return ($query->count() > 0);
		}
		/* Setter Functions */
		public function setEventId($event) {
			if($this->isEvent($event, 'object')) {
				$this->eventId = $event;
				$this->event = $this->getEventInfo($event)->get("name");
			} else if ($this->isEvent($event, 'name')) {
				$this->eventId = $this->getEventId($event);
				$this->event = $event;
			}
			$_SESSION['eventId'] = $this->eventId != null || $this->eventId != '' ? $this->eventId : $_SESSION['eventId']; 
		}
		public function getEventFromEventCode($event_code) {
		    $query = $this->parseQuery("Event");
			$query->equalTo("eventCode", $event_code);
			$query->limit(1);
			return $query->first();	
		}
		/* Boolean and Non Specific Functions*/
		public function hasEventInfo($event, $type) {
			$hasEventInfo = false;
			$eventId = $this->eventId;
			if (!$eventId) {
				$eventId = $event;
			}
  			switch ($type) {
				case 'Speaker':
					$query = $this->parseQuery("Speaker");
					$query->equalTo("eventId", $eventId);
					$results = $query->count();
					if($results > 0) 
						$hasEventInfo = true;
					break;
				case 'Session':
					$query = $this->parseQuery("Session");
					$query->equalTo("eventId", $eventId);
					$results = $query->count();
					if($results > 0) 
						$hasEventInfo = true;
					break;
				case 'Exhibitor':
					$query = $this->parseQuery("Exhibitor");
					$query->equalTo("eventId", $eventId);
					$results = $query->count();
					if($results > 0) 
						$hasEventInfo = true;
					break;
				case 'Beacon':
					$query = $this->parseQuery("Beacon");
					$query->equalTo("eventId", $eventId);
					$results = $query->count();
					if($results > 0) 
						$hasEventInfo = true;
					break;
					
				case 'Content':
					$query = $this->parseQuery("Content");
					$query->equalTo("EventId", $eventId);
					$results = $query->count();
					if($results > 0) 
						$hasEventInfo = true;
					break;
					
				
				case 'Notification':
					$allBeacons = $this->getAllBeaconsForEvent($eventId);
					
					$beacon_array = array();
					$query = $this->parseQuery("Notification");
					foreach ($allBeacons as $beacon) {
						array_push($beacon_array, $beacon->getObjectId());
					}
					
					$query->containedIn("beaconId", $beacon_array);
					$results = $query->find();
					if (count($results) > 0) {
						$hasEventInfo = true;
						
					}
					
					
					
					
					
					/*$query = $this->parseQuery("Notification");
					$query->select("beaconId");
					$results = $query->find();
					$allBeacons = $this->getAllBeaconsForEvent($eventId);
					
					foreach($allBeacons as $beacon) {
						foreach($results as $not) {
							//echo var_dump($beacon->getObjectId());
							echo var_dump($not->get("beaconId"));
							echo "Krsna";
							if ($not->get("beaconId")) {
								if($beacon->getObjectId() == $not->get("beaconId")) {
									$hasEventInfo = true;
									break;
								}
							}
						}
					}*/
					break;
				case 'Survey':
					$query = $this->parseQuery("SurveyQuestion");
					$query->equalTo("eventId", $eventId);
					$results = $query->count();
					if($results > 0) 
						$hasEventInfo = true;
					break;
				case 'Sponsor':
					$query = $this->parseQuery("Sponsor");
					$query->equalTo("eventId", $eventId);
					$results = $query->count();
					if($results > 0) 
						$hasEventInfo = true;
					break;
				default:
					echo "Invalid Case Option";
					break;
			}
			return $hasEventInfo;
		}
		public function chooseCorrectTime($date_pass) {
			$date = new DateTime($date_pass);
			//echo ("Krsna inside cct:");
			//echo var_dump($date);
		/*	if (date('I', time()))
			{  //NO DST	
				return $date;
			}
			else
			{	//DST
				return $date->setTimezone(new DateTimeZone('UTC'));
				
			}*/

		return $date->setTimezone(new DateTimeZone('UTC'));			
		}
		
		public function setTimeZoneForPersist($time, $newzone = null, $defaultzone = 'UTC') {
			$date = new DateTime($time, new DateTimeZone($newzone));
			$date->setTimezone(new DateTimeZone($defaultzone));
			return $date;
		}
		
		public function setTimeZone($time, $newzone = null, $defaultzone = 'UTC') {
			$date = new DateTime($time, new DateTimeZone($defaultzone));
			if ($newzone) {
			    $date->setTimezone(new DateTimeZone($newzone));
			}
		//	echo ("Krsna inside stz:");
		//	echo var_dump($date);
			
			return $date;
		}
		
		public function chooseCorrectEditFill($table, $id) {
			$table_name = $this->getSelectedTable($table);
			switch ($table_name) {
				case 'Event':
					return $this->buildEditFillEvent($id);
					break;
				case 'Speaker':
					return $this->buildEditFillSpeaker($id);
					break;
				case 'Session':
					return $this->buildEditFillSession($id);
					break;
				case 'Exhibitor':
					return $this->buildEditFillExhibitor($id);
					break;
				case 'Beacon':
					return $this->buildEditFillBeacon($id);
					break;
				case 'Notification':
					return $this->buildEditFillNotification($id);
					break;
				case 'Survey':
					return $this->buildEditFillSurvey($id);
					break;
				default:
					echo "Cannot choose correct Fill";
					break;
			}
		}
		private function splitName($fullName) {
			$returnedArray = array();
			$regex = '/\b/';
			$matches = preg_split($regex, $fullName);
			foreach($matches as $mat) {
				array_push($returnedArray, $mat);
			}
			$newArray = array_shift($returnedArray);
			return $returnedArray;
		}
		
		private function createTwitterUrl($twitter){
		//Convert attags to twitter profiles in <a> links
  		//$twitter = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.twitter.com/$1\">@$1</a>", $tweet);
		   $url = "https://twitter.com/";
		   $format_tw_url = str_replace('@', ' ', $twitter);
		   $good_tw_url = ltrim($format_tw_url, ' ');
					
		   $twitter = $url . $good_tw_url;
  		   return $twitter;
			
		}
		private function makeJSONArray($arr) {
			return json_encode($arr);
		}
		/*
		 * validate functions
		 */
		public function getEventByCode($eventCode) {
			$query = $this->parseQuery("Event");
			$query->equalTo("eventCode", $eventCode);
			$event_rows = $query->find();
			return count($event_rows);
		}
		/* Builder Functions */
		public function buildEventEditTable($organizer) {
			$allEvents = $this->getAllEvents($organizer);
			$html = "";
			for ($i = 0; $i < count($allEvents); $i++) {
				$current_event = $this->getEventInfo($allEvents[$i]->getObjectId());
				$event_id = $current_event->getObjectId();
				//$event_id = $allEvents[$i]->getObjectId();
				//$current_event = $this->getEventInfo($event_id);
			//	$name = $current_event->get("name");
									//$html .= "<tr>"
				//$html .= "<tr data-href='/Organizer/Admin/$event_id' data-event=$event_id>"
				if ($_SESSION['eventId'] == $event_id) {
					//$table_row = "<tr bgcolor='#e8e8e8' data-event=$event_id>";
					$table_row = "<tr class='highlight' data-event=$event_id>";
				} else {
					$table_row = "<tr data-event=$event_id>";
				}
				 
	           // echo var_dump($_GET['action']);

				$html .= $table_row
						."<td class='disable-link'>". $current_event->get('name') . "</td>"
						."<td class='disable-link'>". $current_event->get("location") . "</td>"
						."<td class='disable-link'>". $current_event->get("city") . ', ' . $current_event->get("state") . "</td>"
						."<td class='disable-link'>". $current_event->get('startDate')->format('Y-m-d') . "</td>"
						."<td>". "<a href='/Organizer/admin/edit/". $current_event->get("eventCode") ."' onclick='stopPropagation(event)'><i class='fa fa-edit'> </i>Edit</a></td>"
						."<td> <a class='view_chosen' data-info=$event_id href=''><i class='fa fa-bars'> </i>Report</a></td>"
						."</tr>";
			}
			/*
			 * unset active_tab, since events table will be listed on home page
			 */
			unset($_SESSION['active_tab']);
			return $html;
		}
		/* Event_build */
		public function buildEventEditDisplay($event) {
			$eventId = $this->eventId;
			if (!eventId) {
				$eventId = $event;
			}
			$query = $this->parseQuery("Event");
			$query->equalTo("objectId", $eventId);
			$event = $query->first();
			
			$html = "<table id='eventList' class='table table_sortable table_pick datatable display compact table-hover' >";
			//$html = "<table id='eventList' class='table table_sortable table_pick datatable display table-hover'>";
			
			$html .= "<thead><tr><th class='table-header'>Name</th>"
					."<th class='table-header'>Code</th>"
					."<th class='table-header'>Location</th>"
					."<th class='table-header'>Address</th>"
					."<th class='table-header'>Start Time</th>"
					."<th class='table-header'>End Time</th>"
					."<th class='table-header'>#Tags</th>"
					."<th class='table-header'>Edit</th></tr></thead><tbody>";
			
			if($event) {
				for($i = 0; $i < count($event->get("twTags")); $i++) {
					$twitter .= '#'. $event->get("twTags")[$i] . ' ';
				}
				//redundant, but to simplify and less code
				$event_event_id = $event->get("eventCode") . "/event";
				$table = $this->tables['Event'];
				$currentStartDateTime = $this->setTimeZone($event->get("startDate")->format("Y-m-d H:i:s"), $event->get("timeZone"))->format("Y-m-d H:i:s");
                $newStartDateTime = date('h:i A', strtotime($currentStartDateTime));
				$currentEndDateTime = $this->setTimeZone($event->get("endDate")->format("Y-m-d H:i:s"), $event->get("timeZone"))->format("Y-m-d H:i:s");
                $newEndDateTime = date('h:i A', strtotime($currentEndDateTime));
				
				$html .= "<tr><td>". $event->get("name")."</td>"
					   	 //."<td><img src='". ($event->get("logo") ? $event->get("logo")->getURL() : '/assets/img/logo_placeholder.png')."' width='50'></td>"
					   	 ."<td>". $event->get("eventCode")."</td>"
					   	 ."<td>". $event->get("location")."</td>"
					   	 ."<td>". $event->get("city"). ', ' . $event->get("state")."</td>"
					   	 ."<td>". ($event->get("startDate")->format("Y-m-d").', '. $newStartDateTime) ."</td>"
					   	 ."<td>". ($event->get("endDate")->format("Y-m-d").', '. $newEndDateTime) ."</td>"
					   	// ."<td>". $this->setTimeZone($event->get("startDate")->format("Y-m-d H:i:s"), $event->get("timeZone"))->date('h:i A', strtotime($currentDateTime)); ."</td>"
					   	// ."<td>". $this->setTimeZone($event->get("endDate")->format("Y-m-d H:i:s"), $event->get("timeZone"))->format("Y-m-d H:i:s") ."</td>"	
					   	 //."<td>". $event->get("address"). ', ' . $event->get("city")."</td>"
					   	 ."<td>". $twitter ."</td>"
					   	 
					   	 ."<td><a href='/Organizer/admin/edit/" . $event_event_id ."'><i class='fa fa-plus-square-o'></i>  Edit</a></td>"
						."</tr>";
			} else {
				$html .= '<tr><td>No Event Info Added Yet</td></tr>';
			}
			$html .= "</tbody></table>";
			return $html;
		}
		
	public function buildSpeakersAtEvent($event, $isAddBtnNeeded = true) {
			$_SESSION['updateObjId'] = null;
			unset($_SESSION['updateObjId']);
			$eventId = $this->eventId;
			if (!eventId) {
				$eventId = $event;
			}
			$tags_added = 0;
			$query = $this->parseQuery("Speaker");
			$query->select(["firstName", "lastName","company","title","twitterURL","linkedInURL"]);
			$query->equalTo("eventId", $eventId);
			$results = $query->find();
			//$html = "<table id='records-speakers' class='table table_sortable table_pick datatable display compact table-hover'>";
			$html = "<table id='speakersList' class='table table_sortable table_pick datatable display compact table-hover'>";
			
			$html .= "<thead><tr><th class='table-header'>Name</th>"
					."<th class='table-header'>Company</th>"
					."<th class='table-header'>Title</th>"
					."<th class='table-header'>TwitterURL</th>"
					."<th class='table-header'>LinkedInURL</th>"
					."<th class='table-header'>Edit</th>"
					."<th class='table-header'>Delete</th></tr></thead><tbody>";
			$eventObj = $this->getEventInfo($eventId);
			if(count($results) > 0) {
				foreach($results as $speaker) {
					$id = $speaker->getObjectId();
					$table = $this->tables['Speaker'];
					/*$avatar = $speaker->get("avatar") ? $speaker->get("avatar")->getURL() : "/assets/img/avatar5.png";
					$html .= "<tr><td><img src='$avatar' alt='' width='50'></td>"
							."<td>" . $speaker->get("firstName") . " " . $speaker->get("lastName")."</td>"
							."<td>". $speaker->get("company") ."</td>"
						."<td>". $speaker->get("title") ."</td>"
							."<td><a href='/Organizer/Admin/edit/$event/$id/$table'><i class='fa fa-plus-square-o'></i>  Edit</a></td>"
							."<td><a class='delete_chosen' data-info='$id' data-info2='$table' data-toggle='modal' data-target='#deleteModal' href=''><i class='fa fa-trash'></i>  Delete</a></td>"
							. "</tr>";*/
					$event_speaker_id = $eventObj->get("eventCode") . "/edit-speaker/" . $id;
					if($speaker->get("linkedInURL")){
						$linkedin_td = "<i class='fa fa-linkedin'> </i>";
						
					}
					else{
						$linkedin_td = " ";
					}
				if($speaker->get("twitterURL")){
						$tw_td = "<i class='fa fa-twitter'> </i>";
						
					}
					else{
						$tw_td = " ";
					}
					
					$id_table = $id . ':' . $table; 
					$html .= "<tr class='deleteRec' data-id=$id_table><td>" . $speaker->get("firstName") . " " . $speaker->get("lastName")."</td>"
					        ."<td>". $speaker->get("company") ."</td>"
					        ."<td>". $speaker->get("title") ."</td>"
					        ."<td><a target='_blank' href=". $speaker->get("twitterURL") .">".$tw_td."</a></td>"
					        ."<td><a target='_blank' href=". $speaker->get("linkedInURL") .">".$linkedin_td."</a></td>"
					         //."<td>". $speaker->get("linkedInURL") ."<i class='fa fa-linkedin'> </i></td>"
					        ."<td><a href='/Organizer/admin/edit/" . $event_speaker_id ."'><i class='fa fa-plus-square-o'></i>  Edit</a></td>"
					        ."<td><a class='deleteRec' href=''><i class='fa fa-trash'></i>  Delete</a></td>"
					        . "</tr>";
					        
				}
				$tags_added = 1;
			} else {
				//we should not hit this, as add form will be displayed
				$html .= '<tr><td>No Speakers Added Yet</td></tr>';
			}
			$html .= "</tbody></table>";
			/*if ($tags_added && $isAddBtnNeeded) {
			    $html .= "<form action='/Organizer/Admin/Edit/" . $_GET[action] ."' method = 'POST'><button name='add_speaker_details' id='addSpeaker' href='javascript:;' type='submit' class='btn btn-custom btn-sm btn-block'>Add Speaker</button></form>";
			} */
			//echo var_dump($html);
			return $html;
		}
		
		public function buildSessionsAtEvent($event) {
			$_SESSION['updateObjId'] = null;
			unset($_SESSION['updateObjId']);
			$eventId = $this->eventId;
			if (!eventId) {
				$eventId = $event;
			}
			$eventObj = $this->getEventInfo($eventId);
			$tags_added = 0;
			$query = $this->parseQuery("Session");
			$query->equalTo("eventId", $eventId);
			$results = $query->find();
			//$html = "<table id='records-sessions' class='table table_sortable table_pick datatable display table-hover'>";
			$html = "<table id='sessionsList' class='table table_sortable table_pick datatable display table-hover'>";
			$html .= "<thead><tr><th class='table-header'>Title</th>"
					//."<th class='table-header'>Room</th>"
					//."<th class='table-header'>Speaker</th>"
					."<th class='table-header'>Start Time</th>"
					//."<th class='table-header'>Details</th>"
					."<th class='table-header'>End Time</th>"
					."<th class='table-header'>Edit</th>"
					."<th class='table-header'>Delete</th></tr></thead><tbody>";
					
			if(count($results) > 0) {
				foreach($results as $session) {
					$id = $session->getObjectId();
					$table = $this->tables['Session'];
					//$speakers = $this->getSpeakersAtSession($session->getObjectId());
					$event_session_id = $eventObj->get("eventCode") . "/edit-session/" . $id;
					$id_table = $id. ':' . $table;
					
				$currentStartTime = $this->setTimeZone($session->get("startTime")->format("Y-m-d H:i:s"), $eventObj->get("timeZone"))->format("Y-m-d H:i:s");
                $newStartTime = date('h:i A', strtotime($currentStartTime));
				$currentEndTime = $this->setTimeZone($session->get("endTime")->format("Y-m-d H:i:s"), $eventObj->get("timeZone"))->format("Y-m-d H:i:s");
                $newEndTime = date('h:i A', strtotime($currentEndTime));
				
					$html .= "<tr class='deleteRec' data-id=$id_table>"
							."<td>". $session->get("title") ."</td>"
							//."<td>". $session->get("roomName") ."</td>"
							//."<td>Speaker</td>"
							//."<td>". ($event->get("startDate")->format("Y-m-d").' '. $newStartDateTime) ."</td>"
							."<td>".($session->get("startTime")->format("Y-m-d").', '. $newStartTime)."</td>"
							."<td>".($session->get("endTime")->format("Y-m-d").', '. $newEndTime)."</td>"
							//."<td>". substr(trim($session->get("details")), 0, 50) ."...</td>"
							//."<td>". $this->setTimeZone($session->get("endTime")->format("Y-m-d H:i:s"), $eventObj->get("timeZone"))->format("Y-m-d H:i:s") ."</td>"
							."<td><a href='/Organizer/admin/edit/" . $event_session_id ."'><i class='fa fa-plus-square-o'></i>  Edit</a></td>"
							."<td><a class='deleteRec' href=''><i class='fa fa-trash'></i>  Delete</a></td>"
							."</tr>";
					}
					$tags_added = 1;
			} else {
				$html .= '<tr><td>No Sessions Added Yet</td></tr>';
			}
			$html .= "</tbody></table>";
			/*if ($tags_added) {
				$html .= "<form action='/Organizer/Admin/Edit/" . $_GET[action] ."' method = 'POST'><button name='add_session_details' id='addSession' href='javascript:;' type='submit' class='btn btn-custom btn-sm btn-block'>Add Session</button></form>";
			}*/
			return $html;
		}
		public function buildEditAtEvent($event) {
		    $_SESSION['updateObjId'] = null;
			unset($_SESSION['updateObjId']);
			$eventId = $this->eventId;
			if (!$eventId) {
				$eventId = $event;
			}
			$tags_added = 0;
			$query = $this->parseQuery("Event");
			$query->equalTo("objectId", $eventId);
			$results = $query->find();
			//$html = "<table id='records-event' class='table table_sortable table_pick datatable display table-hover'>";
			$html = "<table id='eventList' class='table table_sortable table_pick datatable display table-hover'>";
			$html .= "<thead><tr><th class='table-header'>Name</th>"
					."<th class='table-header'>Start Date</th>"
					."<th class='table-header'>End Date</th>"
					."<th class='table-header'>Edit</th>"
					."<th class='table-header'>Delete</th></tr></thead><tbody>";
					
			if(count($results) > 0) {
				foreach ($results as $ev) {
					$id = $ev->getObjectId();
					$table = $this->tables['Event'];
					$event_edit_id = $eventId . "/Edit_" . $id;
					// $companyInfo = $this->getCompanyInfo($exhibitor->get("companyId"));
					//$logo = $exhibitor->get("logo") ? $exhibitor->get("logo")->getURL() : "/assets/img/logo_placeholder.png";
					$id_table = $id . ':' . $table;
					$html .= "<tr class='deleteRec' data-id=$id_table>"
							."<td>". $ev->get("name") ."</td>"
							."<td>". $ev->get("startDate") ."</td>"
							."<td>". $ev->get("endDate") ."</td>"
							."<td><a href='/Organizer/Admin/" . $event_edit_id ."'><i class='fa fa-plus-square-o'></i>  Edit</a></td>"
							."<td><a class='deleteRec' href=''><i class='fa fa-trash'></i>  Delete</a></td>"	
							."</tr>";
				}
				$tags_added = 1;
			} else {
				$html .= '<tr><td>No Events Added Yet</td></tr>';
			}
			
			$html .= "</tbody></table>";
			/*if ($tags_added) {
				$html .= "<form action='/Organizer/Admin/Edit/" . $_GET[action] ."' method = 'POST'><button name='add_exhibitor_details' id='addExhibitor' href='javascript:;' type='submit' class='btn btn-custom btn-sm btn-block'>Add Exhibitor</button></form>";
			}*/
			return $html;
		}
	
	public function buildExhibitorAtEvent($event) {
		    $_SESSION['updateObjId'] = null;
			unset($_SESSION['updateObjId']);
			$eventId = $this->eventId;
			if (!$eventId) {
				$eventId = $event;
			}
			$tags_added = 0;
			$query = $this->parseQuery("Exhibitor");
			$query->equalTo("eventId", $eventId);
			$results = $query->find();
			//$html = "<table id='records-exhibitors' class='table table_sortable table_pick datatable display table-hover'>";
			
			$html = "<table id='exhibitorsList' class='table table_sortable table_pick datatable display table-hover'>";
			
			$html .= "<thead><tr><th class='table-header'>Name</th>"
					."<th class='table-header'>Booth #</th>"
					."<th class='table-header'>Website</th>"
					."<th class='table-header'>Edit</th>"
					."<th class='table-header'>Delete</th></tr></thead><tbody>";

			$eventObj = $this->getEventInfo($eventId);
					
			if(count($results) > 0) {
				foreach ($results as $exhibitor) {
					$id = $exhibitor->getObjectId();
					$table = $this->tables['Exhibitor'];
					$event_exhibitor_id = $eventObj->get("eventCode") . "/edit-exhibitor/" . $id;
					// $companyInfo = $this->getCompanyInfo($exhibitor->get("companyId"));
					//$logo = $exhibitor->get("logo") ? $exhibitor->get("logo")->getURL() : "/assets/img/logo_placeholder.png";
					$id_table = $id . ':' . $table;
					$html .= "<tr class='deleteRec' data-id=$id_table>"
							."<td>". $exhibitor->get("name") ."</td>"
							."<td>". $exhibitor->get("booth") ."</td>"
							."<td>". $exhibitor->get("website") ."</td>"
							."<td><a href='/Organizer/admin/edit/" . $event_exhibitor_id ."'><i class='fa fa-plus-square-o'></i>  Edit</a></td>"
							."<td><a class='deleteRec' href=''><i class='fa fa-trash'></i>  Delete</a></td>"	
							."</tr>";
				}
				$tags_added = 1;
			} else {
				$html .= '<tr><td>No Exhibitors Added Yet</td></tr>';
			}
			
			$html .= "</tbody></table>";
			/*if ($tags_added) {
				$html .= "<form action='/Organizer/Admin/Edit/" . $_GET[action] ."' method = 'POST'><button name='add_exhibitor_details' id='addExhibitor' href='javascript:;' type='submit' class='btn btn-custom btn-sm btn-block'>Add Exhibitor</button></form>";
			}*/
			return $html;
		}
		/* Needs to parse for type of beacon */
		public function buildBeaconAtEvent($event) {
			$_SESSION['updateObjId'] = null;
			unset($_SESSION['updateObjId']);
			$eventId = $this->eventId;
			if (!$eventId) {
				$eventId = $event;
			}
			$tags_added = 0;
			$query = $this->parseQuery("Beacon");
			$query->equalTo("eventId", $eventId);
			$results = $query->find();
			//$html = "<table id='records-beacons' class='table table_sortable table_pick datatable display table-hover'>";
			$html = "<table id='beaconsList' class='table table_sortable table_pick datatable display table-hover'>";
			//$html = "<table class='table table_sortable'>";
			$html .= "<thead><tr><th class='table-header'>Name</th>"
			."<th class='table-header'>Type</th>"
			."<th class='table-header'>Edit</th>"
			."<th class='table-header'>Delete</th>
					</tr></thead><tbody>";
			
			$eventObj = $this->getEventInfo($eventId);
			if(count($results) > 0) {
				foreach ($results as $beacon) {
					$beacon_type = 'Organizer';
					$table = $this->tables['Beacon'];
					$id = $beacon->getObjectId();
					$event_beacon_id = $eventObj->get("eventCode") . "/edit-beacon/" . $id;
					
					//$company = $this->getCompanyInfo($beacon->get("companyId")) ? $this->getCompanyInfo($beacon->get("companyId")) : NULL;
					if($beacon->get("companyId")){
						$beacon_type = 'Exhibitor';
					}
					//determing if beacon is session type
					$query1 = $this->parseQuery("Session");
					$query1->equalTo("beaconId", $id);
					$results1 = $query1->find();
					if(count($results1) > 0) {
						$beacon_type = 'Session';
					}
					//	if($company) {
					$id_table = $id . ':' . $table;
					$html .= "<tr class='deleteRec' data-id=$id_table>"
					."<td>". $beacon->get("name") ."</td>"
					."<td>". $beacon_type ."</td>"
					//."<td>". $company->get("name") ."</td>"
					//."<td><img src='". ($company->get("logo") ? $company->get("logo")->getURL() : '/assets/img/logo_placeholder.png') ."' width='50'>" ."</td>"
					."<td><a href='/Organizer/admin/edit/" . $event_beacon_id ."'><i class='fa fa-plus-square-o'></i>  Edit</a></td>"
					."<td><a class='deleteRec' href=''><i class='fa fa-trash'></i>  Delete</a></td>"
					."</tr>";
					/*	} else {
						$html .= "<tr>"
						."<td>". $beacon->get("name") ."</td>"
						."<td>NO Company</td>"
						."<td>NO Company</td>"
						."<td><a href='/Organizer/Admin/edit/$event/$id/$table'><i class='fa fa-plus-square-o'></i>  Edit</a></td>"
						."<td><a class='delete_chosen' data-info='$id' data-info2='$table' data-toggle='modal' data-target='#deleteModal' href=''><i class='fa fa-trash'></i>  Delete</a></td>"
						."</tr>";
						}*/
				}
				$tags_added = 1;
			} else {
				$html .= '<tr><td>No Beacons Added Yet</td></tr>';
			}
			$html .= "</tbody></table>";
			/*if ($tags_added) {
				$html .= "<form action='/Organizer/Admin/Edit/" . $_GET[action] ."' method = 'POST'><button name='add_beacon_details' id='addBeacon' href='javascript:;' type='submit' class='btn btn-custom btn-sm btn-block'>Add Beacon</button></form>";
			}*/
			return $html;
		}

		public function buildNotificationAtEvent($event) {
			$_SESSION['updateObjId'] = null;
			unset($_SESSION['updateObjId']);
			$eventId = $this->eventId;
			if (!eventId) {
				$eventId = $event;
			}
			$tags_added = 0;
			$allBeacons = $this->getAllBeaconsForEvent($eventId);
			
			$beacon_array = array();
			$query = $this->parseQuery("Notification");
			foreach ($allBeacons as $beacon) {	 
				array_push($beacon_array, $beacon->getObjectId());
			}
			
			$query->containedIn("beaconId", $beacon_array);
			$results = $query->find();
			//$html = "<table id='records-notifications' class='table table_sortable table_pick datatable display table-hover'>";
			$html = "<table id='triggeredContentList' class='table table_sortable table_pick datatable display table-hover'>";	
			$html .= "<thead><tr><th class='table-header'>Title</th>"
					."<th class='table-header'>Trigger Beacon</th>"
					."<th class='table-header'>Trigger Time(In Minutes)</th>"
					//."<th class='table-header'>Message</th>"
					."<th class='table-header'>PDF</th>"
					."<th class='table-header'>URL</th>"
					."<th class='table-header'>Edit</th>"
					."<th class='table-header'>Delete</th>
				</tr></thead><tbody>";

			$eventObj = $this->getEventInfo($eventId);
			if(count($results) > 0) {
				foreach ($results as $not) {
					$table = $this->tables['Notification'];
					$id = $not->getObjectId();
					$pdf = $not->get("pdfFile") ? $not->get("pdfFile") : "";
					$url = $not->get("url") ?  $not->get("url") : "";
					/*$pdf = $content->get("pdfFile") ? $content->get("pdfFile") : "";
					$url = $content->get("url") ? $content->get("url") : "";
					*/
						 if($pdf){
					    $pdf_td = "<a target='_blank' href='".($pdf ? $pdf->getURL() : '')."'> View</a>";
						
					}
					else{
						$pdf_td = '';
					}
				if($url){
					    $url_td = "<a target='_blank' href='".$url ."'> Visit</a>";
						
					}
					else{
						$url_td = '';
					}
					$timeInMins = round((($not->get("triggerTimeInSecs"))/60),2);
					
					//$message = (strlen($not->get("message")) < 50) ? $not->get("message") : substr(0, 50, $not->get("message") . '...');
					$event_not_id = $eventObj->get("eventCode") . "/edit-notification/" . $id;
					$id_table = $id . ':' . $table;
 					$html .= "<tr class='deleteRec' data-id=$id_table>"
							."<td>". $not->get("title") ."</td>"
							."<td>". $this->getBeaconName($not->get("beaconId")) ."</td>"
							."<td>". $timeInMins ."</td>"
							//."<td>". $message ."</td>"
							."<td>". $pdf_td ."</td>"
					        ."<td>".$url_td ."</td>"							
					        ."<td><a href='/Organizer/admin/edit/" . $event_not_id ."'><i class='fa fa-plus-square-o'></i>  Edit</a></td>"
							."<td><a class='deleteRec' href=''><i class='fa fa-trash'></i>  Delete</a></td>"	
							."</tr>";
				}
				$tags_added = 1;
			}  else {
				$html .= '<tr><td>No Notifications Added Yet</td></tr>';
			}
			$html .= "</tbody></table>";
			/*if ($tags_added) {
				$html .= "<form action='/Organizer/Admin/Edit/" . $_GET[action] ."' method = 'POST'><button name='add_not_details' id='addNot' href='javascript:;' type='submit' class='btn btn-custom btn-sm btn-block'>Add Notification</button></form>";
			}*/
			return $html;
		}
		/*TODO: Speaker wont load correctly */
		public function buildSurveyQuestionsAtEvent($event) {
			$_SESSION['updateObjId'] = null;
			unset($_SESSION['updateObjId']);
			$eventId = $this->eventId;
			if (!eventId) {
				$eventId = $event;
			}
			
			$query = $this->parseQuery("SurveyQuestion");
			$query->equalTo("eventId", $eventId);
			$results = $query->find();
			//$html = "<table id='records-survey' class='table table_sortable table_pick datatable display table-hover'>";
			$html = "<table id='surveysList' class='table table_sortable table_pick datatable display table-hover'>";
			$html .= "<thead><tr>"
					."<th class='table-header'>Questions</th>"
					."<th class='table-header'>Edit</th>"
					."<th class='table-header'>Delete</th>
					</tr></thead><tbody>";

			$eventObj = $this->getEventInfo($eventId);
			if(count($results) > 0) {
				foreach ($results as $surveyQ) {
					$table = $this->tables['SurveyQuestions'];
					//$questions->equalTo("surveyId", $survey->getObjectId());
					//$quest_survey = $questions->find();
					
					//get session name
					//$session->equalTo("objectId", $survey->get("sessionId"));
					//$session_details = $session->first();
					//$session_title = '';
					//if ($session_details) {
					    //$session_title = $session_details->get("title");
					//}
				
					$id = $surveyQ->getObjectId();
					$surveyQ_id = $eventObj->get("eventCode") . "/edit-survey/" . $id;
					$id_table = $id . ':' . $table;											
					$html .= "<tr class='deleteRec' data-id=$id_table>"
							."<td>". $surveyQ->get("question")."</td>"
							."<td><a href='/Organizer/admin/edit/" . $surveyQ_id ."'><i class='fa fa-plus-square-o'></i>  Edit</a></td>"
							."<td><a class='deleteRec' href=''><i class='fa fa-trash'></i>  Delete</a></td>"	
							."</tr>";
					}
				
			}  else {
				$html .= '<tr><td>No Survey Questions Added Yet</td></tr>';
			}
			$html .= '</tbody></table>';
			return $html;
		}
		public function buildSponsorsAtEvent($event) {
			$_SESSION['updateObjId'] = null;
			unset($_SESSION['updateObjId']);
			$eventId = $this->eventId;
			if (!eventId) {
				$eventId = $event;
			}
			$tags_added = 0;
			$query = $this->parseQuery("Sponsor");
			$query->equalTo("eventId", $eventId);
			$results = $query->find();
			//$html = "<table id='records-sponsors' class='table table_sortable table_pick datatable display table-hover'>";
			$html = "<table id='sponsorsList' class='table table_sortable table_pick datatable display table-hover'>";
			$html .= "<thead><tr><th class='table-header'>Name</th>"
			."<th class='table-header'>Level</th>"
			."<th class='table-header'>Website</th>"
			."<th class='table-header'>Edit</th>"
			."<th class='table-header'>Delete</th></tr></thead><tbody>";
			
			$eventObj = $this->getEventInfo($eventId);
			if(count($results) > 0) {
				foreach($results as $sponsor) {
					$id = $sponsor->getObjectId();
					$table = $this->tables['Sponsor'];
					$event_sponsor_id = $eventObj->get("eventCode") . "/edit-sponsor/" . $id;
					$id_table = $id . ':' . $table;
					$html .= "<tr class='deleteRec' data-id=$id_table><td>" . $sponsor->get("name") ."</td>"
					."<td>". $sponsor->get("level") ."</td>"
					."<td>". $sponsor->get("website") ."</td>"
					."<td><a href='/Organizer/admin/edit/" . $event_sponsor_id ."'><i class='fa fa-plus-square-o'></i>  Edit</a></td>"
					."<td><a class='deleteRec' href=''><i class='fa fa-trash'></i>  Delete</a></td>"
					. "</tr>";
					 
				}
				$tags_added = 1;
			} else {
				//we should not hit this, as add form will be displayed
				$html .= '<tr><td>No Sponsors Added Yet</td></tr>';
			}
			
			$html .= "</tbody></table>";
			/*if ($tags_added) {
				$html .= "<form action='/Organizer/Admin/Edit/" . $_GET[action] ."' method = 'POST'><button name='add_sponsor_details' id='addSponsor' href='javascript:;' type='submit' class='btn btn-custom btn-sm btn-block'>Add Sponsor</button></form>";
			}*/
			return $html;
		}
		public function buildContentAtEvent($event) {
			$_SESSION['updateObjId'] = null;
			unset($_SESSION['updateObjId']);
			$eventId = $this->eventId;
			if (!eventId) {
				$eventId = $event;
			}
			$tags_added = 0;
			$query = $this->parseQuery("Content");
			$query->equalTo("EventId", $eventId);
			$results = $query->find();
			//$html = "<table id='records-content' class='table table_sortable table_pick datatable display table-hover'>";
			$html = "<table id='contentsList' class='table table_sortable table_pick datatable display table-hover'>";
			$html .= "<thead><tr><th class='table-header'>Title</th>"
			."<th class='table-header'>PDF</th>"
			."<th class='table-header'>URL</th>"
			."<th class='table-header'>Edit</th>"
			."<th class='table-header'>Delete</th>
				</tr></thead><tbody>";
			$eventObj = $this->getEventInfo($eventId);
			if(count($results) > 0) {
				foreach ($results as $content) {
					$table = $this->tables['Content'];
					$id = $content->getObjectId();
					$pdf = $content->get("pdfFile") ? $content->get("pdfFile") : "";
					$url = $content->get("url") ? $content->get("url") : "";
					 if($pdf){
					    $pdf_td = "<a target='_blank' href=".($pdf ? $pdf->getURL() : '').">View</a>";
						
					}
					else{
						$pdf_td = '';
					}
				if($url){
					    $url_td = "<a target='_blank' href=".$url .">Visit</a>";
						
					}
					else{
						$url_td = '';
					}
			
					$event_content_id = $eventObj->get("eventCode") . "/edit-content/" . $id;
					$id_table = $id . ':' . $table;
					$html .= "<tr class='deleteRec' data-id=$id_table>"
					."<td>". $content->get("title") ."</td>"
					."<td>". $pdf_td ."</td>"
					."<td>".$url_td ."</td>"
					."<td><a href='/Organizer/admin/edit/" . $event_content_id ."'><i class='fa fa-plus-square-o'></i>  Edit</a></td>"
					."<td><a class='deleteRec' href=''><i class='fa fa-trash'></i>  Delete</a></td>"
					."</tr>";
				}
				$tags_added = 1;
			}  else {
				$html .= '<tr><td>No Contents Added Yet</td></tr>';
			}
			
			$html .= "</tbody></table>";
			/*if ($tags_added) {
				$html .= "<form action='/Organizer/Admin/Edit/" . $_GET[action] ."' method = 'POST'><button name='add_content_details' id='addContent' href='javascript:;' type='submit' class='btn btn-custom btn-sm btn-block'>Add Content</button></form>";
			}*/
			return $html;
		}
		/* END Event_build */

		/* Edit_build */
		private function buildEditFillEvent($id) {
			$query = $this->parseQuery("Event");
			$query->equalTo("objectId", $id);
			$eventInfo = $query->first();
			$name = $eventInfo->get("name");
			$logo = $eventInfo->get('logo') ? $eventInfo->get("logo")->getURL() : "/assets/img/logo_placeholder.png";
			$startDate = $eventInfo->get("startDate") ? $eventInfo->get("startDate")->format("Y-m-d H:i:s") : "";
			$endDate = $eventInfo->get("endDate") ? $eventInfo->get("endDate")->format("Y-m-d H:i:s") : "";
			$address = $eventInfo->get("address");
			$city = $eventInfo->get("city");
			$location = $eventInfo->get("location");
			$triggerBeacons = $eventInfo->get("triggerBeacons");
			$tb_array = array();
			foreach ($triggerBeacons as $beacon) {
				array_push($tb_array, $this->getBeaconName($beacon));
			}
			$triggerBeacons = implode(" ", $tb_array);
			$twitter_array = array();
			$twTags = $eventInfo->get("twTags");
			foreach ($twTags as $tw) {
				array_push($twitter_array, "#".$tw);
			}
			$twTags = implode(" ", $twitter_array);
			$eventCode = strval($eventInfo->get("code"));
			$organizerCode = $eventInfo->get("organizerCode");
			$div = '<div class="form-group col-md-8">';
			$endD = '</div>';
			$html =	$div."<label for='event_name'>Name:</label><input name='event_name' type='text' value='$name' class='form-control input-md'>". $endD
				   .$div."<label for='event_logo'>Logo:</label><input class='logo_ex form-control input-md' type='file' name='event_logo' value='$logo'>".$endD
				   .$div."<label for='event_startDate'>Start Date: </label><input class='form-control input-md date_pick' type='text' name='event_startDate' value='$startDate'>".$endD
				   .$div."<label for='event_endDate'>End Date: </label><input class='form-control input-md date_pick' type='text' name='event_endDate' value='$endDate'>".$endD
				   .$div."<label for='event_address'>Address: </label><input class='form-control input-md' type='text' name='event_address' value='$address'>".$endD
				   .$div."<label for='event_city'>City: </label><input class='form-control input-md' type='text' name='event_city' value='$city'>".$endD
				   .$div."<label for='event_location'>Location: </label><input class='form-control input-md' type='text' name='event_location' value='$location'>".$endD
				   .$div."<label for='event_triggerBeacons'>Trigger Beacons: </label><input class='form-control input-md' type='text' name='event_triggerBeacons' value='$triggerBeacons'>".$endD
				   .$div."<label for='event_twitterTags'>Twitter Tag: </label><input class='form-control input-md' type='text' name='event_twitterTags' value='$twTags'>".$endD
				   .$div."<label for='event_code'>Code: </label><input class='form-control input-md' type='text' name='event_code' value='$eventCode'>".$endD
				   .$div."<label for='event_orgCode'>Organizer Code: </label><input class='form-control input-md' type='text' name='event_orgCode' value='$organizerCode'>".$endD;					
			return $html;	
		}
		private function buildEditFillSpeaker($id) {
			$query = $this->parseQuery("Speaker");
			$query->equalTo("objectId", $id);
			$results = $query->first();
			$fname = $results->get("firstName");
			$lname = $results->get("lastName");
			$company = $results->get("company");
			$bio = $results->get("about");
			$linkedIn = $results->get("linkedInURL");
			$twitter = $results->get("twitterURL");
			$image = $results->get("avatar") ? $results->get("avatar")->getURL() : "";
			$div = '<div class="form-group col-md-8">';
			$endD = '</div>';
			$html =	$div."<label for='speaker_firstName'>First Name:</label><input name='speaker_firstName' type='text' value='$fname' class='form-control input-md'>". $endD
				   .$div."<label for='speaker_lastName'>Last Name:</label><input class='form-control input-md' type='text' name='speaker_lastName' value='$lname'>".$endD
				   .$div."<label for='speaker_company'>Company: </label><input class='form-control input-md' type='text' name='speaker_company' value='$company'>".$endD
				   .$div."<label for='speaker_linkedIn'>LinkedIn URL: </label><input class='form-control input-md' type='text' name='speaker_linkedIn' value='$linkedIn'>".$endD
				   .$div."<label for='speaker_twitter'>Twitter URL: </label><input class='form-control input-md' type='text' name='speaker_twitter' value='$twitter'>".$endD
				   .$div."<label for='speaker_image'>Profile Picture: </label><input class='form-control input-md logo_ex' type='file' name='speaker_image' value='$image'>".$endD
				   .$div."<label for='speaker_details'>Bio: </label><textarea rows='10' class='form-control input-md' name='speaker_details'>$bio</textarea>".$endD;
			$speaker_questions = $this->getSpeakerQuestions($results->getObjectId());
			$i = 1;
			foreach($speaker_questions as $questions) {
				$html.= $div."<label for='speaker_question$i'>Question $i:</label><input name='speaker_question$i' type='text' value='".$questions->get("question")."' class='form-control input-md'>". $endD;
				$i++;	
			}
			$html .= "<input type='hidden' name='question_amount' value='$i'>";
			return $html;	
		}
		private function buildEditFillSession($id) {
			$query = $this->parseQuery("Session");
			$query->equalTo("objectId", $id);
			$results = $query->first();
			$title = $results->get("title");
			$roomNumber = $results->get("roomName");
			$startTime = $results->get("startTime")->format("Y-m-d H:i:s");
			$endTime = $results->get('endTime')->format("Y-m-d H:i:s");
			$details = $results->get("details");
			$div = '<div class="form-group col-md-8">';
			$endD = '</div>';
			$html =	$div."<label for='update_title'>Title:</label><input name='update_title' type='text' value='$title' class='form-control input-md'>". $endD
				   .$div."<label for='update_room'>Room Name:</label><input class='form-control input-md' type='text' name='update_room' value='$roomNumber'>".$endD
				   .$div."<label for='update_speaker'>Speaker: </label><select data-live-search='true' class='form-control input-md selectpicker' name='update_speaker'>". $this->buildSpeakerSelection($results->get("speakerId")). "</select>".$endD
				   .$div."<label for='update_startTime'>Start Date: </label><input class='form-control input-md date_pick' type='text' name='update_startTime' value='$startTime'>".$endD
				   .$div."<label for='update_endTime'>End Date: </label><input class='form-control input-md date_pick' type='text' name='update_endTime' value='$endTime'>".$endD
				   .$div."<label for='update_details'>Details: </label><textarea rows='10' class='form-control input-md' name='update_details'>$details</textarea>".$endD;
			return $html;	
		}
		private function buildEditFillExhibitor($id) {
			$query = $this->parseQuery("Exhibitor");
			$query->equalTo("objectId", $id);
			$results = $query->first();
			$name = $results->get("name");
			$desc = $results->get("website");
			$logo = $results->get("logo") ? $results->get("logo")->getURL() : ""; 
			$div = '<div class="form-group col-md-8">';
			$endD = '</div>';
			$html =	$div."<label for='exhibitor_name'>Name: </label><input name='exhibitor_name' type='text' value='$name' class='form-control input-md'>". $endD
				   .$div."<label for='exhibitor_summary'>Website: </label><input class='form-control input-md' name='exhibitor_summary' value='$desc'>".$endD
				   .$div."<label for='exhibitor_logo'>Logo: </label><input class='form-control input-md logo_ex' type='file' name='exhibitor_logo' value='$logo'>".$endD;
			return $html;	
		}
		private function buildEditFillBeacon($id) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("objectId", $id);
			$results = $query->first();
			$name = $results->get("name");
			$company = $this->getCompanyName($results->get("companyId"));
			$div = '<div class="form-group col-md-8">';
			$endD = '</div>';
			$html =	$div."<label for='beacon_name'>Name: </label><input name='beacon_name' type='text' value='$name' class='form-control input-md'>". $endD
				.$div."<label for='beacon_company'>Company: </label><input name='beacon_company' type='text' value='$company' class='form-control input-md'>". $endD;
			return $html;	
		}
		private function buildEditFillNotification($id) {
			$query = $this->parseQuery("Notification");
			$query->equalTo("objectId", $id);
			$results = $query->first();
			$title = $results->get("title");
			$message = $results->get("message");
			$beacon = $results->get("beaconId");
			$triggerTime = $results->get("triggerTimeInSecs");
			$url = $results->get("url");
			$pdf = $results->get("pdfFile") ? $results->get("pdfFile")->getURL() : "";
			$div = '<div class="form-group col-md-8">';
			$endD = '</div>';
			$html =	$div."<label for='not_title'>Name: </label><input name='not_title' type='text' value='$title' class='form-control input-md'>". $endD
				   .$div."<label for='not_message'>Message: </label><textarea rows='5' class='form-control input-md' name='not_message'>$message</textarea>".$endD
				   .$div."<label for='not_pdf'>PDF: </label><input class='form-control input-md logo_ex' type='file' name='not_pdf' value='$pdf'>".$endD
				   .$div."<label for='not_url'>URL: </label><input class='form-control input-md' type='text' name='not_url' value='$url'>".$endD
				   .$div."<label for='not_time'>Trigger When: </label><select data-live-search='true' class='form-control input-md selectpicker' name='not_time'>". $this->buildNotificationSelect(). "</select>".$endD
				   .$div."<label for='not_beacon'>Beacon: </label><select data-live-search='true' class='form-control input-md selectpicker' name='not_beacon'>". $this->buildBeaconDropDown($this->event, $beacon). "</select>".$endD;
			return $html;	
		}
		private function buildEditFillSurvey($id) {
			$query = $this->parseQuery("Survey");
			$questions = $this->parseQuery("SurveyQuestion");
			$query->equalTo("objectId", $id);
			$results = $query->first();
			$questions->equalTo("surveyId", $id);
			$questions->ascending("position");
			$quest = $questions->find();
			$html .= "<div class='form-group col-md-12'><label for='question_position'>Question Position: </label>";
			$html .= '<ul name="question_position" id="sortable">';
			foreach ($quest as $q) {
				$html .= '<input class="hidden_position" id="position'.$q->get("position").'" type="hidden" name="position'.$q->get("position").'" value="'.$q->get("question").'">';
				$html .= '<li id="question_position_'.$q->get("position").'" name="question_position'.$q->get("position").'" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'. $q->get("position") . '.) ' .$q->get("question").'</li>';
			} 
			$html .= '</ul><input type="hidden" name="number_of_ques" value='. count($quest) .'></div>';
			$endD = '</div>';
			$div .= "<div class='form-group col-md-12'>";
			foreach($quest as $q) {	
				$html .= $div . "<label for='survey_question".$q->get("position")."'>Question ".$q->get("position")." : </label><input class='form-control input-md' type='text' name='survey_question".$q->get("position")."' value='". $q->get("question") ."'> ".$endD;
				$html .= $div. "<button class='btn btn-danger delete_ques' name='delete_node".$q->get("position")."' type='submit'>Delete Question</button>".$endD;
			}
			$html .= $div . '<label for="survey_title">Title: </label><input class="form-control input-md" type="input" name="survey_title" value="'.$results->get('title').'">'.$endD
					.$div . '<label for="survey_start">Start Time: </label><input class="form-control input-md date_pick" type="text" name="survey_start" value="'.$results->get('startDate')->format("Y-m-d H:i:s").'">'.$endD
					.$div . '<label for="survey_session">Session: </label><select data-live-search="true" class="form-control input-md selectpicker" name="survey_session">'. $this->buildSessionSelection($results->get("sessionId")) .'</select>'.$endD
					.$div . '<label for="survey_speaker">Speaker: </label><select data-live-search="true" class="form-control input-md selectpicker" name="survey_speaker">'. $this->buildSpeakerSelection($results->get("speakerId")) .'</select>'.$endD;
			return $html;
		}
		public function buildTimezoneSelection($selected = null) {
		 static $regions = array(
        DateTimeZone::AFRICA,
        DateTimeZone::AMERICA,
        DateTimeZone::ANTARCTICA,
        DateTimeZone::ASIA,
        DateTimeZone::ATLANTIC,
        DateTimeZone::AUSTRALIA,
        DateTimeZone::EUROPE,
        DateTimeZone::INDIAN,
        DateTimeZone::PACIFIC,
    );

    $timezones = array();
    foreach( $regions as $region )
    {
        $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
    }
  //echo var_dump($timezones);
    $timezone_offsets = array();
    foreach( $timezones as $timezone )
    {
        $tz = new DateTimeZone($timezone);
        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
    }

    // sort timezone by offset
    asort($timezone_offsets);

    $timezone_list = array();
    foreach( $timezone_offsets as $timezone => $offset )
    {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_formatted = gmdate( 'H:i', abs($offset) );

        $pretty_offset = "UTC${offset_prefix}${offset_formatted}";

        //echo var_dump($timezone);
        //No need offset as of now
        //$timezone_list[$timezone] = "(${pretty_offset}) $timezone";
        $timezone_list[$timezone] = $timezone;
        asort($timezone_list);
    } 
    foreach($timezone_list as $key => $value) {
    		$select = $selected == $value ? "selected>" : ">";
    		echo var_dump($value);
		    $html .= '<option value="' .$value.'"' . $select . $value . '</option>';
    	
        //    $html .= '<option value=' . $value . '>' . $value. '</option>';
			
    }return $html;
}
   
    public function displayAddedSpeakers($eventId = null, $eventCode = null) {
    	//echo var_dump($eventId);
    	//echo var_dump($eventCode);
        if ($eventId) {
        	$query = $this->parseQuery("Speaker");
        	$query->select(["firstName", "lastName","company","title","twitterURL","linkedInURL"]);
        	$query->equalTo("eventId", $eventId);
        	
        	$results = $query->find();
        	
        	if (!results) {
        		return;
        	}
        	//echo var_dump(count($results));
        	$html = '';
        //	$html .= "<label for='speaker_added'>Speakers List</label>";
        	$html .= "<br>";
        	//$html .= "<label for='speaker_added'>Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Company&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Twitter&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LinkedIn<br>";
        //	$html .="<table><tr><th>&nbsp;&nbsp;&nbsp;&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;&nbsp;Company&nbsp;&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;&nbsp;Title&nbsp;&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;&nbsp;TwitterURL&nbsp;&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;&nbsp;LinkedInURL</th></tr>";
        	foreach ($results as $res) {
        		$name = ($res->get("firstName")?$res->get("firstName"):'');
        		$company = ($res->get("company"));
        		$title = ($res->get("title"));
        		$twitter = ($res->get("twitterURL"));
        		$linkedIn = ($res->get("linkedInURL"));
        		if ($res->get("lastName")) {
        			$name .= ',' .$res->get("lastName");
        		}
        		  $html .=  "<tr><td>".$name."&nbsp;&nbsp;&nbsp;&nbsp;</td>" ;
        		  $html .= "<td>".$company."&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        		  $html .= "<td>".$title."&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        		    if ($twitter !== '')
        		  $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;<a href=". $twitter ."><i class='fa fa-twitter'> </i></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
					if ($linkedIn !== '')
        		  $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;<a href=". $linkedIn ."><i class='fa fa-linkedin'> </i></a>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr><br>";
        	//    $html .= "<a href='/Organizer/Admin/EditSpecific/speaker_". $res->getObjectId() ."'><i class='fa fa-edit'> </i>" . $name . "</a>";
        	//    $html .= "&nbsp;&nbsp;&nbsp;&nbsp;";    	
        	}
        }
        return $html;	
    }
    public function getTwitterAccount($twitter){
    		
    	$format_tw_url = str_replace(  "https://twitter.com/", ' ' , $twitter);
    	$good_tw_url = ltrim($format_tw_url, ' ');
    	$good_tw_url = "@" . $good_tw_url ;
    	return $good_tw_url;
    		
    		
    }
       /*
        * $selected is an array of speakers
        */
		public function buildSpeakerSelection($selected = null) {
			$query = $this->parseQuery("Speaker");
			$query->select(["firstName", "lastName"]);
			/*
			 * TODO Need to check why this->eventId is not being set
			 */
			//echo var_dump($this->eventId);
			//$query->equalTo("eventId", $this->eventId);
			$query->equalTo("eventId", $_SESSION['newEventId']);
			
			$results = $query->find();
		//	foreach ($selected as $sel) {
		$html .= '<option disabled selected>Choose multiple speakers</option>';
			foreach ($results as $res) {
				$select = ">";
				if ($selected) {
					 
					foreach ($selected as $sp) {
						if ($sp == $res->getObjectId()) {
							$select = "selected>";
							break;
						}
					}
				}
					//$select = $selected == $res->getObjectId() ? "selected>" : ">";
				    $html .= '<option value="' . $res->getObjectId().'"' . $select . $res->get("firstName") . ' ' . $res->get("lastName") . '</option>';
			
				
		/*	//	$add = ($selected ==($res->getObjectId() )? "selected">': '>';
				$add = ($selected == $res->getObjectId()) ? 'selected="selected">' : '>';
				
				//$add = (selected == '"selected">');
				//$html .= '<option value="'.$res->getObjectId().'".$add. $res->get("firstName") . ' ' . $res->get("lastName") . ' </option>';
                $html .= '<option value="'.$res->getObjectId().'" '. $add. $res->get("firstName") . ' ' . $res->get("lastName") . '</option>';
			*/	
			}
			return $html;
		} 
		public function buildBeaconSelection($event, $option= null) {
			$html = '';
			if($option == 'Exhibitor') {
				$event_companies = $this->getCompaniesAtEvent($event);
				$html .= '<option disabled selected>Choose an Exhibitor</option>';
				foreach ($event_companies as $ec) {
					// $html .= '<option value="' . $ec->get("companyId") . '">' . $this->getCompanyInfo($ec->get("companyId"))->get("name") . '</option>';
					$select = $selected == $ec->get("companyId") ? "selected>" : ">";
				    $html .= '<option value="' . $ec->get("companyId") .'"' . $select . $this->getCompanyInfo($ec->get("companyId"))->get("name") . '</option>';
				
				}
			} else if($option == 'Session') {
				$session_option = $this->getSessionsAtEvent($event);
				$html .= '<option disabled selected>Choose a Session</option>';
				foreach($session_option as $sess) {
				//	$html .= '<option value=' . $sess->getObjectId() . '>' . $sess->get("title") . '</option>';
					$select = $selected == $sess->getObjectId() ? "selected>" : ">";
				    $html .= '<option value="' . $sess->getObjectId() .'"' . $select . $sess->get("title") . '</option>';
				
				}
			} else {
				$html .= '<option>Sorry something went wrong. Try Again Later.</option>';
			}
			return $html;
		}
		public function buildBeaconDropDown($event, $selected = null) {
			$beacons = $this->getAllBeaconsForEvent($event);
			foreach($beacons as $ba) {
				$select = $selected == $ba->getObjectId() ? "selected>" : ">";
				$html .= '<option value="' . $ba->getObjectId().'"' . $select . $ba->get("name") . '</option>';
			}
			return $html;
		}
		public function getBeaconTypeByBeacon($beacon){
			$beacon_type = 'Organizer';
			//$company = $this->getExhibitor($beacon->get("companyId")) ? $adminAPI->getCompanyInfo($beacon->get("companyId")) : NULL;
			if($beacon->get("companyId")){
				$beacon_type = 'Exhibitor';
			}
			
			//determing if beacon is session type
			$query1 = $this->parseQuery("Session");
			$query1->equalTo("beaconId", $beacon->getObjectId());
			$results1 = $query1->find();
			if(count($results1) > 0) {
				$beacon_type = 'Session';
				
			}
			return $beacon_type;
		}
		/**
		  * @param $dynamic_array    Parse's Array of Notification Times in Seconds
		  * @param $static_time 	 Default Number from Default Array of Times in minutes 
		  * @return Boolean 		 True if the static_time is available and False otherwise
		  */
		private function checkNotificationTime($dynamic_array, $static_time) {
			$available = true;
			$loop_condition = true;
			$i = 0;
			while($loop_condition) {
				if($dynamic_array[$i] != NULL) {
					if($dynamic_array[$i]/60 < $static_time) {
						$i++;
					} else if(($dynamic_array[$i]/60) == $static_time || ($dynamic_array[$i]/60) < $static_time+5) {
						$available = false;
						$loop_condition = false;
					}
				} else {
					$loop_condition = false;
				}
			}
			return $available;
		}
		/**
		  * @return HTML options for Select Field with available times for the Notification
		  */
		public function buildNotificationSelect($eventId, $selected=null) {
			$specific_times = array(5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 20, 25, 30, 45, 60, 75, 90);
			$allBeacons = $this->getAllBeaconsForEvent($eventId);
			$notifications_check = $this->parseQuery("Notification");
			$notifications_check->containedIn("beaconId", $allBeacons);
			$notifications_check->ascending('triggerTimeInSecs');
			$event_nots = $notifications_check->find();	
			$html = '';
			$times_array = array();
			$i = 0;
			foreach ($event_nots as $not) {
				array_push($times_array, $not->get("triggerTimeInSecs"));
			}
			foreach($specific_times as $time) {	
				if($this->checkNotificationTime($times_array, $time)) {
					 $select = $selected == $time ? "selected>" : ">";
						$html .= '<option value="' . $time*60 . '"' . $select . $time . '</option>';																																																																																																	
					 				
				} 
			}
			return $html;
		}
		public function buildSessionSelection($selected = null) {
			//$eventId = $this->eventId;
			echo var_dump("Krsna");
			$eventId = $_SESSION['newEventId'];
			$query = $this->parseQuery("Session");
			$query->equalTo("eventId", $eventId);
			$results = $query->find();
			$html = '';
			foreach($results as $session) {
				$select = $selected == $session->get("beaconId") ? "selected>" : ">";
				echo var_dump($select);
				$html .= '<option value="' .$session->get("beaconId").'"' . $select . $session->get("title") . '</option>';
				
			}
			return $html;
		}
		public function buildExhibitorSelection($selected = null) {
			//$eventId = $this->eventId;
			$eventId = $_SESSION['newEventId'];
		
			$query = $this->parseQuery("Exhibitor");
			$query->equalTo("eventId", $eventId);
			$results = $query->find();
			foreach($results as $ca) {

				$select = $selected == $ca->getObjectId() ? "selected>" : ">";
				$html .= '<option value="' .$ca->getObjectId().'"' . $select . $ca->get("name") . '</option>';
			}
			return $html;
		}
		
		/* Submission Functions */
		public function submitNewEvent($exist_obj_id = NULL,$admin, $title, $timezone, $event_code, $logo, $location, $address, $address2, $city, $state, $zip, $country, $startTime, $endTime, $hash) {
			//echo var_dump($title);
			unset($_SESSION['newEventId']);
			$user_info = $this->getUserInfo($admin);
			$companyId = $this->getCompanyId($user_info->get("company"));
			
			$dateStart = $this->setTimeZoneForPersist($startTime, $timezone[0]);
			$dateEnd = $this->setTimeZoneForPersist($endTime, $timezone[0]);
			
		    //$dateStart = new DateTime($startTime, new DateTimeZone($timezone[0]));
			//$dateStart->setTimezone(new DateTimeZone('UTC'));
			
			//$dateEnd = new DateTime($endTime, new DateTimeZone($timezone[0]));
			//$dateEnd->setTimezone(new DateTimeZone('UTC'));
			
			if($logo['name'] != null) {
				$file = ParseFile::createFromData(file_get_contents($logo['tmp_name']), $logo['name']);
			}
			if ($exist_obj_id) {
				$event_submit = $this->getEventInfoForEdit($exist_obj_id);
			} else {
			    $event_submit = new ParseObject("Event");
			}
		//	$event_submit = new ParseObject("Event");
		    $event_submit->set("name", $title);
		    $event_submit->set("timeZone", $timezone[0]);
		    $event_submit->set("eventCode", $event_code);
		    $event_submit->set("location", $location);
		    $event_submit->set("address", $address);
		    $event_submit->set("address2", $address2);
		    $event_submit->set("city", $city);
		    $event_submit->set("state", $state);
		    $event_submit->set("zipCode", $zip);
		    $event_submit->set("startDate", $dateStart);
		    $event_submit->set("endDate", $dateEnd);
		    $event_submit->setArray("organizers", array($admin));
		    $hashTagArray = $this->getTwitterHash($hash);
		    $event_submit->setArray("twTags", $hashTagArray);
		    try {
		    	if($logo['name'] != null) {
			    	$file->save();
			    
			    	$event_submit->set("logo", $file);
			    }	
			    
			    $event_submit->save();
			    $_SESSION['newTabObjId'] = $event_submit->getObjectId();
		    } catch (ParseException $ex) {
		    	echo $ex->getMessage() . ' ' . $ex->getCode();
		    }
		    return $event_submit;
		}

		public function submitNewSpeaker($exist_obj_id = NULL, $event, $name, $company, $title, $bio, $linkedin, $twitter, $image) {
			//$event_info = $this->getEventInfo($event);
			
			if ($exist_obj_id) {
				$speaker_submit = $this->getSpeakerInfo($exist_obj_id);
			} else {
			    $speaker_submit = new ParseObject("Speaker");
			}
			$name_array = $this->splitName($name);
			//TODO, delete after fixing image load on update
				
			if($image['name'] != null) {
				$file = ParseFile::createFromData(file_get_contents($image['tmp_name']), $image['name']);
			}

			if ($twitter) {
			    $twitter_url = $this->createTwitterUrl($twitter);
			}
			
			$speaker_submit->set("firstName", $name_array[0]);
			$speaker_submit->set("lastName", $name_array[2]);
			$speaker_submit->set("company", $company);
			$speaker_submit->set("title", $title);
			$speaker_submit->set("about", $bio);
			$speaker_submit->set("linkedInURL", $linkedin);
			$speaker_submit->set("twitterURL", $twitter_url);
			$speaker_submit->set("eventId", $event);
			try {

				if($image['name'] != null) {
					$file->save();
					$speaker_submit->set("avatar", $file);
				}
					
				$speaker_submit->save();
				$_SESSION['newTabObjId'] = $speaker_submit->getObjectId();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}
		
		public function submitNewSponsor($exist_obj_id = NULL,$eventId, $name, $profile, $level,$website,$logo) {
			//$event_info = $this->getEventInfo($event);
		if ($exist_obj_id) {
				$sponsor_submit = $this->getSponsorInfo($exist_obj_id);
			} else {
			    $sponsor_submit = new ParseObject("Sponsor");
			}
			//$sponsor_submit = new ParseObject("Sponsor");
			$sponsor_submit->set("name", $name);
			$sponsor_submit->set("description", $profile);
			$sponsor_submit->set("level", $level);
			$sponsor_submit->set("website", $website);
			$sponsor_submit->set("eventId", $eventId);
			if($logo['name'] != null) {
				$file = ParseFile::createFromData(file_get_contents($logo['tmp_name']), $logo['name']);
			}
			try {
				if($logo['name'] != null) {
					$file->save();
					$sponsor_submit->set("logo", $file);
				}
				$sponsor_submit->save();
				$_SESSION['newTabObjId'] = $sponsor_submit->getObjectId();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}

		public function submitNewSession($exist_obj_id = NULL,$event, $title, $desc, $startTime, $endTime, $speaker) {
			//$eventId = $this->eventId;
		if ($exist_obj_id) {
				$session_submit = $this->getSessionInfo($exist_obj_id);
			} else {
			    $session_submit = new ParseObject("Session");
			}
			
			if ($event) {
				$event_obj = $this->getEventInfoById($event);
			}
			if ($event_obj->get("timeZone")) {
				$dateStart = $this->setTimeZoneForPersist($startTime, $event_obj->get("timeZone"));
			    $dateEnd = $this->setTimeZoneForPersist($endTime, $event_obj->get("timeZone"));
			}
			//$dateStart = $this->chooseCorrectTime($startTime);
			//$dateEnd = $this->chooseCorrectTime($endTime);
			$session_submit->set("title", $title);
			$session_submit->set("details", $desc);
			$session_submit->set("startTime", $dateStart);
			$session_submit->set("endTime", $dateEnd);
			//$session_submit->set("roomName", $room);
			if(!empty($speaker))
				$session_submit->setArray("speakerIds", $speaker);
			    $session_submit->set("eventId", $event);
			//echo var_dump($session_submit);
			
			//echo var_dump($speaker);
			
			try {
				$session_submit->save();
				$_SESSION['newTabObjId'] = $session_submit->getObjectId();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}

		public function submitNewExhibitor($exist_obj_id = NULL,$event, $name, $desc, $booth, $website, $logo) {
		if ($exist_obj_id) {
				$exhibitor_actual = $this->getExhibitorInfo($exist_obj_id);
				//echo "Inside if block of submit new exhibitor";
				//echo var_dump($exist_obj_id);
			} else {
			    $exhibitor_actual = new ParseObject("Exhibitor");
			    $exhibitor_submit = new ParseObject("Company");
			    $role_submit = new ParseObject("EventCompany");
			$exArray = array("exhibitor");    
			$exhibitor_submit->set("name", $name);
			$exhibitor_submit->set("description", $desc);
			$role_submit->setArray("companyRole", $exArray);
			$role_submit->set("eventId", $event);
			
			try{
			if($logo['name'] != null) {
				$file = ParseFile::createFromData(file_get_contents($logo['tmp_name']), $logo['name']);
			}
			if($logo['name'] != null) {
					$exhibitor_submit->set("logo", $file);
					$file->save();
				}
				$exhibitor_submit->save();
				$role_submit->set("companyId", $exhibitor_submit->getObjectId());
				$role_submit->save();
			
			}catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
			
			}
			//$eventId = $this->eventId;
			//$exArray = array("exhibitor");
			
			//$exhibitor_actual = new ParseObject("Exhibitor");
			if($logo['name'] != null) {
				$file = ParseFile::createFromData(file_get_contents($logo['tmp_name']), $logo['name']);
			}
			$exhibitor_actual->set("eventId", $event);
			$exhibitor_actual->set("booth", $booth);
			$exhibitor_actual->set("website", $website);
			$exhibitor_actual->set("name", $name);
			$exhibitor_actual->set("description", $desc);
			
			
			try {
				if($logo['name'] != null) {
					$file->save();
					$exhibitor_actual->set("logo", $file);
					//$exhibitor_submit->set("logo", $file);
				}
				$exhibitor_actual->save();
				$_SESSION['newTabObjId'] = $exhibitor_actual->getObjectId();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}

		public function submitNewBeacon($exist_obj_id = NULL,$event, $name, $beacon_type, $beacon_for) {
			//$eventId = $this->eventId;
			if ($exist_obj_id) {
				$beacon = $this->getBeaconInfo($exist_obj_id);
			} else {
			    $beacon = new ParseObject("Beacon");
			}
		
			$availableBeacon = $this->getAvailableBeacons();
			//echo var_dump($availableBeacon);
			$beacon->set("name", $name);
			$beacon->set("eventId", $event);
			/*
			 * get available beacon and copy major, minor, uuid
			 */
						
			$beacon->set("major", $availableBeacon->get["major"]);
			$beacon->set("minor", $availableBeacon->get["minor"]);
			$beacon->set("uuid", $availableBeacon->get["uuid"]);
			$beacon->set("IsAvailable",$availableBeacon->get["IsAvailable"] );
			//echo var_dump($beacon);
			/*if($beacon_type == 'Trigger') {
				$event = $this->parseQuery("Event");
				$event->equalTo("objectId", $event);
				$ev_result = $event->first();
				try {
					$beacon->save();
					$ev_result->add("triggerBeacons", [$beacon->getObjectId()]);
					$ev_result->save();
				} catch (ParseException $ex) {
					echo $ex->getMessage() . ' ' . $ex->getCode();
				}
			} else
			*/

			if($beacon_type == 'Organizer') {
				try {
					$beacon->save();
					$availableBeacon->set("IsAvailable", false);
					//$availableBeacon->setArray("IsAvailable",false);
					$availableBeacon->save();
				} catch (ParseException $ex) {
					echo $ex->getMessage() . ' ' . $ex->getCode();
				}
			} else if($beacon_type == 'Exhibitor') {
				echo var_dump($beacon_for);
				//echo var_dump($event);
				
				$beacon->set("companyId", $beacon_for[0]);
				try {
					$beacon->save();
					$availableBeacon->set("IsAvailable", false);
					$availableBeacon->save();
				} catch (ParseException $ex) {
					echo $ex->getMessage() . ' ' . $ex->getCode();
				}
			} else if($beacon_type == 'Session') {
				echo var_dump($beacon_for);
				
				$session_query = $this->parseQuery("Session");
				$session_query->equalTo("objectId", $beacon_for[0]);
				$session = $session_query->first();
				try {
					$beacon->save();
					$availableBeacon->set("IsAvailable", false);
		          	$availableBeacon->save();
					$session->set("beaconId", $beacon->getObjectId());
					$session->save();
				} catch (ParseException $ex) {
					echo $ex->getMessage() . ' ' . $ex->getCode();
				}
			}
			$_SESSION['newTabObjId'] = $beacon->getObjectId();
		}

		public function submitNewNotification($exist_obj_id = null,$event, $title, $message, $PDF, $time, $url, $beacon) {
			//$eventId = $this->eventId;
			if ($exist_obj_id) {
				$not_submit = $this->getNotificationInfo($exist_obj_id);
			} else {
			    $not_submit = new ParseObject("Notification");
			}
		
		
			//$not_submit = new ParseObject("Notification");
			
			$not_submit->set("beaconId", $beacon);
			$not_submit->set("title", $title);
			$not_submit->set("message", $message);
			$not_submit->set("triggerTimeInSecs", intval($time));
			$not_submit->set("url", $url);
		if($PDF['name'] != null) {
				$file = ParseFile::createFromData(file_get_contents($PDF['tmp_name']), $PDF['name']);
			}
			try {
				if($PDF['name'] != null) {
					$file->save();
					$not_submit->set("pdfFile", $file);
				}
				$not_submit->save();
				$_SESSION['newTabObjId'] = $not_submit->getObjectId();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}
		
		public function submitNewContent($exist_obj_id = null,$eventId, $title, $description, $PDF, $url) {
			//$eventId = $this->eventId;
			if ($exist_obj_id) {
				$content_submit = $this->getContentInfo($exist_obj_id);
			} else {
			    $content_submit = new ParseObject("Content");
			}
		
			//$content_submit = new ParseObject("Content");
			if($PDF['name'] != null) {
				$file = ParseFile::createFromData(file_get_contents($PDF['tmp_name']), $PDF['name']);
			}
			$content_submit->set("title", $title);
			$content_submit->set("description", $description);
			$content_submit->set("url", $url);
			$content_submit->set("EventId", $eventId);
			try {
				if($PDF['name'] != null) {
					$file->save();
					$content_submit->set("pdfFile", $file);
				}
				$content_submit->save();
				$_SESSION['newTabObjId'] = $content_submit->getObjectId();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}
		
		/*private function submitQuestionsSurvey($exist_obj_id = null, $questions, $survey, $speaker) {
		if ($exist_obj_id) {
				//$content_submit = $this->getContentInfo($exist_obj_id);
				for ($i=0; $i < count($questions); $i++) { 
				$q = $this->getSurveyQuestionInfo($exist_obj_id);
				$q->set("position", $i+1);
				$q->set("question", $questions[$i]);
				$q->set("surveyId", $survey->getObjectId());
				$q->set("speakerId", $speaker);
				try {
					$q->save();
				} catch (ParseException $ex) {
					echo $ex->getMessage() . ' ' . $ex->getCode();
				}
			}
		
			} else {
			    for ($i=0; $i < count($questions); $i++) { 
				$q = new ParseObject("SurveyQuestion");
				$q->set("position", $i+1);
				$q->set("question", $questions[$i]);
				$q->set("surveyId", $survey->getObjectId());
				$q->set("speakerId", $speaker);
				try {
					$q->save();
				} catch (ParseException $ex) {
					echo $ex->getMessage() . ' ' . $ex->getCode();
				}
			}
		
			}
		}
	/*		for ($i=0; $i < count($questions); $i++) { 
				$q = new ParseObject("SurveyQuestion");
				$q->set("position", $i+1);
				$q->set("question", $questions[$i]);
				$q->set("surveyId", $survey->getObjectId());
				$q->set("speakerId", $speaker);
				try {
					$q->save();
				} catch (ParseException $ex) {
					echo $ex->getMessage() . ' ' . $ex->getCode();
				}
			}
		}*/
		public function submitNewSurvey($exist_obj_id = null, $eventId, $questions) {
			//$eventId = $this->eventId;
			if ($exist_obj_id) {
				$q = $this->getSurveyQuestionInfo($exist_obj_id);
				/*
				 * there should be only one question in questions array
				 */
				
				$q->set("question", $questions);
				
			try {
					$q->save();
				} catch (ParseException $ex) {
					echo $ex->getMessage() . ' ' . $ex->getCode();
				}
			} else {
				/*
				 * New question(s) to be added
				 */
			for ($i=0; $i < count($questions); $i++) {
				//	for ($i=0; $i <= 20 ; $i++) {
				
					$q = new ParseObject("SurveyQuestion");
					$q->set("position", $i+1);
					$q->set("question", $questions[$i]);
					$q->set("eventId", $eventId);
			try {
						$q->save();
						$_SESSION['newTabObjId'] = $q->getObjectId();
					} catch (ParseException $ex) {
						echo $ex->getMessage() . ' ' . $ex->getCode();
					}
				}//end of for
			}
		}	
			
			
		/* Update Functions */
		
			public function updateEventInfo($name, $logo, $start, $end, $address, $city, $location, $triggerBeacons, $twitter, $code, $organizerCode) {
			$query = $this->parseQuery("Event");
			$query->equalTo("objectId", $this->eventId);
			$event = $query->first();
			$regex = "/\s|\#/";
			$trigger_array1 = preg_split($regex, $triggerBeacons, -1, PREG_SPLIT_NO_EMPTY);
			$twitter_array = preg_split($regex, $twitter, -1, PREG_SPLIT_NO_EMPTY);
			$trigger_array = array();
			foreach($trigger_array1 as $beacon) {
				array_push($trigger_array, $this->getBeaconId($beacon));
			}
			$startDate = new DateTime($start);
			$endDate = new DateTime($end);
			if($logo['name'] != null) {
				$file = ParseFile::createFromData(file_get_contents($logo['tmp_name']), $logo['name']);
			}	
			$event->set("name", $name);		
			$event->set("startDate", $startDate);
			$event->set("endDate", $endDate);
			$event->set("address", $address);
			$event->set("city", $city);
			$event->set("location", $location);
			$event->setArray('triggerBeacons', $trigger_array);
			$event->setArray("twTags", $twitter_array);
			$event->set("code", intval($code));
			$event->set("organizerCode", $organizerCode);
			try {
				if($logo['name'] != null) {
					$file->save();
					$event->set("logo", $file);
				}
				$event->save();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}
		private function updateSpeakerQuestions($id, $questions_array) {
			$query = $this->parseQuery("SurveyQuestion");
			$query->equalTo("speakerId", $id);
			$query->ascending("position");
			$questions = $query->first();
			$i = 0;
			foreach($questions as $question) {
				if($question->get("question") != $questions_array[$i]) {
					$question->set("questions", $questions_array[$i]);
					try {
						$question->save();
					} catch (ParseException $ex) {
						echo $ex->getMessage() . ' ' . $ex->getCode();
					}
				}
			}
		}
		public function updateSpeakerInfo($id, $fname, $lname, $company, $linked, $twitter, $image, $bio, $questions) {
			$query = $this->parseQuery("Speaker");
			$query->equalTo("objectId", $id);
			$speaker = $query->first();
			if($image['name'] != null) {
				$file = ParseFile::createFromData(file_get_contents($image['tmp_name']), $image['name']);
			}
			$speaker->set("firstName", $fname);
			$speaker->set("lastName", $lname);
			$speaker->set("company", $company);
			$speaker->set("linkedInURL", $linked);
			if(count($questions) > 0) {
				$this->updateSpeakerQuestions($id, $questions);
			}
			$speaker->set("twitterURL", $twitter);
			$speaker->set("about", $bio);
			try {
				if($image['name'] != null) {
					$file->save();
					$speaker->set("avatar", $file);
				}
				$speaker->save();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}
		public function updateSessionInfo($id, $title, $room, $speaker, $startDate, $endDate, $details) {
			$query = $this->parseQuery("Session");
			$query->equalTo("objectId", $id);
			$session = $query->first();
			$startTime = new DateTime($startDate);
			$endTime = new DateTime($endDate);
			$session->set("title", $title);
			$session->set("roomName", $room);
			$session->set("speakerId", $speaker);
			$session->set("startTime", $startTime);
			$session->set("endTime", $endTime);
			$session->set("details", $details);
			try {
				$session->save();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}
		public function updateExhibitorInfo($id, $name, $sum, $logo) {
			$query = $this->parseQuery("Exhibitor");
			$query->equalTo("objectId", $id);
			$exhibitor = $query->first();
			$exhibitor->set("name", $name);
			$exhibitor->set("website", $sum);
			if($logo['name'] != null) {
				$file = ParseFile::createFromData(file_get_contents($logo['tmp_name']), $logo['name']);
			}
			try {
				if($logo['name'] != null) {
					$file->save();
					$exhibitor->set("logo", $file);
				}	
				$exhibitor->save();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}
		public function updateBeaconInfo($id, $name, $company) {
			$query = $this->parseQuery("Beacon");
			$query->equalTo("objectId", $id);
			$beacon = $query->first();
			$beacon->set("name", $name);
			$beacon->set("companyId", $this->getCompanyId($company));
			try {
				$beacon->save();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}
		public function updateNotificationInfo($id, $not_title, $not_message, $not_pdf, $not_url, $not_time, $not_beacon) {
			$query = $this->parseQuery("Notification");
			$query->equalTo("objectId", $id);
			$not = $query->first();
			$not->set("title", $not_title);
			$not->set("message", $not_message);
			if($not_pdf['name'] != null) {
				$file = ParseFile::createFromData(file_get_contents($not_pdf['tmp_name']), $not_pdf['name']);
			}
			$not->set("url", $not_url);
			$not->set("triggerTimeInSecs", $not_time);
			$not->set("beaconId", $not_beacon);
			try {
				if($not_pdf['name'] != null) {
					$file->save();
					$not->set("pdfFile", $file);
				}
				$not->save();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}
		public function updateSurveyInfo($id, $pos_array, $ques_array, $title, $start, $session, $speaker, $added_questions) {
			$survey_query = $this->parseQuery("Survey");
			$quest_query = $this->parseQuery("SurveyQuestion");
			$quest_query->equalTo("surveyId", $id);
			$survey_query->equalTo("objectId", $id);
			$quest_query->ascending("position");
			$quest_query->select(['question', 'position']);
			$quest = $quest_query->find();
			$survey = $survey_query->first();
			$j = 0;
			foreach($quest as $q) {
				for($i = 0; $i < count($pos_array)-1; $i++) {
					if($q->get('question') == $pos_array[$i]) {
						$q->set("position", $i+1);
					}
					try {
						$q->save();
					} catch (ParseException $ex) {
						echo $ex->getMessage() . ' ' . $ex->getCode();
					}
				}
				if($q->get("question") != $ques_array[$j]) {
					$q->set("question", $ques_array[$j]);
					try {
						$q->save();
					} catch (ParseException $ex) {
						echo $ex->getMessage() . ' ' . $ex->getCode();
					}
				}	
				$j++;				
			}
			if($added_questions != NULL) {
				$po = count($pos_array);
				foreach($added_questions as $questions) {
					$qu = new ParseObject("SurveyQuestion");
					$qu->set("question",  $questions);
					$qu->set("surveyId", $id);
					$qu->set("position", $po);
					$qu->set("speakerId", $speaker);
					$po++;
					try {
						$qu->save();
					} catch (ParseException $ex) {
						echo $ex->getMessage() . ' ' . $ex->getCode();
					}
				}
			} 
			$survey->set("sessionId", $session);
			$startTime = new DateTime($start);
			$survey->set('startDate', $startTime);
			$survey->set("title", $title);
			$survey->set("speakerId", $speaker);
			try {
				$survey->save();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}
		/* Delete Functions */
		private function redoSurveyPosition($info) {
			$query = $this->parseQuery("SurveyQuestion");
			$query->equalTo("surveyId", $info['specific']);
			$query->ascending("position");
			$questions = $query->find();
			$iteration = $info['pos'];
			foreach ($questions as $q) {
				if($q->get("position") > $info['pos']) {
					$q->set("position", $iteration);
					$iteration++;
					try {
						$q->save();
					} catch (ParseException $ex) {
						echo $ex->getMessage() . ' ' . $ex->getCode();
					}
				}
			}
		}
		private function deleteExhibitor($id) {
			$query = $this->parseQuery("EventCompany");
			$companyInfo = $this->parseQuery("Company");
			$query->equalTo('objectId', $id);
			$ev = $query->first();
			$companyInfo->equalTo("objectId", $ev->get("companyId"));
			$company = $companyInfo->find()[0];
			$ev->destroy();
			$ev->delete("objectId");
			$company->destroy();
			$company->delete("objectId");
			try {
				$company->save();
				$ev->save();
			} catch (ParseException $ex) {
				echo $ex->getMessage() . ' ' . $ex->getCode();
			}
		}
		private function deleteSurveyInfo($info) {
			if($info['pos'] != null) {
				$isLastQuestion = false;
				$query = $this->parseQuery("SurveyQuestion");
				$query->equalTo("surveyId", $info['specific']);
				$query->equalTo("position", $info['pos']);
				if($query->exists("position", $info['pos']+1)) {
					$isLastQuestion = true;
				}
				$question = $query->find()[0];
				if($isLastQuestion) $this->redoSurveyPosition($info);
				$question->destroy();
				try {
					$question->save();
				} catch (ParseException $ex) {
					echo $ex->getMessage() . ' ' . $ex->getCode();
				}
			} else {
				$surveyQ = $this->parseQuery("Survey");
				$surveyQ->equalTo("objectId", $info['specific']);
				$survey = $surveyQ->first();
				$questionsQ = $this->parseQuery("SurveyQuestion");
				$questionsQ->equalTo("surveyId", $info['specific']);
				$questions = $questionsQ->find();
				foreach($questions as $question) {
					$question->destroy();
				}
				$survey->destroy();
				try {
					$question->save();
					$survey->save();
				} catch (ParseException $ex) {
					echo $ex->getMessage() . ' ' . $ex->getCode();
				}
			}
		}
		public function deleteRow($id, $class) {
			if($this->getSelectedTable($class) == 'Company') {
				$this->deleteExhibitor($id);
			} else if($this->getSelectedTable($class) == 'Survey'){
				$this->deleteSurveyInfo($id);
			} else {
				/* General Delete */
				$query = $this->parseQuery($this->getSelectedTable($class));
				$query->equalTo("objectId", $id);
				$results = $query->first();
				$results->destroy();
				$results->delete("objectId");
				try {
					$results->save();
				} catch (ParseException $ex) {
					echo $ex->getMessage() . ' ' . $ex->getCode();
				}
			}
		}
		
		public function getSessionForSpeaker($speaker) {
			$speakerExists = 0;
			$speaker_info = $this->getSpeakerInfo($speaker);
			$eventId = $speaker_info->get("eventId");
			
			$allSessions = $this->getSessionsByEventId($eventId);
			
			foreach ($allSessions as $session) {
				if ($session->get("speakerIds")) {
					$allSpeakersId = $session->get("speakerIds");
					foreach ($allSpeakersId as $speakerId) {
						if ($speakerId == $speaker) {
							$speakerExists = 1;
							break;
						}
					}
				}
				if ($speakerExists == 1){
					break;
				}
				
			}
			return $speakerExists;    	
		}
		
		public function getNotificationForBeacon($beacon) {
			$beaconExists = 0;
			$allNotifics = $this->getNotificsWithBeacons($beacon);
			if (count($allNotifics)>0) {
				$beaconExists = 1;
			}
			return $beaconExists;
		}

		public function checkDeleteIntegrity($id, $class) {
			//return 108;
			$returnVal = 0;
		    if ($this->getSelectedTable($class) == "Speaker") {
		        $returnVal = $this->getSessionForSpeaker($id);    	
		    }
		    else if ($this->getSelectedTable($class) == 'Beacon') {
		        $returnVal = $this->getNotificationForBeacon($id);    	
		    }	
		    return $returnVal;
		}
		
	
	}