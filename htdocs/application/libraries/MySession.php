<?php if ( ! defined('BASEPATH')) 
	exit('No direct script access allowed');

class MySession {
	const CLIENT_LOGON = 'client_logon';
	const COMPANY_LOGON = 'company_logon';
	const FROM_POST = 'from_post';
	const USER_ID = 'user_id';
	const WANTED_URL_DESTINATION = 'wanted_url_destination';
	const FACEBOOK_ACCESS_TOKEN = 'facebook_access_token';
	const AFTER_LOGIN = 'after_login';
	const FACEBOOK_AUTHENTICATED = 'facebook_authenticated';
	const ADMIN_LOGON = 'admin_logon';
	
	public function __construct() {
	}
	
	public function logon_client_user($user_id) {
		get_instance()->session->set_userdata(self::USER_ID, $user_id);
		get_instance()->session->set_userdata(self::CLIENT_LOGON, TRUE);
		get_instance()->session->set_userdata(self::COMPANY_LOGON, FALSE);
		get_instance()->session->set_userdata(self::ADMIN_LOGON, FALSE);
	}
	
	public function logon_company_user($user_id) {
		get_instance()->session->set_userdata(self::USER_ID, $user_id);
		get_instance()->session->set_userdata(self::CLIENT_LOGON, FALSE);
		get_instance()->session->set_userdata(self::COMPANY_LOGON, TRUE);
		get_instance()->session->set_userdata(self::ADMIN_LOGON, FALSE);
		$this->set_facebook_access_token(NULL);
	}
	
	public function logon_admin() {
		$this->logoff_user();
		get_instance()->session->set_userdata(self::ADMIN_LOGON, TRUE);
	}
	
	public function logoff_user() {
		get_instance()->session->sess_destroy();
		get_instance()->session->set_userdata(self::USER_ID, NULL);
		get_instance()->session->set_userdata(self::CLIENT_LOGON, NULL);
		get_instance()->session->set_userdata(self::COMPANY_LOGON, NULL);
		get_instance()->session->set_userdata(self::ADMIN_LOGON, NULL);
		$this->set_facebook_access_token(NULL);
	}
	
	public function get_user_id() {
		return get_instance()->session->userdata(self::USER_ID);
	}
	
	public function get_company_id() {
		$user_id = $this->get_user_id();
		
		get_instance()->load->model('CompanyModel');
		return get_instance()->CompanyModel->get_company(NULL, $user_id)->id;
	}
	
	public function get_client_id() {
		$user_id = $this->get_user_id();
		
		get_instance()->load->model('ClientModel');
		return get_instance()->ClientModel->get_client_id_for_user_id($user_id);
	}
		
	public function is_company_user_logged() {
		return get_instance()->session->userdata(self::COMPANY_LOGON);
	}
	
	public function is_client_user_logged() {
		return get_instance()->session->userdata(self::CLIENT_LOGON);
	}
	
	public function is_admin_logged() {
		return get_instance()->session->userdata(self::ADMIN_LOGON);
	}
	
	public function export_to_smarty() {
		get_instance()->mysmarty->assignVariable(self::COMPANY_LOGON, $this->is_company_user_logged());
		get_instance()->mysmarty->assignVariable(self::CLIENT_LOGON, $this->is_client_user_logged());
		get_instance()->mysmarty->assignVariable(self::FACEBOOK_AUTHENTICATED, $this->is_facebook_authenticated());
	}
	
	public function set_from_post() {
		get_instance()->session->set_flashdata(self::FROM_POST, TRUE);
	}
	
	public function is_from_post() {
		return get_instance()->session->flashdata(self::FROM_POST);
	}
	
	public function set_after_login() {
		get_instance()->session->set_flashdata(self::AFTER_LOGIN, TRUE);
	}
	
	public function is_after_login() {
		return get_instance()->session->flashdata(self::AFTER_LOGIN);
	}
	
	public function set_wanted_url_destination($url) {
		get_instance()->session->set_flashdata(self::WANTED_URL_DESTINATION, $url);
	}
	
	public function get_wanted_url_destination() {
		return get_instance()->session->flashdata(self::WANTED_URL_DESTINATION);
	}
	
	public function has_wanted_url_destination() {
		return get_instance()->session->flashdata(self::WANTED_URL_DESTINATION) !== NULL;
	}
	
	public function clean_wanted_url_destination() {
		get_instance()->session->userdata(self::WANTED_URL_DESTINATION, NULL);
	}
	
	public function get_facebook_access_token() {
		return get_instance()->session->userdata(self::FACEBOOK_ACCESS_TOKEN);
	}
	
	public function set_facebook_access_token($access_token) {
		get_instance()->session->set_userdata(self::FACEBOOK_ACCESS_TOKEN, $access_token);
	}
	
	public function is_facebook_authenticated() {
		return $this->is_client_user_logged() &&
			$this->get_facebook_access_token() !== NULL;
	}
	
}
