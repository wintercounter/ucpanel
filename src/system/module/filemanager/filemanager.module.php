<?php lock();

class Filemanager_Module extends Core{
    
    function __Construct(){
        /* Placeholder */
    }
    
    public function home(){
        
        $this->write_config('rewrite_template_path', $this->config('system_theme_path').'filemanager/');
        
        $out = array(
          'action' => 'showContent|initFileManager',
          'content' => $this->OUT()->draw('home', true)
        );
        
        return json_encode($out);
        
    }
    
}

?>