<?php lock();

class Panel_Class extends Core{
	
	public $menu = array();
    
    function __Construct(){
        
        // Keep-alive user
        if($this->COOKIE('logged_in') && $this->COOKIE('logged_hash') && $this->COOKIE('logged_uname')){
                $this->login($this->COOKIE('logged_uname'),$this->COOKIE('logged_hash'),1);
        }
        
    }
    
    public function login($username = false, $password = false, $just_update = 0){
	
	$result = array();
			
        $enc = 'sha1';

        // Quit if one of the datas aren't exists
        if($username && $password){

                $result['username'] = $this->pref('username');
                $result['email'] = $this->pref('email');
                $result['password'] = $this->pref('password');

                if($just_update==0){
                        
                        $pass = $this->do_hash($enc($this->POST('password')));
                }
                else{
                        $pass = $password;
                }
                
                if($pass == $this->do_hash($result['password']) && $username == $result['username']){

                        $this->set_cookie('logged_in', 96000, '1', 1);
                        $this->set_cookie('logged_hash', 96000, $pass, 1);
                        $this->set_cookie('logged_uname', 96000, $result['username'], 1);

                        $this->write_config('logged_in', TRUE);
                        
                        $result['user_ip'] = $_SERVER['REMOTE_ADDR'];
                        
                        $this->user_data = $result;
                        
                }
        }
        
        return $this->config('logged_in');

    }
    
    public function logged_in(){
        return $this->config('logged_in');
    }
	
	public function add_menu($array){
		
		$proto = array(
			'title' => '',
			'count' => 0,
			'target' => '#nogo',
			'icon' => '',
			'submenu' => array()
		);
			
		foreach($array as $key => $value){
			
			$value = $this->param_mount($proto, $value);
			
			if(isset($this->menu[$key]['submenu']) && is_array($this->menu[$key]['submenu'])){
				
				$value['submenu'] = array_merge($value['submenu'], $this->menu[$key]['submenu']);
				
			}
			
			$this->menu[$key] = $value;
			
		}
	
	}
	
	public function add_sub_menu($to, $array){
		
		foreach($array as $key => $item){
		
			$proto = array(
				'title' => '',
				'count' => 0,
				'target' => '#nogo'
			);
			
			$item = $this->param_mount($proto, $item);
				
			if(!$this->menu[$to]['submenu']){

				$this->menu[$to]['submenu'] = array();
				
			}
			
			$this->menu[$to]['submenu'][$key] = $item;
			
		}
		
	}
	
	private function do_hash($pass){
		
                $result['email'] = $this->pref('email');
                $result['password'] = $this->pref('password');
		
		$enc = 'sha1';
		
		return $enc('You_are_shit_'.$enc($result['email'].$enc($pass).$result['email']));
		
	}
    
}

?>