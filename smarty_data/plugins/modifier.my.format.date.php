<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.my_format_date.php
 * Type:     modifier
 * Name:     format date
 * Purpose:  Formats mysql datetimes to portuguese dates
 * -------------------------------------------------------------
 */

define('MY_SMARTY_DATE_TIME_FORMAT', '%Y/%m/%d %H:%M');
define('MY_SMARTY_DATE_FORMAT', '%d de %B de %Y');
 
function smarty_modifier_my_format_date($date_str) {
	$my_datetime_cmp = strptime($date_str, MY_SMARTY_DATE_TIME_FORMAT);
	
	$datetime = mktime(	$my_datetime_cmp['tm_hour'], 
						$my_datetime_cmp['tm_min'], 
						$my_datetime_cmp['tm_sec'], 
						$my_datetime_cmp['tm_mon'] + 1, 
						$my_datetime_cmp['tm_mday'], 
						$my_datetime_cmp['tm_year'] + 1900);
	
	return strftime(MY_SMARTY_DATE_FORMAT, $datetime);
}