<?php lock();

class Countdown_Module extends Core{
  
    public  $supports = false,
            $prefs;
    
    function __Construct(){
        
        $this->prefs = array(
            'until' => array('value' => date('m/d/Y H:i', time() + 60*60*24*365), 'group' => 1, 'class' => 'countdown_datepicker', 'type' => 'text'),
            'alwaysExpire' => array('value' => 'false', 'group' => 2, 'class' => '', 'type' => 'bool'),
            'compact' => array('value' => 'false', 'group' => 2, 'class' => '', 'type' => 'bool'),
            'compactLabels' => array('value' => "['y', 'm', 'w', 'd']", 'group' => 2, 'class' => '', 'type' => 'array'),
            'description' => array('value' => "", 'group' => 1, 'class' => '', 'type' => 'text'),
            'digits' => array('value' => "['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']", 'group' => 2, 'class' => '', 'type' => 'array'),
            'expiryText' => array('value' => "", 'group' => 1, 'class' => '', 'type' => 'text'),
            'expiryUrl' => array('value' => "", 'group' => 1, 'class' => '', 'type' => 'text'),
            'format' => array('value' => "dHMS", 'group' => 2, 'class' => '', 'type' => 'text'),
            'layout' => array('value' => "", 'group' => 2, 'class' => '', 'type' => 'textarea'),
            'isRTL' => array('value' => "false", 'group' => 2, 'class' => '', 'type' => 'bool'),
            'onExpiry' => array('value' => "null", 'group' => 2, 'class' => '', 'type' => 'text'),
            'onTick' => array('value' => "null", 'group' => 2, 'class' => '', 'type' => 'text'),
            'serverSync' => array('value' => "null", 'group' => 2, 'class' => '', 'type' => 'text'),
            'significant' => array('value' => "0", 'group' => 2, 'class' => '', 'type' => 'text'),
            'since' => array('value' => "null", 'group' => 2, 'class' => 'countdown_datepicker', 'type' => 'text'),
            'timezone' => array('value' => "null", 'group' => 2, 'class' => '', 'type' => 'text'),
            'tickInterval' => array('value' => "1", 'group' => 2, 'class' => '', 'type' => 'text'),
            'timeSeparator' => array('value' => ":", 'group' => 2, 'class' => '', 'type' => 'text'),
            'whichLabels' => array('value' => "null", 'group' => 2, 'class' => '', 'type' => 'text')
        );
      
        if(!$this->installed('countdown')){
          $this->install();
        }
        
    }
    
    public function generate_code(){
        
        $l =& $this->lang('core');
        
        $ret = '<script type="text/javascript">
        (function($){
            $(document).ready(function(){
                $("#module_countdown").countdown({
                labels : ["'.$l['Years'].'", "'.$l['Months'].'", "'.$l['Weeks'].'", "'.$l['Days'].'", "'.$l['Hours'].'", "'.$l['Minutes'].'", "'.$l['Seconds'].'"],
                labels1 : ["'.$l['Year'].'", "'.$l['Month'].'", "'.$l['Week'].'", "'.$l['Day'].'", "'.$l['Hour'].'", "'.$l['Minute'].'", "'.$l['Second'].'"],';
                
        foreach($this->prefs as $key => $attr){
            
            $val = ($attr['type'] == 'bool' || $attr['type'] == 'array' || $attr['value'] == 'null' || $key == 'until' || $key == 'since') ? $this->pref('countdown_'.$key) : "'".htmlspecialchars_decode($this->pref('countdown_'.$key), ENT_QUOTES)."'";
            if(($key == 'until' || $key == 'since') && $val != "null" ){
                $val = 'new Date('.(strtotime($val) * 1000).')';
            }
            $ret .= "       $key: ".$val.",\n";
            
        }
        
        $ret = rtrim($ret, ",");
        
        $ret .= '});});
        }(jQuery));
        </script>';
        
        return str_replace("&#039;", "'" , $ret);
        
    }
    
    public function home(){
      
        $this->write_config('rewrite_template_path', $this->config('system_theme_path').'countdown/');
        
        $this->OUT()->assign('simple_settings', $this->get_settings(1));
        $this->OUT()->assign('advanced_settings', $this->get_settings(2));
        
        $out = array(
          'action' => 'showContent',
          'content' => $this->OUT()->draw('home', true)
        );
        
        return json_encode($out);
      
    }    

    public function update_settings(){
      
      foreach($_POST as $key => $value){
        
        if(isset($this->prefs[$key])){
          
          $this->pref('countdown_'.$key, $value);

        }
        
      }
      
      return array('action' => 'alert|refreshIframe', 'content' => $this->lang('countdown','saved_successfully'));
      
    }
    
    
    
    /* Privates */
    
    private function install(){
        
        foreach($this->prefs as $key => $attr){
            
            if($this->pref('countdown_'.$key) === NULL){
                $this->pref('countdown_'.$key, $attr['value']);
            }
            
        }
        
        $this->installed('countdown', true);
        
    }
    
    private function get_settings($group){
        
        $r = array();
        
        foreach($this->prefs as $key => $attr){
            
            if($attr['group'] == $group){
                
                $r[$key] = array(
                    'label' => $this->labelize($key, true),
                    'class' => $attr['class'],
                    'type' => $attr['type'],
                    'value' => $this->pref('countdown_'.$key)
                );
                
            }
            
        }
        
        return $r;
        
    }
    
}

?>