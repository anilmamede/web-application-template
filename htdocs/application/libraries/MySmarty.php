<?php if ( ! defined('BASEPATH')) 
	exit('No direct script access allowed');
	
class MySmarty {
	
	public $smarty_obj;
	
	public function __construct() {
			require_once(SMARTY_DIR . 'Smarty.class.php');
			
			$this->smarty_obj = new Smarty();
			$this->smarty_obj->template_dir		= SMARTY_TEMPLATES;
			$this->smarty_obj->compile_dir		= SMARTY_TEMPLATES_C;
			$this->smarty_obj->config_dir		= SMARTY_CONFIGS;
			$this->smarty_obj->cache_dir		= SMARTY_CACHE;
			$this->smarty_obj->caching			= Smarty::CACHING_LIFETIME_SAVED;
			$this->smarty_obj->cache_lifetime	= 0;
			$smarty->plugins_dir = array( 'plugin', SMARTY_PLUGINS);
	}
	
	public function setCacheLifetime($seconds) {
		$this->smarty_obj->cache_lifetime($seconds);
	}
	
	public function assignVariable($key, $value) {
		$this->smarty_obj->assign($key, $value);
	}
	
	public function displayTemplate($template) {
		$this->smarty_obj->display($template);
	}
	
	public function fetchTemplate($template) {
		return $this->smarty_obj->fetch($template);
	}
	
	public function isTemplateCached($template) {
		$this->smarty_obj->isCached($template);
	}
	
	public function reset() {
		$this->smarty_obj->clearAllAssign();
	}
}