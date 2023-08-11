<?php lock();

    // Sub-Pages
    class Social_links_Admin_Hooks extends Core{
        
        function __Construct(){
            $this->load_lang('social_links');
            $this->hook()->on('admin', 'start', 'add_menu', $this);
        }
        
        public function add_menu(){
            
            $this->PANEL()->add_menu(array(
                
                'social_links' => array(
                    
                    'title' => $this->lang('social_links','title'),
                    'count' => 0,
                    'target' => '#social_links:manage',
                    'icon' => 'group'
                )
                
            ));
            
        }
        
    }
    
    new Social_links_Admin_Hooks;
    
?>