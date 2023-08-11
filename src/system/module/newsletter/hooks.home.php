<?php lock();

    // Sub-Pages
    class Newsletter_Home_Hooks extends Core{
        
        function __Construct(){
            
            if($this->GET('newsletter_unsubscribe') && $this->GET('d')){

                $this->load_lang('newsletter');
                
                $mail = urldecode($this->GET('newsletter_unsubscribe'));
                
                if($this->valid_email($mail)){
                    
                    $id = $this->DB()->get_var("SELECT id FROM email WHERE email = '$mail'");
                    
                    if( sha1($id) == $this->GET('d')){
                        
                        $this->module('newsletter')->delete($id);
                        
                    }
                    
                }
                
                $this->quit('<html><head><meta charset="utf-8"></head><body><h1>' . $this->lang('newsletter','unsubscribed_successfully') . '</h1></body></html>');
                
            }
            
        }
        
    }
    
    new Newsletter_Home_Hooks;
    
?>