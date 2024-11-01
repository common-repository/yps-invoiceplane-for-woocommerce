<?php

namespace YPS\Framework\Core\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class Config extends Base {

    protected $controller_name;
    protected $controller_page;
	
	/**
	 * Get the value of controller_name
	 *
	 * @return mixed
	 */
	public function get_controller_name(){
		return $this->controller_name;
	}

	/**
	 * Set the value of controller_name
	 *
	 * @param   mixed  $controller_name  
	 *
	 * @return  self
	 */
	public function set_controller_name($controller_name){

		if (!preg_match("/^[a-z-']*$/", $controller_name)){
			throw new \Exception("Only a-z and - characters are recognized as controller_name");
		}

		$this->controller_name = $controller_name;

		return $this;
	}


	/**
	 * Get the value of controller_page
	 *
	 * @return mixed
	 */
	public function get_controller_page(){
		
		if(empty($this->controller_page)){
			return $this->context->get_plugin_code();
		}

		return $this->controller_page;
	}

	/**
	 * Set the value of controller_page
	 *
	 * @param   mixed  $controller_page  
	 *
	 * @return  self
	 */
	public function set_controller_page($controller_page){
		$this->controller_page = $controller_page;

		return $this;
	}
}
