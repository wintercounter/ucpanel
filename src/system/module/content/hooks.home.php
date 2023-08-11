<?php lock();

    // Sub-Pages
    class Content_Home_Hooks extends Core{
        
        function __Construct(){
            $this->hook()->on('home','start','assign_content',$this);
        }
        
        public function assign_content(){
            
            $fields = $this->module('content')->get_fields();
            
            foreach($fields as $key => $value){
                
                $this->OUT()->assign('content_' . $value['key'], $value['value']);
                
            }
            
        }
        
    }
    
    new Content_Home_Hooks;