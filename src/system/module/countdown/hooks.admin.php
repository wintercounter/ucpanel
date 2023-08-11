<?php lock();

    // Sub-Pages
    class Countdown_Admin_Hooks extends Core{
        
        function __Construct(){
            $this->module('countdown');
            $this->hook()->on('admin', 'start', 'add_menu', $this);
        }
        
        public function add_menu(){
            
            $this->PANEL()->add_menu(array(
                
                'countdown' => array(
                    
                    'title' => $this->lang('countdown','countdown_title'),
                    'count' => 0,
                    'target' => '#countdown:home',
                    'icon' => 'time'
                )
                
            ));
            
        }
        
    }
    
    new Countdown_Admin_Hooks;
    
?>