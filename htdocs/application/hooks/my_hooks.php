<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/* DEALING WITH ERRORS */

function exception_error_handler($errno, $errstr, $errfile, $errline) {
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

function exception_handler($exception) {
	$file = $exception->getFile();
	$line = $exception->getLine();
	$message = $exception->getMessage();
	log_message('error', "$file:$line -> $message");
	
  	log_message('error', $exception);
	
	rollback_db_transaction();
	close_db_transaction();
	
	show_error('Uncaught Exception. See the logs');
}

function convert_errors_to_exceptions() {
	if(version_compare(PHP_VERSION, '5.3.0') >= 0) {
		set_error_handler('exception_error_handler', E_ALL ^ E_DEPRECATED);
	} else {
		set_error_handler('exception_error_handler', E_ALL);
	}
	
	set_exception_handler('exception_handler');
}

/* DATABASE TRANSACTIONS MANAGEMENT */

function start_db_transaction() {
	$CI =& get_instance();
	
	log_message('info', 'Start Database Transaction');
	$CI->db->trans_start();
}

function close_db_transaction() {
	$CI =& get_instance();
	
	log_message('info', 'Close Database Transaction');
	$CI->db->trans_complete();

	if ($CI->db->trans_status() === FALSE)
	{
	    throw new Exception("Error in database");
	} 	
}

function rollback_db_transaction() {
	$CI =& get_instance();
	
	log_message('info', 'Rollback Database Transaction');
	$CI->db->trans_rollback();
}


/* SESSION INFORMATION TO SMARTY */

function smarty_assign_my_session() {
}

/* END OF PHP FILE */
