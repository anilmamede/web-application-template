<?php if ( ! defined('BASEPATH')) 
	exit('No direct script access allowed');

require_once ("facebook.php");

class MyFacebook {
	
	private $facebook_obj;
	
	const EXTENDED_PERMISSIONS = '';
	const FACEBOOK_GRAPH_ACCESS_TOKEN_URL = 'https://graph.facebook.com/oauth/access_token';
	const FACEBOOK_GRAPH_USER_DATA_URL = 'https://graph.facebook.com/me';
	const FACEBOOK_GRAPH_PHOTO_URL = 'https://graph.facebook.com/me/picture?type=large';
	
	const NETWORK_ERROR = 'NetworkError';
	const OAUTH_EXCEPTION = 'OAuthException';
	const ACCESS_TOKEN_MISSING = 'AccessTokenMissing';
	const ACCESS_TOKEN_GRANTED = 'AccessTokenGranted';
		
	public function __construct() {
		$config = array();
		$config['appId'] = FACEBOOK_APPID;
		$config['secret'] = FACEBOOK_APPSECRET;
		$config['fileUpload'] = false;
	
		$this->facebook_obj = new Facebook($config);
		$this->facebook_obj->setAccessToken($this->_get_access_token_from_session());
	}
	
	public function get_login_url() {
		$login_params = array(	'redirect_uri' => FACEBOOK_REDIRECT_URL, 
								'display' => 'page', 'scope' => self::EXTENDED_PERMISSIONS);

		return $this->facebook_obj->getLoginUrl($login_params);
	}
	
	public function do_login($code) {
		get_instance()->load->model('ClientModel');
		
		$facebook_login_data = $this->_get_access_token_from_facebook($code);
		$access_token = $facebook_login_data['data']['access_token'];
		get_instance()->mysession->set_facebook_access_token($access_token);
		
		get_instance()->ClientModel->login_with_facebook();
	}
	
	public function get_photo() {
		$access_token = get_instance()->mysession->get_facebook_access_token();
		$photo = my_web_uri_access(self::FACEBOOK_GRAPH_PHOTO_URL . "&access_token=$access_token", TRUE);

		return array(
			'filename' => 'facebook_photo.jpg',
			'data' => $photo,
			'mimetype' => 'image/jpeg',
			'width' => 0,
			'height' => 0,
			'filesize' => 0);
	}
	
	public function get_user_data() {
		$access_token = get_instance()->mysession->get_facebook_access_token();
		$result = my_web_uri_access(self::FACEBOOK_GRAPH_USER_DATA_URL . "?access_token=$access_token");
		
		$this->__check_oauth_exception_error($result);
		$result = json_decode($result, TRUE);
		
		return $result;
	}
	
	private function _get_access_token_from_facebook($code) {
		$appid = FACEBOOK_APPID;
		$appsecret = FACEBOOK_APPSECRET;
		$my_url = FACEBOOK_REDIRECT_URL;
	
		$token_url = self::FACEBOOK_GRAPH_ACCESS_TOKEN_URL . "?client_id=$appid&redirect_uri=$my_url&client_secret=$appsecret&code=$code";
		
		log_message('debug', $token_url);
				
		$result = my_web_uri_access($token_url);
		
		/* If an error occurred then an exception was thrown. So we
		 * assume that no network errors ocurred
		 */
		 
		$this->__check_oauth_exception_error($result);
		
		/* Third case. Check if access_token key is in the response */
		if(stripos($result, "access_token") === FALSE) {
			/* Is not in the response. Return an associative array with the error */
			$error_desc = array('type' => self::ACCESS_TOKEN_MISSING, 'message' => 'The access token is missing');
			throw new FacebookException(array('success' => FALSE, 'error' => $error_desc));
		}
		
		/* Finally parse the access_token and expires value and return */
		$access_token_parsed = $this->_parse_access_token($result);
		$access_token_parsed['type'] = self::ACCESS_TOKEN_GRANTED;
		
		return array('success' => TRUE, 'data' => $access_token_parsed);		
	}

	private function __check_oauth_exception_error($result) {
		log_message('debug', 'OAuthException: ' . $result);
		if(stripos($result, self::OAUTH_EXCEPTION) !== FALSE) {
			/* An error ocurred, parse the JSON and throw an exception */
			$result = json_decode($result, TRUE);
			$error = array('type' => self::OAUTH_EXCEPTION, 'message' => $result);
			throw new FacebookException(array('success' => FALSE, 'error' => $error));
		}
		
	}
	
	private function _parse_access_token($str) {	
		$values = explode('&', $str);
		
		foreach($values as $val) {
			$keyval = explode('=', $val);
			$result[trim($keyval[0])] = trim($keyval[1]);
		}	
		
		return $result;
	}
	
	private function _get_access_token_from_session() {
		get_instance()->mysession->get_facebook_access_token();
	}
}