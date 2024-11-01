<?php

namespace YPS\Framework\Core\v346_950_484;

use YPS\Framework\Core\v346_950_484\Helper;
use YPS\Framework\String\v346_950_484\String_Helper;

class Context {

	protected $plugin_name;
    protected $plugin_code;
    protected $plugin_file;
    protected $plugin_namespace;
    protected $plugin_version;

	protected $composer_data;
	protected $framework_mapping_data;
	protected $version_data;

	protected $plugin_has_license_activation	= false;

	protected $plugin_dependencies  = array();

	protected $init_priority		= 10;
	
    protected $classes          	= array();
    
    protected $helper;

    public function __construct() {
        $this->helper       = new Helper($this);
    }

	public function init_framework(){
		$composer_data			= file_get_contents($this->helper->get_plugin_path($this->get_plugin_code(), "composer.json"));
		$framework_mapping_data	= file_get_contents($this->helper->get_plugin_path($this->get_plugin_code(), "autoload/framework_mapping.json"));
		$version_data			= file_get_contents($this->helper->get_plugin_path($this->get_plugin_code(), "autoload/version.json"));

		$this->composer_data					= json_decode($composer_data, true);
		$this->framework_mapping_data			= json_decode($framework_mapping_data, true);
		$this->version_data						= json_decode($version_data, true);

		foreach($this->framework_mapping_data as $framework_composer_name => $framework_name){
			$framework_version		= $this->version_data[$framework_composer_name];

			$this->add_framework($framework_name, $framework_version);
		}

		$this->init_missing_dependecies_messages();
    }

	public function get_composer_data(){
		return $this->composer_data;
	}

	public function get_framework_mapping_data(){
		return $this->framework_mapping_data;
	}

	public function get_version_data(){
		return $this->version_data;
	}

    public function add_framework($framework_name, $framework_version){

        $this->classes[$framework_name]        = array(
            'framework_name'        => $framework_name,
            'framework_version'     => $framework_version,
            'framework_folder'      => self::get_framework_folder_from_name($framework_name)
        );
    }

	public function get_framework_data($framework_name, $data_key){
		$framework		= $this->get_framework($framework_name);

		return $framework[$data_key];
	}

	public function get_framework($framework_name){

		if(!isset($this->classes[$framework_name])){
			throw new \Exception("The framework {$framework_name} has not been found");
		}

		return $this->classes[$framework_name];
	}

	public function get_frameworks(){
		return $this->classes;
	}

    public function get_framework_class($framework_name, $framework_class, $arguments = array()){

        $class          = $this->get_framework($framework_name);
        $class_version  = str_replace(".", "_", $class['framework_version']);
        $class_string   = "\\YPS\\Framework\\{$framework_name}\\v{$class_version}\\{$framework_class}";

        $arguments      = array_merge(array($this), $arguments);

        $refl_class     = new \ReflectionClass($class_string);
        $instance       = $refl_class->newInstanceArgs($arguments);

        return $instance;
    }

	public function enqueue_framework_script($framework_name, $script_name, $deps = array()){

		$framework_data		= $this->get_framework($framework_name);
		$script_filename	= strtolower($script_name);
		$script_filename	= str_replace("_", "-", $script_filename);

        $this->helper->enqueue_script("YPS_Framework_{$script_name}", $this->get_plugin_code(), "vendor/yps/{$framework_data['framework_folder']}/js/{$script_filename}.js", $deps, $framework_data['framework_version']);
	}

	/**
	 * Enqueue a framework css style
	 * 
	 * @param $framework_name For example "Core", "Plugin", etc
	 * @param $style_name Only the filename without ".css" and without relative path (For example "style-admin")
	 */
	public function enqueue_framework_style($framework_name, $style_name){

		$framework_data		= $this->get_framework($framework_name);
		$style_filename	= strtolower($style_name);
		$style_filename	= str_replace("_", "-", $style_filename);

        $this->helper->enqueue_style("YPS_Framework_{$style_name}", $this->get_plugin_code(), "vendor/yps/{$framework_data['framework_folder']}/css/{$style_filename}.css", $framework_data['framework_version']);
	}

	public function get_framework_url($framework_name, $rel_url){
		$framework_data		= $this->get_framework($framework_name);
		
		return $this->helper->get_framework_url($this->get_plugin_code(), $framework_data['framework_folder'], $rel_url);
	}

	public function get_framework_path($framework_name, $rel_path){
        $lib_name       = $this->get_framework_data($framework_name, 'framework_folder');

        return $this->helper->get_plugin_path($this->get_plugin_code(), "vendor/yps/{$lib_name}/{$rel_path}");
    }

	public static function get_framework_folder_from_name($framework_name){
		$framework_folder		= strtolower("framework-{$framework_name}");
		$framework_folder		= str_replace("_", "-", $framework_folder);

		return $framework_folder;
	}

    public function get_plugin_id(){
        return str_replace("-", "_", $this->plugin_code);
    }

	/**
	 * Get the value of plugin_code
	 *
	 * @return mixed
	 */
	public function get_plugin_code(){
		return $this->plugin_code;
	}

	/**
	 * Set the value of plugin_code
	 *
	 * @param   mixed  $plugin_code  
	 *
	 * @return  self
	 */
	public function set_plugin_code($plugin_code){
		$this->plugin_code = $plugin_code;

		return $this;
	}

	/**
	 * Get the value of plugin_file
	 *
	 * @return mixed
	 */
	public function get_plugin_file(){
		return $this->plugin_file;
	}

	/**
	 * Set the value of plugin_file
	 *
	 * @param   mixed  $plugin_file  
	 *
	 * @return  self
	 */
	public function set_plugin_file($plugin_file){
		$this->plugin_file = $plugin_file;

		return $this;
	}

	/**
	 * Get the value of plugin_namespace
	 *
	 * @return mixed
	 */
	public function get_plugin_namespace(){
		return $this->plugin_namespace;
	}

	/**
	 * Set the value of plugin_namespace
	 *
	 * @param   mixed  $plugin_namespace  
	 *
	 * @return  self
	 */
	public function set_plugin_namespace($plugin_namespace){
		$this->plugin_namespace = $plugin_namespace;

		return $this;
	}

	/**
	 * Get the value of plugin_version
	 *
	 * @return mixed
	 */
	public function get_plugin_version(){

        /* Genera un numero di versione randomico per test, cache, ecc */
        if($this->helper->get_constant_value("YPS_TEST") == true){
            return String_Helper::generate_random(10);
        }

		return $this->plugin_version;
	}

	/**
	 * Set the value of plugin_version
	 *
	 * @param   mixed  $plugin_version  
	 *
	 * @return  self
	 */
	public function set_plugin_version($plugin_version){
		$this->plugin_version = $plugin_version;

		return $this;
	}

	/**
	 * Get the value of init_priority
	 *
	 * @return mixed
	 */
	public function get_init_priority(){
		return $this->init_priority;
	}

	/**
	 * Set the value of init_priority
	 *
	 * @param   mixed  $init_priority  
	 *
	 * @return  self
	 */
	public function set_init_priority($init_priority){
		$this->init_priority = $init_priority;

		return $this;
	}

	public function get_hook_suffix(){
		return "yourplugins_page_{$this->get_plugin_code()}";
	}
	
	public function get_plugin_dependencies(){
		return $this->plugin_dependencies;
	}

    public function get_plugin_dependency_status($plugin_code){
        $plugin_info    = $this->plugin_dependencies[$plugin_code];
		
		if(strpos($plugin_info['status_callback'], "is_yps_plugin_activate") !== false){
			return $plugin_info['status_callback']($plugin_code);
		}

        return $plugin_info['status_callback']();
    }

    public function add_plugin_dependency($plugin_code, $plugin_label, $status_callback){
        $this->plugin_dependencies[$plugin_code]    = array(
			'label'				=> $plugin_label,
			'status_callback'	=> $status_callback
		);
    }

	public function init_missing_dependecies_messages(){
		add_action('admin_notices', function(){

			/* Show a message if dependencies are not satisfied */
			foreach($this->get_plugin_dependencies() as $plugin_code => $plugin_info){
				if($this->get_plugin_dependency_status($plugin_code) === false){
					?>
					<div class="notice notice-warning">
						<p><b><?php echo $this->get_plugin_name() ?></b>: It needs <b>'<?php echo $plugin_info['label'] ?>'</b> in order to work correctly. Please install and activate it.</p>
					</div>
					<?php
				}
			}
	
		}, 10);
	}

	public function get_plugin_basename(){
		return "{$this->get_plugin_code()}/{$this->get_plugin_code()}.php";
	}

	public static function is_yps_plugin_activate($plugin_code){

		if (!function_exists( 'is_plugin_active' )){
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		
		return \is_plugin_active("{$plugin_code}/{$plugin_code}.php");
	}

	/**
	 * Get the value of plugin_has_license_activation
	 *
	 * @return mixed
	 */
	public function get_plugin_has_license_activation(){
		return $this->plugin_has_license_activation;
	}

	/**
	 * Set the value of plugin_has_license_activation
	 *
	 * @param   mixed  $plugin_has_license_activation  
	 *
	 * @return  self
	 */
	public function set_plugin_has_license_activation($plugin_has_license_activation){
		$this->plugin_has_license_activation = $plugin_has_license_activation;

		return $this;
	}

	/**
	 * Get the value of plugin_name
	 *
	 * @return mixed
	 */
	public function get_plugin_name(){
		return $this->plugin_name;
	}

	/**
	 * Set the value of plugin_name
	 *
	 * @param   mixed  $plugin_name  
	 *
	 * @return  self
	 */
	public function set_plugin_name($plugin_name){
		$this->plugin_name = $plugin_name;

		return $this;
	}
}
