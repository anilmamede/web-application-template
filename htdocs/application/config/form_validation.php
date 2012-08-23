<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$config = array();

$config['example_validation'] = array(
				array('field' => 'name', 'label' => 'Name', 'rules' => 'trim|required|max_length[50]'),
				array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email|max_length[50]'),
				array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|min_length[8]|max_length[50]'),
				array('field' => 're_password', 'label' => 'Re-Password', 'rules' => 'trim|required|matches[password]'));
