<?php lock();

class Content_Module extends Core{
  
    public $supports = false;
    
    function __Construct(){
      
        if(!$this->installed('content')){
          $this->install();
        }
        
        $s = $this->config('theme_support');
        
        if(isset($s['content'])){
            
            $this->supports = $s['content'];
        
            foreach($s['content'] as $key => $type){
              $this->create_support($key, $type);
            }
        
        }
        
    }
    
    public function home(){
      
      $this->write_config('rewrite_template_path', $this->config('system_theme_path').'content/');
      
      $this->OUT()->assign('content', $this->get_fields());
      
      $out = array(
        'action' => 'showContent',
        'content' => $this->OUT()->draw('home', true)
      );
      
      return json_encode($out);
      
    }
    
    public function get_fields(){
      
      $fields = $this->DB()->get_results("SELECT * FROM content LIMIT 5000", ARRAY_A);
      
      foreach($fields as $key => $value){
        
        if(isset($this->supports[$fields[$key]['key']])){
            
            $fields[$key]['label'] = $this->labelize($fields[$key]['key']);
            
        }
        else{
            
            unset($fields[$key]);
            
        }
        
      }
      
      return $fields;
    
    }
    

    public function update_content(){
      
      foreach($_POST() as $key => $value){
        
        if(isset($this->supports[$key])){
            
          $this->content($key, $value);
          
        }
        
      }
      
      return json_encode(array('action' => 'alert|refreshIframe', 'content' => $this->lang('content','saved_successfully')));
      
    }
    
    
    /* Get / Update / Add content data */
    final public function content($item, $value = NULL){
            
            if($value !== NULL){
                    
                    if($this->DB()->get_var("SELECT COUNT(id) FROM content WHERE key = '$item'") > 0){
                        
                            $this->DB(true)->prepare('UPDATE content SET value = ? WHERE key = ?')->execute(array($value, $item));
                            
                    }else{

                            $this->DB(true)->prepare("INSERT INTO content ('key', 'value') VALUES (?, ?, ?)")->execute(array($item, $value));
                            
                    }

                    return true;
                    
            }

            return @$this->DB()->get_var("SELECT value FROM content WHERE key = '$item'");

    }
    
    /* Privates */
    
    private function install(){
      
      $this->DB()->query('
        CREATE TABLE "content" (
        "id" integer NOT NULL PRIMARY KEY,
        "key" text NOT NULL,
        "value" text NOT NULL,
        "type" text NOT NULL)
      ');
      
      $this->installed('content', true);
      
    }
    
    private function create_support($key, $type){
      
      if($this->DB()->get_var("SELECT count(id) FROM content WHERE key = '$key'") != 0){
        return;
      }
      
      $this->DB()->query('
        INSERT INTO "content" ("key", "value", "type") VALUES ("'.$key.'", "", "'.$type.'")
      ');
    }
    
}

?>