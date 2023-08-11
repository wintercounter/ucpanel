<?php lock();

class Settings_Module extends Core{
    
    private $variables = array('theme_name','theme_author','theme_version','theme_description','theme_supports','theme_cover');
    private $methods = array('construct', 'activate', 'settings','reset_defaults');
    
    function __Construct(){
        
    }
    
    public function themes(){
        
        $this->write_config('rewrite_template_path', $this->config('system_theme_path').'settings/');
        
        $themes_data = $this->get_themes_data();
        
        $this->assign_active_data();
		
		if($this->has_settings()){
			$this->write_config('rewrite_template_path', $this->config('site_theme_path') . $this->pref('theme') . '/');
			$has_settings = true;
			$settings_html = $this->OUT()->draw('settings', true);
			$this->write_config('rewrite_template_path', $this->config('system_theme_path').'settings/');
		}
		else{
			$has_settings = false;
			$settings_html = '';
		}
        
        $this->OUT()->assign('themes_data', $themes_data);
        $this->OUT()->assign('active_theme', $this->pref('theme'));
        $this->OUT()->assign('has_settings', $has_settings);
        $this->OUT()->assign('settings_html', $settings_html);
        $this->OUT()->assign('site_theme_url', $this->config('site_theme_url'));
        
        $out = array(
          'action' => 'showContent',
          'content' => $this->OUT()->draw('themes', true)
        );
        
        return json_encode($out);
        
    }
    
    public function system(){
        
        $this->write_config('rewrite_template_path', $this->config('system_theme_path').'settings/');
        
        $this->OUT()->assign('username', $this->pref('username'));
        $this->OUT()->assign('email', $this->pref('email'));
        $this->OUT()->assign('languages', $this->get_languages());
        
        $out = array(
          'action' => 'showContent',
          'content' => $this->OUT()->draw('system', true)
        );
        
        return json_encode($out);
        
    }
    
    public function save_system(){
        
        $this->pref('username', $this->POST('username'));
        $this->pref('email', $this->POST('email'));
        $this->pref('language', $this->POST('language'));
        
        if($this->POST('password') != ''){
            $this->pref('password', sha1($this->POST('password')));
        }
        
        return array(
            'action' => 'logout',
            'content' => ''
        );
        
    }
	
    public function activate_theme(){
            
            $out = array();
            
            if($theme = $this->POST('theme')){
                    
                    $this->pref('theme', $theme);
                    
                    include_once($this->config('site_theme_path') . $theme . '/functions.php');
                    
                    $class = ucfirst($theme) . '_Theme';
                    
                    $theme_class = new $class;
                    
                    if(method_exists($theme_class, 'activate')){
                            
                        $theme_class->activate();
                            
                    }
                    
                    $out['content'] = $this->lang('settings','theme_activation_success');
                    $out['action'] = 'alert|refreshPage|refreshIframe';
                    
            }
            else{
                    $out['content'] = $this->lang('settings','theme_activation_unsuccess');
                    $out['action'] = 'alert';
            }
            
            return json_encode($out);
            
    }
    

    /* Privates */
    
    private function get_themes_data(){
		
		$unshift = false;
        
        $data = array();
		
        $path = $this->config('site_theme_path');
        
        foreach (glob($path . '*', GLOB_ONLYDIR) as $theme) {
            
			$theme = end(explode("/",$theme));
		
			if($theme_class = $this->get_theme_class($theme)){
				
				/* Add variables */
				foreach($this->variables as $var){
					
					$data[$theme][$var] = isset($theme_class->$var) ? $theme_class->$var : false;
					
				}
				
				/* Add methods */
				foreach($this->methods as $method){
					
					$data[$theme][$method . '_method'] = method_exists($theme_class, $method) ? true : false;
					
				}
				
				$data[$theme]['theme_folder'] = $theme;
				
				if($theme == $this->pref('theme')){
					$unshift = $data[$theme];
					unset($data[$theme]);
				}
				
			}
                
        }
		
		if($unshift){
			array_unshift($data, $unshift);
		}
        
        return $data;
            
    }
    
    private function get_theme_class($theme){
        
        if(file_exists($this->config('site_theme_path') . $theme . '/functions.php')){
            
            include_once($this->config('site_theme_path') . $theme . '/functions.php');
            
            $class = ucfirst($theme) . '_Theme';
            return new $class();
            
        }
        
        return false;
        
    }
    
    private function assign_active_data(){
        
        foreach($this->variables as $var){
            
            if(isset($this->THEME()->$var)){
                
                if($var == 'theme_supports'){
                    
                    $a = array();
                    
                    foreach($this->THEME()->$var as $key => $v){
                        
                        $a[$key] = $this->labelize($key);
                        
                    }
                    
                }
                else{
                    $a = $this->THEME()->$var;
                }
                
                $a = $this->OUT()->assign($var, $a);
                
            }
            
        }
        
        foreach($this->methods as $method){
            
            if(method_exists($this->THEME(), $method)){
                
                $this->OUT()->assign('theme_method_' . $method, true);
                
            }
            else{
                $this->OUT()->assign('theme_method_' . $method, false);
            }
            
        }
        
    }
	
    private function has_settings($theme = false){
            
        $theme = $theme ? $theme : $this->pref('theme');

        return file_exists($this->config('site_theme_path') . $theme . '/settings.php');

    }
    
    private function get_languages(){
            
        $ret = '';
        
        foreach(glob($this->config('system_path').'language/*/', GLOB_ONLYDIR) as $dir) {
            
            $dir = end(explode("/",trim($dir,"/")));
            
            if($this->pref('language') == $dir){
                
                $ret .= '<option value='.$dir.' SELECTED>'.$dir.'</option>';
                
            }
            else{
            
                $ret .= '<option value='.$dir.'>'.$dir.'</option>';
                
            }
        
        }
        
        return $ret;
            
    }
    
}

?>