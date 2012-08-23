<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class FacebookException extends Exception {

	public function __construct($facebook_response) {
		$this->facebook_response = $facebook_response;
	}

	public function friendlyErrorMessage() {
		return json_encode($this->facebook_response);
	}

}

function log_facebook_exception($level, $exception) {
	log_message($level, $exception->friendlyErrorMessage());
}

class FacebookAccountIsNotUserType extends Exception {}

class FacebookUserNotRegistered extends Exception {}

class WebResourceAccessException {
	
	private $error;
	
	public function __construct($error) {
		$this->error = $error;
	}
	
	public function getError() {
		return $this->error;
	}
}

class GravatarPhotoNotAvailable extends Exception {}


/* END OF PHP FILE */
