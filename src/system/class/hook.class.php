<?php lock();

class Hook_Class extends Core{
	
	private $hooks = array();
	
	function __Construct(){
		
	}
	
	public function on($mod, $event, $call, $parent = false){
		
		$i = 0;
		
		while(isset($this->hooks[$mod][$event][$i])){
			
			$i++;
			
		}
		
		if($parent){
			
			$call = array($parent, $call);
			
		}
		
		$this->hooks[$mod][$event][$i] = $call;
		
	}
	
	public function run($mod, $event, $data = false){

		if(isset($this->hooks[$mod][$event])){
			
			foreach ($this->hooks[$mod][$event] as $key => $value) {
				
				call_user_func($this->hooks[$mod][$event][$key], $data);
				
			}
			
		}
		
	}
	
}
