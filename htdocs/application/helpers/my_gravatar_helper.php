<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

if (!function_exists('my_gravatar_photo_retrieval')) {
	
	define('GRAVATAR_SERVICE_BASE_URL', 'http://www.gravatar.com/avatar/');
	define('GRAVATAR_OPTIONS', '?d=404&s=200');
	
	function my_gravatar_photo_retrieval($email) {

		try {
			$email_hash = md5(trim(strtolower($email))) . '.jpg';
			
			$photo = my_web_uri_access(GRAVATAR_SERVICE_BASE_URL . $email_hash . GRAVATAR_OPTIONS, TRUE);
			
			return array(
				'filename' => $email_hash,
				'data' => $photo,
				'mimetype' => 'image/jpeg',
				'width' => 0,
				'height' => 0,
				'filesize' => 0);

		} catch(WebResourceAccessException $e) {
			throw new GravatarPhotoNotAvailable();
		}
		
	}
}