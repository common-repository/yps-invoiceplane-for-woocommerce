<?php

namespace YPS\Framework\Core\v346_950_484;

use YPS\Framework\Core\v346_950_484\Helper;
use YPS\Framework\File_System\v346_950_484\File_System_Helper;
use YPS\Spreadsheet_Calculator\Framework\Wordpress\Wordpress_Helper;

class Application extends Controller {

    protected $modules              = array();
    protected $enqueue_data         = array();

    public function __construct($context, $params = array()) {

        add_action('admin_init', function () {
            if(!isset($_SESSION)){
                session_start([
                    'read_and_close' => true,
                ]);
            }
        });

        add_action('template_redirect', function(){
            if(!isset($_SESSION)){
                session_start([
                    'read_and_close' => true,
                ]);
            }
        });

        parent::__construct($context, $params);

        $this->helper           = new Helper($context);

        $enqueue_contents       = file_get_contents($this->helper->get_plugin_path($this->context->get_plugin_code(), "autoload/enqueue.json"));
        $this->enqueue_data     = json_decode($enqueue_contents, true);

        add_action('plugins_loaded', array($this, 'application_plugins_loaded'));
        add_filter('cron_schedules', array($this, 'application_cron_schedules'));
        
        register_activation_hook($this->context->get_plugin_file(), array($this, 'cron_activation'));
        register_deactivation_hook($this->context->get_plugin_file(), array($this, 'cron_deactivation'));

        add_action('init', array($this, 'init'), $this->context->get_init_priority());
    }

    /**
     * Launching init hooks, functions
     */
    public function init(){    
        global $wp_rewrite;

        $wp_rewrite->flush_rules();

        add_action('wp_enqueue_scripts', array($this, 'front_enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

        add_action("wp_ajax_{$this->context->get_plugin_code()}_ajax_callback", array($this, 'ajax_callback'));
        add_action("wp_ajax_nopriv_{$this->context->get_plugin_code()}_ajax_callback", array($this, 'ajax_callback'));

        add_action('admin_menu', array( $this, 'application_admin_menu'),99);

        add_action("{$this->context->get_plugin_id()}_cron_every_minute", array($this, 'cron_every_minute'));
        add_action("{$this->context->get_plugin_id()}_cron_every_10_minutes", array($this, 'cron_every_10_minutes'));
        add_action("{$this->context->get_plugin_id()}_cron_every_hour", array($this, 'cron_every_hour'));
        add_action("{$this->context->get_plugin_id()}_cron_every_day", array($this, 'cron_every_day'));

        add_filter('script_loader_tag', array($this, 'add_script_defer_attribute'), 10, 2);
        add_filter('script_loader_tag', array($this, 'add_script_async_attribute'), 10, 2);

        $this->upgrade();

        foreach($this->modules as $module){

            $controller     = $module->get_controller();

            new $controller($this->context, array(
                'raw'               => true,
            ));
        }
        
    }

    function application_plugins_loaded(){
        
        /* Loading translation files if exist */
        load_plugin_textdomain($this->context->get_plugin_code(), FALSE, "{$this->context->get_plugin_code()}/lib/yps-framework-core/includes/../../../lang/");
        load_plugin_textdomain('yps-framework-core', FALSE, "{$this->context->get_plugin_code()}/lib/yps-framework-core/lang/");
    }

    /**
     * It will call the relative controller action for ajax calls.
     */
    public function ajax_callback(){

        $controller_name   = $this->helper->get_request("controller");
        $controller_name   = $this->translate_controller_name($controller_name);
        
        $controller_name   = "{$this->context->get_plugin_namespace()}\\{$controller_name}_Controller";

        $action_name       = $this->helper->get_request("action_name");
        $action_name       = $this->translate_action_name($action_name);
        $action_name       = "{$action_name}_action";
        
        $controller         = new $controller_name($this->context, array(
            'raw'               => true,
        ));

        $controller->{$action_name}();
    }

    private function is_yps_framework_script($script){
        return (substr($script['script_name'], 0, strlen("yps-framework")) === "yps-framework");
    }

    private function is_yps_environment($hook_suffix = null){
        return ($hook_suffix == "yourplugins_page_{$this->context->get_plugin_code()}" || $hook_suffix == "toplevel_page_yourplugins");
    }
    /**
     * @param string $type = admin|front
     * @param string $script_type = css|js
     * @param array $script
     */
    public function get_enqueue_script_rule_result($type, $script_type, $script, $hook_suffix = null){

        $is_yps_framework_script    = $this->is_yps_framework_script($script);
        $is_yps_environment         = $this->is_yps_environment($hook_suffix);
        $ret                        = false;

        if($type == "admin"){
            if($is_yps_environment == true){
                $ret    = true;
            }
        }

        if($type == "front"){
            $ret    = true;
        }

        return apply_filters('yps_framework_core_get_enqueue_script_rule_result', $ret, $type, $script_type, $script, $hook_suffix);
    }

    /**
     * @param string $type = admin|front
     */
    private function enqueue_app_scripts($type, $hook_suffix = null){

        foreach(array('css', 'js') as $script_type){
            if(isset($this->enqueue_data[$type][$script_type])){
                foreach($this->enqueue_data[$type][$script_type] as $script){
                    if($this->get_enqueue_script_rule_result($type, $script_type, $script, $hook_suffix) == true){
                        $this->enqueue_app_script($type, $script_type, $script);
                    }
                }
            }
        }

    }

    private function enqueue_app_script($type, $script_type, $script){

        if($script_type == 'css'){
            $this->helper->enqueue_style($script['script_name'], $this->context->get_plugin_code(), $script['file'], $script['version']);
        }

        if($script_type == 'js'){
            $this->helper->enqueue_script($script['script_name'], $this->context->get_plugin_code(), $script['file'], $script['deps'], $script['version']);
        }

        /* Load also any related dependency to avoid loading each one manually */
        if(isset($script['deps'])){
            foreach($script['deps'] as $script_dependency){
                foreach(array('css', 'js') as $dependency_script_type){
                    if(isset($this->enqueue_data[$type][$dependency_script_type][$script_dependency])){
                        $this->enqueue_app_script($type, $dependency_script_type, $this->enqueue_data[$type][$dependency_script_type][$script_dependency]);
                    }
                }
            }
        }

    }

    /**
     * Loading styles and scripts only on wp-admin pages.
     *
     * @param string $hook_suffix
     * @return void
     */
    function admin_enqueue_scripts($hook_suffix){
        
        /* Loading Wordpress Media Library files */
        wp_enqueue_media();
    
        $this->enqueue_app_scripts("admin", $hook_suffix);

        foreach($this->modules as $module){
            $this->enqueue_scripts($module, $module->get_admin_scripts(), $module->get_admin_styles());
        }

        $this->localize_scripts();
    }

    /**
     * Loading styles and scripts only on wp front-end.
     *
     * @param string $hook_suffix
     * @return void
     */
    function front_enqueue_scripts($hook_suffix){

        $this->enqueue_app_scripts("front", $hook_suffix);

        foreach($this->modules as $module){
            $this->enqueue_scripts($module, $module->get_front_scripts(), $module->get_front_styles());
        }

        $this->localize_scripts();
    }
    
    private function localize_scripts(){

        Helper::localize_script("yps-framework-core-loader", 'YPS_FRAMEWORK_CORE_LOADER', array(
            'loader_image'         => apply_filters('yps_framework_core/loader_image', Helper::get_framework_url($this->context->get_plugin_code(), "framework-core", "media/spinner.gif")),
        ));

        Helper::localize_script("datatables-jquery-datatables", 'YPS_DATA_TABLES', array(
            'lang'                              => array(
                "sEmptyTable"       => __('No record has been found', 'yps-framework-core'),
                "sInfo"             => __('Showing _START_/_END_ of _TOTAL_ records', 'yps-framework-core'),
                "sInfoEmpty"        => __('Showing 0/0 of 0 records', 'yps-framework-core'),
                "sInfoFiltered"     => __('(filtered _MAX_ records)', 'yps-framework-core'),
                "sInfoPostFix"      => "",
                "sInfoThousands"    => ",",
                "sLengthMenu"       => __('Show _MENU_ records per page', 'yps-framework-core'),
                "sLoadingRecords"   => __('Loading...', 'yps-framework-core'),
                "sProcessing"       => __('Loading...', 'yps-framework-core'), 
                "sSearch"           => __('Search', 'yps-framework-core'),
                "sZeroRecords"      => __('No records has been found', 'yps-framework-core'),
                "oPaginate" => array(
                    "sFirst"        => __('First', 'yps-framework-core'), 
                    "sLast"         => __('Last', 'yps-framework-core'), 
                    "sNext"         => __('Next', 'yps-framework-core'),
                    "sPrevious"     => __('Previous', 'yps-framework-core'),
                ),
                "oAria" => array(
                    "sSortAscending"    => __(': enable sorting by ascending order', 'yps-framework-core'),
                    "sSortDescending"   =>  __(': enable sorting by descending order', 'yps-framework-core')
                )
            ),
        ));
    }

    private function enqueue_scripts($module, $scripts, $styles){

        foreach($scripts as $handle => $module_script){
            $deps           = (isset($module_script['deps'])?$module_script['deps']:array());
            $localize       = (isset($module_script['localize'])?$module_script['localize']:array());

            $module->enqueue_script($handle, $module_script['file'], $deps, $localize);
        }

        foreach($styles as $handle => $file){
            $module->enqueue_style($handle, $file);
        }

    }
    
    public function upgrade_modules(){
        foreach($this->modules as $module){
            foreach($module->init_models as $model_name){
                $model      = new $model_name();
                $model->create_table();
            }
        }
    }

    public function upgrade_models(){
        foreach($this->get_custom_upgrade_models() as $model_name){
            $model      = new $model_name($this->context, $this->params);
            $model->create_table();
        }
    }

    public function upgrade_create_folders(){
        foreach($this->get_custom_upgrade_create_folders() as $path){
            File_System_Helper::create_folder($path);
        }
    }

    public function get_custom_upgrade_create_folders(){
        return array();
    }

    public function get_custom_upgrade_models(){
        return array();
    }

    public function custom_upgrade(){

    }

    public function upgrade(){
        if(get_option($this->context->get_plugin_code()) != $this->context->get_plugin_version()){
        
            $this->upgrade_modules();
            $this->upgrade_models();
            $this->upgrade_create_folders();
            
            $this->custom_upgrade();

            update_option($this->context->get_plugin_code(), $this->context->get_plugin_version());
        }


    }
    
    public function application_cron_schedules($schedules){

        if(!isset($schedules["yps_every_minute"])){
            $schedules["yps_every_minute"] = array(
                'interval'      => 60,
                'display'       => __('Every 1 minute'));
        }

        if(!isset($schedules["yps_every_10_minutes"])){
            $schedules["yps_every_10_minutes"] = array(
                'interval'      => 600,
                'display'       => __('Every 10 minutes'));
        }
        
        if(!isset($schedules["yps_every_hour"])){
            $schedules["yps_every_hour"] = array(
                'interval'      => 3600,
                'display'       => __('Every hour'));
        }
        
        if(!isset($schedules["yps_every_day"])){
            $schedules["yps_every_day"] = array(
                'interval'      => 86400,
                'display'       => __('Every 24 hours'));
        }

        return $schedules;
    }
    
    function cron_activation() {

        if(!wp_next_scheduled("{$this->context->get_plugin_id()}_cron_every_minute")){
            wp_schedule_event(time(), 'yps_every_minute', "{$this->context->get_plugin_id()}_cron_every_minute");
        }

        if(!wp_next_scheduled("{$this->context->get_plugin_id()}_cron_every_10_minutes")){
            wp_schedule_event(time(), 'yps_every_10_minutes', "{$this->context->get_plugin_id()}_cron_every_10_minutes");
        }
        
        if(!wp_next_scheduled("{$this->context->get_plugin_id()}_cron_every_hour")){
            wp_schedule_event(time(), 'yps_every_hour', "{$this->context->get_plugin_id()}_cron_every_hour");
        }

        if(!wp_next_scheduled("{$this->context->get_plugin_id()}_cron_every_day")){
            wp_schedule_event(time(), 'yps_every_day', "{$this->context->get_plugin_id()}_cron_every_day");
        }

    }

    function cron_deactivation(){
        wp_clear_scheduled_hook("{$this->context->get_plugin_id()}_cron_every_minute");
        wp_clear_scheduled_hook("{$this->context->get_plugin_id()}_cron_every_10_minutes");
        wp_clear_scheduled_hook("{$this->context->get_plugin_id()}_cron_every_hour");
        wp_clear_scheduled_hook("{$this->context->get_plugin_id()}_cron_every_day");
    }
    
    public function cron_every_day(){}
    public function cron_every_minute(){}
    public function cron_every_10_minutes(){}
    public function cron_every_hour(){}
    
    function add_script_defer_attribute($tag, $handle) {

        $scripts_to_defer = apply_filters('yps_script_defer_handles', array());

        foreach($scripts_to_defer as $defer_script) {
            if ($defer_script === $handle) {
                return str_replace(' src', ' defer="defer" src', $tag);
            }
        }
        return $tag;
    }
    
    function add_script_async_attribute($tag, $handle) {

        $scripts_to_async = apply_filters('yps_script_async_handles', array());

        foreach($scripts_to_async as $async_script) {
            if ($async_script === $handle) {
                return str_replace(' src', ' async="async" src', $tag);
            }
        }
        return $tag;
    }
    
    public function add_module($module){
        $this->modules[]            = $module;
    }

    public function get_modules(){
        return $this->modules;
    }
    
    public function application_admin_menu(){
        foreach($this->modules as $module){
            add_submenu_page(
                $this->context->get_plugin_code(),
                $module->get_name(),
                $module->get_name(),
                'read',
                Url_Helper::parse_query_url($module->get_menu_url(), true),
                array($this, 'submenu_callback')
            );
        }
    }
    
    public function submenu_callback(){
        foreach($this->modules as $module){
            $controller     = $module->get_controller();

            new $controller($this->context);
        }

        /**
         *         foreach($controllers as $controller_name => $controller_options){
            if(isset($controller_options['permissions'])){
                if(current_user_can($controller_options['permissions'])){
                    new $controller_name(array('plugin_code' => $this->context->get_plugin_code()));
                }
            }else{
                new $controller_name(array('plugin_code' => $this->context->get_plugin_code()));
            }
        }
            */
    }

	/**
	 * Get the value of enqueue_data
	 *
	 * @return mixed
	 */
	public function get_enqueue_data(){
		return $this->enqueue_data;
	}

	/**
	 * Set the value of enqueue_data
	 *
	 * @param   mixed  $enqueue_data  
	 *
	 * @return  self
	 */
	public function set_enqueue_data($enqueue_data){
		$this->enqueue_data = $enqueue_data;

		return $this;
	}
}
