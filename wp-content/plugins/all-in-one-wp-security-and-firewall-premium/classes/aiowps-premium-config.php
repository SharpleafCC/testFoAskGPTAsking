<?php
class AIOWPS_Premium_Config{
    public $configs;
    public $message_stack;
    static $_this;
    
    public function __construct(){
        $this->message_stack = new stdClass();
    }

    public function load_config() {
	    $this->configs = get_site_option('aiowps_premium_configs');
    }
	
    public function get_value($key){
    	return isset($this->configs[$key])?$this->configs[$key] : '';    	
    }
    
    public function set_value($key, $value){
    	$this->configs[$key] = $value;
    }
    
    public function add_value($key, $value){
        if(!is_array($this->configs)){$this->configs = array();}
    	if (array_key_exists($key, $this->configs)){
            //Don't update the value for this key
    	}
    	else{//It is safe to update the value for this key
            $this->configs[$key] = $value;
    	}    	
    }

    public function save_config() {
    	update_site_option('aiowps_premium_configs', $this->configs);
    }

    /**
     * Delete all config option.
     */
    public function delete_config(){
        delete_site_option('aiowps_premium_configs');
    }

    public function get_stacked_message($key){
        if(isset($this->message_stack->{$key}))
            return $this->message_stack->{$key};
        return "";
    }
    
    public function set_stacked_message($key,$value){
        $this->message_stack->{$key} = $value;
    }
    
    public static function get_instance(){
    	if(empty(self::$_this)){
            self::$_this = new self();
            self::$_this->load_config();
            return self::$_this;
    	}
    	return self::$_this;
    }
}
