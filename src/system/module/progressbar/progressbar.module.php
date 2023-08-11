<?php lock();

class Progressbar_Module extends Core{
    
    function __Construct(){
        
       if(!$this->installed('progressbar')){
	        $this->install();
	}
        
    }
    
    public function manage(){
      
        $this->write_config('rewrite_template_path', $this->config('system_theme_path').'progressbar/');
        
        $this->OUT()->assign('progresses', $this->get_progress());
        
        $out = array(
          'action' => 'showContent',
          'content' => $this->OUT()->draw('manage', true)
        );
        
        return json_encode($out);
      
    }
    
    public function view_add(){
        
        $this->add_progress(array(
            'label' => $this->POST('label'),
            'percentage' => $this->POST('percentage')
        ));
        
        return array('action' => 'refreshPage|alert|refreshIframe', 'content' => $this->lang('progressbar','added_successfully'));
        
    }
    
    public function add_progress($params){
        
         $proto = array(
            'id' => false,
            'label' => '',
            'percentage' => 0,
            'disabled' => 0,
            'order' => 0
        );
        
        $arr = $this->param_mount($proto, $params);
        
        // Begin update
        if($arr['id'] && ($this->DB()->get_var("SELECT count(id) FROM progressbar WHERE id = '" . $arr['id'] . "'") > 0)){
            
            $this->DB()->query("UPDATE progressbar SET
                               label = '" . $arr['label'] . "',
                               percentage = '" . $arr['percentage'] . "',
                               disabled = '" . $arr['disabled'] . "',
                               \"order\" = '" . $arr['order'] . "'
                               WHERE id = '" . $arr['id'] . "'
            ");
            
        }
        else{

            $this->DB()->query("INSERT INTO progressbar ('label','percentage','disabled','order') VALUES (
                               '" . $arr['label'] . "',
                               '" . $arr['percentage'] . "',
                               '" . $arr['disabled'] . "',
                               '" . $arr['order'] . "')
            ");
            
        }
        
    }    
    
    public function get_progress($where = false){
        
        $where = $where ? 'WHERE ' . $where : '';
        
        $r = $this->DB()->get_results('SELECT * FROM progressbar '.$where.' ORDER BY "order" ASC', ARRAY_A);
        
        return $r;
        
    }
    
    public function view_edit(){
        
        foreach($this->POST('id') as $k => $id){
            
            $label = $this->POST('label');
            $label = $label[$k];
            
            $percentage= $this->POST('percentage');
            $percentage = $percentage[$k];
            
            $order = $this->POST('order');
            $order = $order[$k];
            
            $disabled = $this->POST('disabled');
            $disabled = $disabled[$k];
            
            $this->add_progress(array(
                'id' => $id,
                'label' => $label,
                'percentage' => $percentage,
                'order' => $order,
                'disabled' => $disabled
            ));
            
        }
        
        return array(
            'action' => 'refreshPage|alert|refreshIframe',
            'content' => $this->lang('progressbar','edit_success')
        );
        
    }
    
    public function view_delete(){
        
        $this->delete_progress($this->POST('id'));
        
        return array(
            'action' => 'refreshPage|alert|refreshIframe',
            'content' => $this->lang('progressbar','delete_success')
        );
        
    }
    
    public function view_enable(){
        
        $this->toggle_progress($this->POST('id'));
        
        return array(
            'action' => 'refreshIframe|progressbar_enabled',
            'content' => $this->POST('id')
        );
        
    }
    
    public function view_disable(){
        
        $this->toggle_progress($this->POST('id'));
        
        return array(
            'action' => 'refreshIframe|progressbar_disabled',
            'content' => $this->POST('id')
        );
        
    }
    
    public function toggle_progress($id){
        $state = ($this->DB()->get_var("SELECT disabled FROM progressbar WHERE id = '$id'") == 0) ? 1 : 0;
        $this->DB()->query("UPDATE progressbar SET disabled = '$state' WHERE id = '$id'");
    }
    
    public function delete_progress($id){
        $this->DB()->query("DELETE FROM progressbar WHERE id = '$id'");
    }
    
    public function save_table(){
        
        foreach($this->POST('id') as $k => $id){
            
            $label = $this->POST('label');
            $label = $label[$k];
            
            $percentage= $this->POST('percentage');
            $percentage = $percentage[$k];
            
            $order = $this->POST('order');
            $order = $order[$k];
            
            $disabled = $this->POST('disabled');
            $disabled = $disabled[$k];
            
            $this->add_progress(array(
                'id' => $id,
                'label' => $label,
                'percentage' => $percentage,
                'order' => $order,
                'disabled' => $disabled
            ));
            
        }
        
        return array(
            'action' => 'refreshIframe',
            'content' => 'OK'
        );
        
    }
		
		private function install(){
				
				$this->DB()->query('
          CREATE TABLE IF NOT EXISTS "progressbar" (
            "id" integer NOT NULL PRIMARY KEY,
            "label" text NOT NULL,
	    "percentage" integer NOT NULL,
            "disabled" integer NOT NULL,
            "order" integer NOT NULL
          )                   
        ');
				
	$this->installed('progressbar', true);

		}
    
}

?>