<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

define('MYSQL_DATE_FORMAT', '%Y-%m-%d');
define('MYSQL_DATETIME_FORMAT', '%Y-%m-%d %H:%M');

if (!function_exists('my_datetime_to_mysql')) {

	function my_datetime_to_mysql($datetime = NULL) {
		if ($datetime == NULL) {
			return strftime(MYSQL_DATETIME_FORMAT, time());
		}

		return strftime(MYSQL_DATETIME_FORMAT, $datetime);
	}

}

if (!function_exists('my_mysql_to_datetime')) {

	function my_mysql_to_datetime($mysql_datetime) {
		$my_datetime_cmp = strptime($mysql_datetime, MYSQL_DATETIME_FORMAT);

		return mktime($my_datetime_cmp['tm_hour'], $my_datetime_cmp['tm_min'], $my_datetime_cmp['tm_sec'], $my_datetime_cmp['tm_mon'] + 1, $my_datetime_cmp['tm_mday'], $my_datetime_cmp['tm_year'] + 1900);
	}

}

define('MY_STR_TO_DATETIME_FORMAT', '%d-%m-%Y %H:%M');

if (!function_exists('my_str_to_datetime')) {
	
	function my_str_to_datetime($str) {
		$my_datetime_cmp = strptime($str, MY_STR_TO_DATETIME_FORMAT);

		return mktime($my_datetime_cmp['tm_hour'], $my_datetime_cmp['tm_min'], $my_datetime_cmp['tm_sec'], $my_datetime_cmp['tm_mon'] + 1, $my_datetime_cmp['tm_mday'], $my_datetime_cmp['tm_year'] + 1900);		
	}
}

if (!function_exists('my_mysql_get_unique_code')) {
	function my_mysql_get_unique_code() {
		$CI = &get_instance();
		$CI->db->select('nextval');
		$nextval = $CI->db->get('sequence')->row()->nextval;
		$CI->db->update('sequence', array('nextval' => $nextval + 1));

		return $nextval;
	}

}

/* Copyright 2006 Maciej Strzelecki

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA */

if (!function_exists('uuid')) {
	function uuid() {
		// version 4 UUID
		return sprintf('%08x-%04x-%04x-%02x%02x-%012x', mt_rand(), mt_rand(0, 65535), bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '0100', 11, 4)), bindec(substr_replace(sprintf('%08b', mt_rand(0, 255)), '01', 5, 2)), mt_rand(0, 255), mt_rand());
	}

}

if (!function_exists('my_redirect')) {
	function my_redirect($url = "") {
		redirect(str_replace('/index.php', '', site_url($url)));
	}
}

if(!function_exists('get_age')) {
		
	function get_age($birthday) {
		$elaspse_sec = time() - $birthday;
		$year = 31536000;
		
		return floor($elaspse_sec / $year);
	}
}

if(!function_exists('my_current_url')) {
	function my_current_url() {
		return str_replace('/index.php', '', current_url());
	}
}


if(!function_exists('my_web_uri_access')) {

	function my_web_uri_access($url, $binary = FALSE) {
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		if($result === FALSE) {
			$error = array('type' => NETWORK_ERROR, 'message' => curl_error());
			throw new WebResourceAccessException($error);
		}
		
		if($binary) {
			$result = base64_encode($result);
		}	
		
		return $result;
	}

} 

/* END OF PHP FILE */
