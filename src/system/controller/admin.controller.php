<?php lock();
	
class Admin extends Core {
	
	private $theme, $theme_uc;
	
	function __Construct(){
		
		$this->theme = $this->pref('theme');
		$this->theme_uc = ucfirst($this->theme);
            
		$this->write_config('rewrite_template_path', $this->config('system_theme_path').'admin/');
		
		if(file_exists($this->config('site_theme_path').$this->theme.'/functions.php')){
			require_once($this->config('site_theme_path').$this->theme.'/functions.php');
			if(file_exists($this->config('site_theme_path').$this->theme.'/language.php')){
				global $LANG;
				require_once($this->config('site_theme_path').$this->theme.'/language.php');
			}
			$class = $this->theme_uc.'_Theme';
			$theme_class = new $class();
			$this->write_config('inited_class', $theme_class, 'active_theme');
			if(method_exists($theme_class,'construct')){
				$theme_class->construct();
			}
			$this->write_config('theme_support', $this->THEME()->theme_supports);
		}
		else{
			exit('functions.php doesn\'t exists for activated theme and it\'s required!');
		}
		
		// Add start hook
		$this->hook('admin')->run('admin', 'start');
		
		$method = $this->POST('action');
		$module = $this->POST('module');
	    
		if($method){
			
			if($module && $this->PANEL()->logged_in()){
				
				$r = ($module == 'theme_class') ? $this->THEME()->$method() : $this->module($module)->$method();
				
				if($method == 'reset_defaults' && $module == 'theme_class'){
					$r = array('action' => 'refreshPage|refreshIframe');
				}
				
				if(is_array($r) || !is_object(json_decode($r))){
					
					if(is_array($r)){
						$r['content'] = (isset($r['content'])) ? $r['content'] : '';
						$r['action'] = (isset($r['action'])) ? $r['action'] : '';
					}
					
					$r = json_encode($r);
					
				}
				
				header('Content-type: application/json; charset=UTF-8');
				$this->quit($r);

			}
			elseif($module && !$this->PANEL()->logged_in()){
				header("Location: " . $this->config('full_url'));
				exit;
			}
			else{
				$this->$method();
			}
			
		}
		
		$page = $this->PANEL()->logged_in() ? 'home' : 'login';
            
		$this->OUT()->assign('static_link', $this->config('system_theme_url').'admin/');
		$this->OUT()->assign($this->config());
		$this->OUT()->assign('lng_js', $this->lng_js());
		$this->OUT()->assign('conf_js', $this->conf_js());
		$this->OUT()->assign('menu',$this->PANEL()->menu);
		$this->OUT()->assign('logged_in', $this->PANEL()->logged_in());
		
		$out = $this->OUT()->draw($page, true);
		
		// Add end hook
		$this->hook()->run('admin', 'end');
    
		echo $out;
                        
	}
	
	private function login(){
		
		if(!$this->PANEL()->login($this->POST('username'), $this->POST('password'))){
			
			$this->OUT()->assign('error', true);
			
		}
		
	}
	
	private function lng_js(){
		
		$ret = '';
		
		foreach($this->lang() as $mod => $arr){
			
			$ret .= "ucp.lang.$mod={};";
			
			foreach($arr as $k => $v){
				
				$v = addslashes($v);
				$ret .= "ucp.lang.$mod.$k='$v';";
				
			}
			
		}
		
		return $ret;
	
	}
	
	private function conf_js(){
		
		$ret = '';
		
		foreach($this->config() as $key => $val){
			
			if(is_string($val)){

				$v = addslashes($val);
				$ret .= "ucp.config.$key='$v';";
				
			}
			
		}
		
		return $ret;
	
	}
	
}

?>