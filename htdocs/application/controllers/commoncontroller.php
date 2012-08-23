<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

abstract class CommonController extends CI_Controller {

	public function __construct() {
		parent::__construct();
		setlocale(LC_ALL, 'pt_PT');
	}
		
}
