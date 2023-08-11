<?php lock();

    // Sub-Pages
    class Content_Admin_Hooks extends Core{
        
        function __Construct(){
            $this->module('content');
            $this->hook()->on('admin','start','generate_menu',$this);
        }
        
        public function generate_menu(){
            
            $this->PANEL()->add_menu(array(
                
                'content' => array(
                    
                    'title' => $this->lang('content','content_title'),
                    'count' => 0,
                    'target' => '#content:home',
                    'icon' => 'pencil'
                )
                
            ));
            
        }
        
    }
    
    new Content_Admin_Hooks;