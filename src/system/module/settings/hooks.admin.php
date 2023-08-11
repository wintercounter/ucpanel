<?php lock();

    // Sub-Pages
    class Settings_Admin_Hooks extends Core{
        
        function __Construct(){
            $this->module('settings');
            $this->hook()->on('admin', 'start', 'add_menu', $this);
        }
        
        public function add_menu(){
            
            $this->PANEL()->add_menu(array(
                
                'settings' => array(
                    
                    'title' => $this->lang('settings','title'),
                    'count' => 0,
                    'target' => '#nogo',
                    'icon' => 'cogs',
                    'submenu' => array(
                      'system' => array(
                        'title' => $this->lang('settings','system_settings'),
                        'count' => 0,
                        'target' => '#settings:system'
                      ),
                      'themes' => array(
                        'title' => $this->lang('settings','theme_settings'),
                        'count' => 0,
                        'target' => '#settings:themes'
                      )
                    )
                )
                
            ));
            
        }
        
    }
    
    new Settings_Admin_Hooks;
    
?>