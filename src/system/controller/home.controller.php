<?php lock();
	
class Home extends Core {
	
	private $theme, $theme_uc;
	
	function __Construct(){
		
		$this->theme = $this->pref('theme');
		$this->theme_uc = ucfirst($this->theme);
            
		$this->write_config('rewrite_template_path', $this->config('site_theme_path').$this->theme.'/');
			
		if(file_exists($this->config('rewrite_template_path').'functions.php')){
			require_once($this->config('rewrite_template_path').'functions.php');
			if(file_exists($this->config('rewrite_template_path').'language.php')){
				global $LANG;
				require_once($this->config('rewrite_template_path').'language.php');
				$this->extend($this->generate_js_lang($this->theme), 'footer');
			}
			$class = $this->theme_uc.'_Theme';
			$theme_class = new $class();
			$this->write_config('inited_class', $theme_class, 'active_theme');
			if(method_exists($theme_class,'construct')){
				$theme_class->construct();
			}
			$this->write_config('theme_support', $theme_class->theme_supports);
		}
		else{
			exit($this->config('rewrite_template_path').'functions.php');
		}
            
		// Add home hook
		$this->hook('home')->run('home', 'start');
		
		// Run custom action
		if($this->POST('action')){
			
			$r = $this->run_method($this->POST('action'));
			
			if($r !== 'unregistered'){
				
				if(is_array($r) || !is_object(json_decode($r))){
					
					$r = json_encode($r);
					
				}
				
				header('Content-type: application/json; charset=UTF-8');
				$this->quit($r);
				
			}
			
		}
    
		$this->OUT()->assign('static_link',$this->config('site_theme_url').$this->pref('theme').'/');
		$this->OUT()->assign($this->config());
		
		$out = $this->OUT()->draw($this->config('page'), true);
		
		$this->hook()->run('home', 'end', $out);
		
		$this->quit($out);

	}
	
	private function generate_js_lang($k){
		
		$out = "<script>\n// Theme languages\nvar lang = {};";
		
		foreach($this->lang($k) as $key => $lang){
			$out .= 'lang.'.$key.' = "'.$lang.'";';
		}
		
		return $out."\n</script>";
		
	}
	
}

?>