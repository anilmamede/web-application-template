<?php  

if (!defined('CONFIGURATION')) {
	
	/* The website URL. For local environment use virtual hosts if you have more than 
	 *	one application in http server
	 */
	define('WEBSITE', 'http://acme.local');
	define('APP_DOMAIN', 'acme.local');
	define('ENVIRONMENT', 'development');
	
	/* Point to the Code Igniter 2.1.0 instalation system folder */
	define('CI_SYSTEM_LOCATION', '</Users/acme/CodeIgniter_2.1.0>' . '/system');
	
	/* When an user authenticates with Facebook he returns to web application. Define 
	 * the returning URL.
	 */
	define('FACEBOOK_REDIRECT_URL', WEBSITE . '/authentication/facebook');
	define('FACEBOOK_APPID', '');
	define('FACEBOOK_APPSECRET', '');

	/* Database configuration */
	define('DATABASE_HOSTNAME', 'localhost');
	define('DATABASE_NAME', 'acme_db');
	define('DATABASE_USERNAME', 'acme_db');
	define('DATABASE_PASSWORD', '<somepassword>');
	define('DATABASE_TABLE_PREFIX', 'acme_');
	
	/* Point to SMARTY template engine instalation folder */ 
	define('SMARTY_DIR', '</Users/acme/Smarty-3.0.5>' . '/libs/');
	define('SMARTY_TEMPLATES', dirname(__FILE__) . '/../smarty_data/templates/');
	define('SMARTY_TEMPLATES_C', dirname(__FILE__) . '/../smarty_data/templates_c/');
	define('SMARTY_CONFIGS', dirname(__FILE__) . '/../smarty_data/configs/');
	define('SMARTY_CACHE', dirname(__FILE__) . '/../smarty_data/cache/');
	define('SMARTY_PLUGINS', dirname(__FILE__) . '/../smarty_data/plugins/');
	
	define('MAIL_FACTORY', 'smtp');
	define('SMTP_OUTGOING_EMAIL_SERVER', 'mail.acme.com');
	define('SMTP_USERNAME', 'no-reply@acme.com');
	define('SMTP_PASSWORD', '<somepassword>');
	
	/* Security Settings. Define an encription key for salt passwords and sessions. */
	define('COOKIE_PREFIX', 'acme');
	define('ENCRYPTION_KEY', '<defineacmekey>');

	define('CONFIGURATION', TRUE);
}

// END OF PHP SCRIPT