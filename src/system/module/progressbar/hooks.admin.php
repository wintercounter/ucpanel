<?php lock();

    // Sub-Pages
    class Progressbar_Admin_Hooks extends Core{
        
        function __Construct(){
            $this->load_lang('progressbar');
            $this->hook()->on('admin', 'start', 'add_menu', $this);
        }
        
        public function add_menu(){
            
            $this->PANEL()->add_menu(array(
                
                'progressbar' => array(
                    
                    'title' => $this->lang('progressbar','title'),
                    'count' => 0,
                    'target' => '#progressbar:manage',
                    'icon' => 'align-left'
                )
                
            ));
            
        }
        
    }
    
    new Progressbar_Admin_Hooks;
    
?>