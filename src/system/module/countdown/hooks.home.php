<?php lock();

    // Sub-Pages
    class Countdown_Home_Hooks extends Core{
        
        function __Construct(){
            $this->hook()->on('home', 'start', 'add_countdown', $this);
        }
        
        public function add_countdown(){
            
            $this->add_js($this->config('system_url').'module/countdown/assets/jquery.countdown.min.js', 'home');
            $this->add_css($this->config('system_url').'module/countdown/assets/jquery.countdown.css', 'home');
            $this->OUT()->assign('module_countdown', '<div id="module_countdown"></div>');
            $this->extend($this->module('countdown')->generate_code(), 'footer');
            
        }
        
    }
    
    new Countdown_Home_Hooks;
    
?>