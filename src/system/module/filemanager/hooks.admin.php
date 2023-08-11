<?php lock();

    // Sub-Pages
    class Filemanager_Admin_Hooks extends Core{
        
        function __Construct(){
            $this->load_lang('filemanager');
            $this->hook()->on('admin','start','generate_menu',$this);
        }
        
        public function generate_menu(){
            
            $this->PANEL()->add_menu(array(
                
                'filemanager' => array(
                    
                    'title' => $this->lang('filemanager','title'),
                    'count' => 0,
                    'target' => '#filemanager:home',
                    'icon' => 'cloud'
                    
                )
                
            ));
            
        }
        
    }
    
    new Filemanager_Admin_Hooks;