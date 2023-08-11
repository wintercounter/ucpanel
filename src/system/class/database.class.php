<?php lock();

class Database_Class extends Core{
	
	private $module_path;
	
	function __Construct(){
		
		$function = $this->config('db_type');
		$this->module_path = $this->config('full_path').$this->config('system_dir').'/module/ezsql/'.$function.'/';
		$this->module('ezsql',FALSE, FALSE);
		require_once $this->module_path."ez_sql_".$function.".php";
		if($function == 'mysql' || $function === '')
			$function = 'sql';
		$this->$function();
	}

	public function mssql(){
		if(!$this->config('db_inited'))
			$this->write_config('db_inited',new ezSQL_mssql($this->config('db_username'),$this->config('db_password'),$this->config('db_name'),$this->config('db_hostname')));
	}
	public function sql(){
		if(!$this->config('db_inited'))
			$this->write_config('db_inited',new ezSQL_mysql($this->config('db_username'),$this->config('db_password'),$this->config('db_name'),$this->config('db_hostname'),324356));
	}
	public function oracle(){ // not fully implemented
		if(!$this->config('db_inited'))
			$this->write_config('db_inited',new ezSQL_oracle8_9('user','password','oracle.instance'));
	}
	public function pdo(){
		if(!$this->config('db_inited'))
			$this->write_config('db_inited',new ezSQL_pdo('sqlite:' . $this->config('db_path') . $this->config('db_name') . '.db', 'false', 'false'));
	}
	public function postgresql(){
		if(!$this->config('db_inited'))
			$this->write_config('db_inited',new ezSQL_postgresql($this->config('db_username'),$this->config('db_password'),$this->config('db_name'),$this->config('db_hostname')));
	}
	public function sqlite(){
		if(!$this->config('db_inited'))
			$this->write_config('db_inited',new ezSQL_sqlite($this->config('db_path'),$this->config('db_name').'.db'));
	}
} // END CLASS
	
?>