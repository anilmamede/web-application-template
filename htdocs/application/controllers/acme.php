<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

include_once('commoncontroller.php');

class Acme extends CommonController {
	
	public function __construct() {
		parent::__construct();
		
	}
	
	function index() {
	}
	
}
