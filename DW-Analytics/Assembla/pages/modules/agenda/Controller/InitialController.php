<?php
	include 'pages/modules/agenda/Model/InitialModel.php';
	class InitialController {
		/**
		 * @var $model The InitalModel Object that is instantiated in the contructor
		 */
		public $model;
		/**
		 * Sets up the Model for the Class
		 */
		public function __construct() {
			$this->model = new InitialModel();
		}
		/**
		 * Sets up the info for the page and calls the correct page
		 */
		public function invoke() {
			$model = $this->model;
			$event = $this->model->eventObj;
			$logo = $event->get("logo") ? $event->get("logo")->getURL() : '/assets/img/logo_placeholder.png';
			$startDate = $this->model->startDate->format('l jS F Y');
			$sessions = $this->model->sessionObj;
			$timezone = $this->model->timezone;
			$speakers = $this->model->speakers;
			$app_version = "0.0.1";
			$company_number = "781-242-2423";
			$company_email = "hello&#64;beepetc.com";
			include 'pages/modules/agenda/View/initial_view.php';
		}
	}
