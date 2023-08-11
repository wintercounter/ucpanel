<?php lock();

    // Sub-Pages
    class Newsletter_Admin_Hooks extends Core{
        
        function __Construct(){
            $this->module('newsletter');
            $this->hook()->on('admin', 'start', 'add_menu', $this);
        }
        
        public function add_menu(){
            
            $this->PANEL()->add_menu(array(
                
                'newsletter' => array(
                    
                    'title' => $this->lang('newsletter','title'),
                    'count' => 0,
                    'target' => '#nogo',
                    'icon' => 'envelope-alt',
                    'submenu' => array(
                      'manage' => array(
                        'title' => $this->lang('newsletter', 'manage'),
                        'target' => '#newsletter:manage'
                      ),
                      'create' => array(
                        'title' => $this->lang('newsletter', 'create'),
                        'target' => '#newsletter:create'
                      )
                    )
                )
                
            ));
            
            $this->PANEL()->add_sub_menu('settings', array(
              'newsletter_settings' => array(
                'title' => $this->lang('newsletter', 'settings'),
                'target' => '#newsletter:settings'
              )
            ));
            
        }
        
    }
    
    new Newsletter_Admin_Hooks;
    
?>