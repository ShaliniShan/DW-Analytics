<?php

	use Parse\ParseQuery;
	use Parse\ParseUser;
	use Parse\ParseException;

	class ACL {

		private $userPerm = 0;			//Integer : Stores the ID of the current user

		private function __constructor($userID = '') {
			$roles = ACL::getUserPermissions($userID);
			$_SESSION['userRole'] = $roles[0];
			if($_SESSION['userRole'] == 'o')
				$this->userPerm = 1;
			else if ($_SESSION['userRole'] == 'a')
				$this->userPerm = 0;
		}

		private static function getUserPermissions($userId) {
			if($userId == '') return;
			$userInfo = ParseUser::query();
			$userInfo->equalTo("objectId", $userId);
			$user_perm = $userInfo->first();
			return $user_perm->get("roles");
		}

		public function ACL($userID = '') {
			$this->__constructor($userID);
		}

		public function getUsername() {
			return $_SESSION['username'];
		}

		public function getUserPerm() {
			return $this->userPerm;
		}

		public function hasPermission($key) {
			if($key != 'o') {
				return false;
			}
			return true;
		}
	}
