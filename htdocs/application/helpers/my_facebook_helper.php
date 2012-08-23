<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

require_once ("facebook.php");

function access_token_from_session($session) {
	return $session->userdata('facebook_token');
}

function facebook_obj($access_token = '') {
	$config = array();
	$config['appId'] = APPID;
	$config['secret'] = APPSECRET;
	$config['fileUpload'] = false;

	$facebook = new Facebook($config);
	$facebook->setAccessToken($access_token);
	
	return $facebook;
}

/* Define constants for access token response */
define('NETWORK_ERROR', 'NetworkError');
define('OAUTH_EXCEPTION', 'OAuthException');
define('ACCESS_TOKEN_MISSING', 'AccessTokenMissing');
define('ACCESS_TOKEN_GRANTED', 'AccessTokenGranted');

function access_token($code) {
	$appid = FACEBOOK_APPID;
	$appsecret = FACEBOOK_APPSECRET;
	$my_url = FACEBOOK_REDIRECT_URL;

	$token_url = "https://graph.facebook.com/oauth/access_token?client_id=$appid&redirect_uri=$my_url&client_secret=$appsecret&code=$code";
	
	$response = access_web_uri($token_url);
	
	/* If an error occurred then an exception was thrown. So we
	 * assume that no network errors ocurred
	 */
		
	$result = $response['data'];
	
	/* Check if an OAuthException ocurred */
	if(stripos($result, OAUTH_EXCEPTION) !== FALSE) {
		/* An error ocurred, parse the JSON and throw an exception */
		$result = json_decode($result, TRUE);
		$error = array('type' => OAUTH_EXCEPTION, 'message' => $result);
		throw new FacebookException(array('success' => FALSE, 'error' => $error));
	}
	
	/* Third case. Check if access_token key is in the response */
	if(stripos($result, "access_token") === FALSE) {
		/* Is not in the response. Return an associative array with the error */
		$error_desc = array('type' => ACCESS_TOKEN_MISSING, 'message' => 'The access token is missing');
		throw new FacebookException(array('success' => FALSE, 'error' => $error_desc));
	}
	
	/* Finally parse the access_token and expires value and return */
	$access_token_parsed = parse_access_token($result);
	$access_token_parsed['type'] = ACCESS_TOKEN_GRANTED;
	
	return array('success' => TRUE, 'data' => $access_token_parsed);
}

function parse_access_token($str) {	
	$values = explode('&', $str);
	
	foreach($values as $val) {
		$keyval = explode('=', $val);
		$result[trim($keyval[0])] = trim($keyval[1]);
	}	
	
	return $result;
}

define('FACEBOOK_QUERY_SUCCESS', 'FacebookQuerySuccess');

function facebook_query($url, $binary = FALSE) {
	log_message('debug', 'facebook_query called');
	
	$response = access_web_uri($url, $binary);
	
	/* If an error ocurred then an exception was thrown */
	if(!$binary) {
		$data = json_decode($response['data'], TRUE);
	} else {
		$data = $response['data'];
	}
	
	/* Check for OAuthException */
	if(!$binary && isset($data['error']) && $data['error']['type'] === OAUTH_EXCEPTION) {
		throw new FacebookException(array('success' => FALSE, 'error' => $data['error']));
	}
	
	/* Success */
	log_message('debug', "facebook_query return");
	
	return array('success' => TRUE, 'data' => $data);
}


define('FACEBOOK_DATE_FORMAT', '%m/%d/%Y');

function from_facebook_to_php_date($facebook_date) {
	log_message('debug', 'facebook date: ' . $facebook_date);
	$my_datetime_cmp = strptime($facebook_date, FACEBOOK_DATE_FORMAT);
	return mktime(0, 0 , 0, $my_datetime_cmp['tm_mon'] + 1, $my_datetime_cmp['tm_mday'], $my_datetime_cmp['tm_year'] + 1900);
}

/* End of file my_facebook_helper.php */
/* Location: ./application/helpers/my_facebook_helper.php */
