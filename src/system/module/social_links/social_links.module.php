<?php lock();

class Social_links_Module extends Core{
  
    public  $sites;
    
    function __Construct(){
    	
				if(!$this->installed('social_links')){
		        $this->install();
		    }
        
        $this->sites = array(
            'custom' => $this->lang('social_links', 'custom_link'),
            'aim' => 'AIM',
            'android' => 'Android',
            'appstore' => 'Apple Store',
            'bebo' => 'Bebo',
            'blogger' => 'Blogger',
            'delicious' => 'Delicious',
            'designfloat' => 'DesignFloat',
            'deviantart' => 'DeviantArt',
            'digg' => 'Digg',
            'dribbble' => 'Dribbble',
            'ebay' => 'eBay',
            'evernote' => 'Evernote',
            'facebook' => 'Facebook',
            'feedburner' => 'Feedburner',
            'flickr' => 'Flickr',
            'foursquare' => 'Foursquare',
            'gmail' => 'Gmail',
            'google-plus' => 'Google+',
            'gowalla' => 'Gowalla',
            'grooveshark' => 'Grooveshark',
            'hi5' => 'Hi5',
            'lastfm' => 'LastFM',
            'linkedin' => 'LinkedIn',
            'livejournal' => 'LiveJournal',
            'metacafe' => 'Metacafe',
            'mobileme' => 'MobileMe',
            'msn' => 'MSN',
            'myspace' => 'MySpace',
            'ning' => 'Ning',
            'openid' => 'OpenID',
            'orkut' => 'Orkut',
            'pandora' => 'Pandora',
            'paypal' => 'PayPal',
            'picasa' => 'Picasa',
            'plurk' => 'Plurk',
            'posterous' => 'Posterous',
            'reddit' => 'Reddit',
            'rss' => 'RSS Feed',
            'skype' => 'Skype',
            'stumbleupon' => 'StumbleUpon',
            'tumblr' => 'Tumblr',	
            'twitter' => 'Twitter',
            'vimeo' => 'Vimeo',
            'wordpress' => 'WordPress',
            'xing' => 'XING',
            'yelp' => 'Yelp',
            'youtube' => 'YouTube',
            'youversion' => 'YouVersion'
        );
        
        $supports = isset($this->THEME()->theme_supports['social_links']) ? $this->THEME()->theme_supports['social_links'] : false;
        
        foreach($this->sites as $key => $site){
            
            $this->sites[$key] = array();
            $this->sites[$key]['name'] = $site;
            
            if($supports === true || (is_array($supports) && in_array($key, $supports))){
                
                $this->sites[$key]['supported'] = true;
                
            }
            else{
                
                $this->sites[$key]['supported'] = false;
                
            }
            
        }
        
    }
    
    public function add_site($slug, $title){
        
        $this->sites[$slug] = $title;
        
    }
    
    public function remove_site($slug){
        
        unset($this->sites[$slug]);
        
    }
    
    public function manage(){
      
        $this->write_config('rewrite_template_path', $this->config('system_theme_path').'social_links/');
        
        $this->OUT()->assign('sites', $this->sites);
        $this->OUT()->assign('links', $this->get_links());
        
        $out = array(
          'action' => 'showContent',
          'content' => $this->OUT()->draw('manage', true)
        );
        
        return json_encode($out);
      
    }
    
    public function add_link($arr){
        
        $proto = array(
            'id' => false,
            'key' => 'custom',
            'title' => '',
            'value' => '',
            'disabled' => 0,
            'order' => 0
        );
        
        $arr = $this->param_mount($proto, $arr);
        
        // Begin update
        if($arr['id'] && ($this->DB()->get_var("SELECT count(id) FROM social_links WHERE id = '" . $arr['id'] . "'") > 0)){
            
            $this->DB()->query("UPDATE social_links SET
                               title = '" . $arr['title'] . "',
                               value = '" . $arr['value'] . "',
                               disabled = '" . $arr['disabled'] . "',
                               \"order\" = '" . $arr['order'] . "'
                               WHERE id = '" . $arr['id'] . "'
            ");
            
        }
        else{

            $this->DB()->query("INSERT INTO social_links ('key','title','value','disabled','order') VALUES (
                               '" . $arr['key'] . "',
                               '" . $arr['title'] . "',
                               '" . $arr['value'] . "',
                               '" . $arr['disabled'] . "',
                               '" . $arr['order'] . "')
            ");
            
        }
        
    }
    
    public function get_links($where = false){
        
        $where = $where ? 'WHERE ' . $where : '';
        
        $r = $this->DB()->get_results('SELECT * FROM social_links '.$where.' ORDER BY "order" ASC', ARRAY_A);
        
        if($r !== NULL){
        
            foreach($r as $k => $v){
                
                $r[$k]['site_title'] = isset($this->sites[$v['key']]) ? $this->sites[$v['key']]['name'] : $v['key'];
                $r[$k]['supported'] = isset($this->sites[$v['key']]) ? $this->sites[$v['key']]['supported'] : false;
                
            }
        
        }
        
        return $r;
        
    }
    
    public function view_edit(){
        
        foreach($this->POST('id') as $k => $id){
            
            $key = $this->POST('key');
            $key = $key[$k];
            
            $title = $this->POST('title');
            $title = $title[$k];
            
            $value = $this->POST('value');
            $value = $value[$k];
            
            $order = $this->POST('order');
            $order = $order[$k];
            
            $disabled = $this->POST('disabled');
            $disabled = $disabled[$k];
            
            $this->add_link(array(
                'id' => $id,
                'key' => $key,
                'title' => $title,
                'value' => $value,
                'order' => $order,
                'disabled' => $disabled
            ));
            
        }
        
        return array(
            'action' => 'refreshPage|alert|refreshIframe',
            'content' => $this->lang('social_links','edit_success')
        );
        
    }
    
    public function view_delete(){
        
        $this->delete_link($this->POST('id'));
        
        return array(
            'action' => 'refreshPage|alert|refreshIframe',
            'content' => $this->lang('social_links','delete_success')
        );
        
    }
    
    public function view_enable(){
        
        $this->toggle_link($this->POST('id'));
        
        return array(
            'action' => 'refreshIframe|social_links_enabled',
            'content' => $this->POST('id')
        );
        
    }
    
    public function view_disable(){
        
        $this->toggle_link($this->POST('id'));
        
        return array(
            'action' => 'refreshIframe|social_links_disabled',
            'content' => $this->POST('id')
        );
        
    }
    
    public function toggle_link($id){
        $state = ($this->DB()->get_var("SELECT disabled FROM social_links WHERE id = '$id'") == 0) ? 1 : 0; 
        $this->DB()->query("UPDATE social_links SET disabled = '$state' WHERE id = '$id'");
    }
    
    public function delete_link($id){
        $this->DB()->query("DELETE FROM social_links WHERE id = '$id'");
    }
    
    public function save_table(){
        
        foreach($this->POST('id') as $k => $id){
            
            $key = $this->POST('key');
            $key = $key[$k];
            
            $title = $this->POST('title');
            $title = $title[$k];
            
            $value = $this->POST('value');
            $value = $value[$k];
            
            $order = $this->POST('order');
            $order = $order[$k];
            
            $disabled = $this->POST('disabled');
            $disabled = $disabled[$k];
            
            $this->add_link(array(
                'id' => $id,
                'key' => $key,
                'title' => $title,
                'value' => $value,
                'order' => $order,
                'disabled' => $disabled
            ));
            
        }
        
        return array(
            'action' => 'refreshIframe',
            'content' => 'OK'
        );
        
    }
    
    public function view_add_link(){
        
        $this->add_link(array(
            'key' => $this->POST('key'),
            'title' => $this->POST('title'),
            'value' => $this->POST('value')
        ));
        
        return array('action' => 'refreshPage|alert|refreshIframe', 'content' => $this->lang('social_links','added_successfully'));
        
    }
		
		private function install(){
				
				$this->DB()->query('
          CREATE TABLE "social_links" (
            "id" integer NOT NULL PRIMARY KEY,
            "key" text NOT NULL,
            "title" text NOT NULL,
						"value" text NOT NULL,
            "disabled" integer NOT NULL,
            "order" integer NOT NULL
          )                   
        ');
				
				$this->installed('social_links', true);

		}
    
}

?>