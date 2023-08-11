<?php lock();
	
class Install extends Core {
    
        private $cant_write = array();
	
	function __Construct(){
		
		if(get_magic_quotes_gpc()){
			exit("Please disable 'magic_quotes_gpc' in your php.ini or in a htaccess file first.<br>In case you don't know how to do that visit our support page at http://ucpanel.net/support");
		}
            
		if(!$this->check_writeable()){
		    $this->please_chmod();
		}
		
		if($this->POST('installaction') == '1'){
		    $this->install_system();
		}
		
		$this->write_config('rewrite_template_path', $this->config('system_theme_path').'install/');
		$this->OUT()->assign('static_link',$this->config('system_theme_url').'install/');
		$this->OUT()->assign('step',($this->POST('installaction') == '1') ? '2' : '1');
		$this->OUT()->assign('available_themes', $this->get_themes());
		$this->OUT()->assign('available_languages', $this->get_languages());
		$this->OUT()->assign($this->config());
		
		echo $this->OUT()->draw('home', true);

	}
        
        private function install_system(){
		
		$errors = array();
		
		/* Set Preferences */
		$this->pref('username',$this->POST('username'));
		$this->pref('password',sha1($this->POST('password')));
		$this->pref('email',$this->POST('email'));
		$this->pref('theme',$this->POST('theme'));
		$this->pref('language',$this->POST('language'));
	    
	    
		/* Theme activation */
		$functions = $this->config('site_theme_path') . $this->POST('theme') . '/functions.php';
		if(file_exists($functions)){
			include_once($functions);
			$theme = ucfirst($this->POST('theme')) . '_Theme';
			$theme = new $theme;
			$this->write_config('theme_support', $theme->theme_supports);
			if(method_exists($theme, 'activate')){
				@$theme->activate();
			}
		}
            
		file_put_contents($this->config('user_path').'cache/system/installed.tmp', '1');
		
		$content = str_replace("install", $this->POST('folder'), file_get_contents($this->config('system_path').'config.php'));
		file_put_contents($this->config('system_path').'config.php',$content);
		
		$old = umask(0);
		@chmod($this->config('system_path').'config.php', 0644);
		umask($old);
		
		$perm = substr(decoct(fileperms($this->config('system_path').'config.php')),2);
		
		if($perm == '0777' || $perm == '777'){
		    $errors[] = array(
			'path' => $this->config('system_path').'config.php',
			'perm' => '644'
		    );
		}
    
		@rename($this->config('full_path') . 'install', $this->config('full_path') . $this->POST('folder'));
    
		$old = umask(0);
		@chmod($this->config('full_path') . $this->POST('folder'), 0755);
		umask($old);
		
		if(!is_dir($this->config('full_path') . $this->POST('folder'))){
		    $errors[] = array(
			'path' => $this->config('full_path') . $this->POST('folder'),
			'perm' => '755'
		    );
		    $errors[] = array(
			'path' => $this->config('full_path') . 'install',
			'perm' => 'rename'
		    );
		}
		
		$this->OUT()->assign('errors',$errors);
		$this->OUT()->assign('new_name',$this->POST('folder'));
	    

        }
        
        private function get_themes(){
            
            $ret = '';
            
            foreach(glob($this->config('site_theme_path').'*/', GLOB_ONLYDIR) as $dir) {
                
                $dir = end(explode("/",trim($dir,"/")));
                
                $ret .= '<option value='.$dir.'>'.ucwords(str_replace("_", " ", trim(trim($dir,"'"),"\""))).'</option>';
            
            }
            
            return $ret;
            
        }
	
	private function get_languages(){
            
            $ret = '';
            
            foreach(glob($this->config('system_path').'language/*/', GLOB_ONLYDIR) as $dir) {
                
                $dir = end(explode("/",trim($dir,"/")));
                
                $ret .= '<option value='.$dir.'>'.$dir.'</option>';
            
            }
            
            return $ret;
            
        }
        
        private function check_writeable(){
            
            $this->cant_write = array(
            
                'storage' => $this->config('full_path') . 'storage',
		'thumbnails' => $this->config('full_path') . 'storage/thumbnails',
                'cache' => $this->config('user_path') . 'cache',
                'sqlite' => $this->config('user_path') . 'cache/sqlite',
                'system' => $this->config('user_path') . 'cache/system',
                'templates' => $this->config('user_path') . 'cache/templates',
                'config' => $this->config('system_path') . 'config.php',
                'user_folder' => $this->config('user_path')
            
            );
            
            foreach($this->cant_write as $key => $path){
                
                if(is_writable($path)){
                    unset($this->cant_write[$key]);
                }
                
            }
            
            return (count($this->cant_write) == 0) ? true : false;
            
        }
        
        private function please_chmod(){
            
            
            ?>
            
            <!DOCTYPE html>
            <html lang="en-US">
            <head>
            <meta charset="UTF-8" />
            <style>
                body{background: #eee; text-shadow: 1px 1px #fff; color: #333; padding: 100px; font-family: Arial, Verdana, sans-serif;}
                li{margin-top: 5px; font-size: 12px; color: #666;}
                button{border: none; color: #fff; background: #369; cursor: pointer; padding: 10px; font-size: 16px; margin-top: 10px; font-weight: bold;opacity: 0.8; position: relative;}
                button:hover{opacity: 1}
                button:active{top: 2px;}
            </style>
            <body>
                <h1>Seems like the directories/files below aren't writable!</h1>
                <h3>Please CHMOD them to 777 to continue!</h3>
                <ul>
                    <?php foreach($this->cant_write as $path) { ?>
                    <li><?php echo $path; ?></li>
                    <?php } ?>
                </ul>
                <button onClick="history.go()">Re-try</button>
            </body>
            
            <?php
            
            exit;
            
        }
	
}

?>