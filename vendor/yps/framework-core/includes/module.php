<?php

namespace YPS\Framework\Core\v346_950_484;

class Module  {

    public $config                      = null;
    public $controller                  = null;

    public $code                        = null;
    public $name                        = null;

    public $menu_enabled                = false;
    public $menu_config_entrypoint      = "";

    public $admin_scripts               = array();
    public $admin_styles                = array();

    public $front_scripts               = array();
    public $front_styles                = array();

    public $init_models                 = array();
    public $init_folders                = array();

    public $submenu                     = false;
    public $menu_icon                   = null;
    public $menu_position               = 10;
    public $menu_parent_slug            = null;

    public function __construct($params = array()){
        foreach($params as $param_key => $param_value){
            $this->{$param_key}     = $param_value;
        }

        if(empty($this->code)){
            throw new \Exception("Module 'code' not set");
        }

    }

    public function get_admin_styles(){
        return $this->admin_styles;
    }

    public function get_admin_scripts(){
        return $this->admin_scripts;
    }

    public function get_front_styles(){
        return $this->front_styles;
    }

    public function get_front_scripts(){
        return $this->front_scripts;
    }

    public function get_name(){
        return $this->name;
    }

    public function get_config(){
        return $this->config;
    }

    public function get_controller(){
        return $this->controller;
    }

    public function set_config($config){
        $this->config       = $config;
    }

    public function set_controller($controller){
        $this->controller   = $controller;
    }

    public function get_menu_enabled(){
        return $this->menu_enabled;
    }

    public function set_menu_enabled($menu_enabled){
        $this->menu_enabled     = $menu_enabled;
    }

    public function set_menu_config_entrypoint($config_function){
        $this->menu_config_entrypoint         = $config_function;
    }

    public function enqueue_script($handle, $file, $deps = array(), $localize = array()){
        Helper::enqueue_script($handle, Helper::get_code(), "{$this->code}/{$file}", Helper::get_version(), $deps);

        if(count($localize) != 0){
            Helper::localize_script($handle, strtoupper($handle), $localize);
        }
    }

    public function enqueue_style($handle, $file){
        Helper::enqueue_style($handle, Helper::get_code(), $file, Helper::get_version());
    }


    public function get_code(){
        return $this->code;
    }

    public function set_code($code){
        $this->code     = $code;
    }

    public function get_menu_url(){
        return $this->config->{$this->menu_config_entrypoint}();
    }
    
    public function get_submenu(){
        return $this->submenu;
    }

    public function set_submenu($submenu){
        $this->submenu  = $submenu;
    }

    public function get_menu_icon(){
        return $this->menu_icon;
    }

    public function set_menu_icon($menu_icon){
        $this->menu_icon    = $menu_icon;
    }

    /**
     * Get the value of menu_position
     *
     * @return mixed
     */
    public function get_menu_position(){
        return $this->menu_position;
    }

    /**
     * Set the value of menu_position
     *
     * @param   mixed  $menu_position  
     *
     * @return  self
     */
    public function set_menu_position($menu_position){
        $this->menu_position = $menu_position;

        return $this;
    }

    /**
     * Get the value of menu_parent_slug
     *
     * @return mixed
     */
    public function get_menu_parent_slug(){

        if(empty($this->menu_parent_slug)){
            return $this->code;
        }

        return $this->menu_parent_slug;
    }

    /**
     * Set the value of menu_parent_slug
     *
     * @param   mixed  $menu_parent_slug  
     *
     * @return  self
     */
    public function set_menu_parent_slug($menu_parent_slug){
        $this->menu_parent_slug = $menu_parent_slug;

        return $this;
    }
    
}


