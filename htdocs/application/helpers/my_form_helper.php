<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');


if (!function_exists('my_set_value')) {
	
	function my_set_value($field, $alt_value = FALSE, $empty_str_as_none = TRUE) {
		$result = set_value($field, $alt_value);
		
		log_message('error', "result: |$field| && |$result|");
		
		if(trim($result) === '' && $empty_str_as_none) {
			return $alt_value;
		}
		
		return $result;
	}
}

if (!function_exists('my_read_request')) {
	
	function my_read_request($ruleset_name, $read_from_form = TRUE) {
		include(APPPATH . '/config/form_validation.php');
		
		$result = array();
		$result['global_message'] = '';
		$result['errors'] = array();

		$ruleset = $config[$ruleset_name];
		
		foreach ($ruleset as $rule) {
			$result[$rule['field']] = $read_from_form ? set_value($rule['field'], FALSE) : '';
			$result['errors'][$rule['field']] = $read_from_form ? form_error($rule['field']) : '';
		}
		
		return (object) $result;
	}
}

if (!function_exists('my_build_request')) {
	
	function my_build_request($ruleset_name) {
		return my_read_request($ruleset_name, FALSE);
	}
}

if (!function_exists('my_build_request_from_data')) {
		
	function my_build_request_from_data($ruleset_name, $data) {
		include(APPPATH . '/config/form_validation.php');
		
		$result = array();
		$result['global_message'] = '';
		$result['errors'] = array();

		$ruleset = $config[$ruleset_name];
		
		foreach ($ruleset as $rule) {
			$result[$rule['field']] = $data->$rule['field'];
			$result['errors'][$rule['field']] = '';
		}
		
		return (object) $result;
	}
	
}

// END OF PHP FILE