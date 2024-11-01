<?php

namespace YPS\Framework\Form\v346_950_484;

use YPS\Framework\Core\v346_950_484\Controller;
use YPS\Spreadsheet_Calculator\Framework\String\String_Helper;

class Modal_Form_Field extends Form_Field {

	protected $title;
	
    protected $button							= null;
    protected $modal_view;
    
	protected $cancel_button_text;
	protected $confirm_button_text;

	protected $hash_tracking					= false;
	protected $close_on_outside_click			= false;

	protected $classes							= array();
	
    public static $record_list_footer_hooks     = array();

    public function __construct($context) {

        $this->set_type("modal");

		$default_button		= new Button_Form_Field($context);
		$default_button->set_button_text(__("Open Modal", 'yps-framework-core'));

		$this->set_button($default_button);

		$this->set_cancel_button_text(__("Cancel", 'yps-framework-core'));
		$this->set_confirm_button_text(__("OK", 'yps-framework-core'));

        parent::__construct($context);

        add_action('yps_record_list_footer', array($this, 'yps_record_list_footer'), 10);
    }

    public function yps_record_list_footer(){

        $controller         = new Controller($this->context);

        if(!in_array($this->get_name(), self::$record_list_footer_hooks)){
            echo $controller->get_framework_view('Form', "form/field/modal-content.php", array(
                'field_name'        => $this->get_name(),
                'field'             => $this,
            )); 
    
            self::$record_list_footer_hooks[]   = $this->get_name();
        }

    }

    /**
	 * Get the value of button_text
	 *
	 * @return mixed
	 */
	public function get_button(){
		return $this->button;
	}

	/**
	 * Set the value of button_text
	 *
	 * @param \YPS\Framework\Form\v346_950_484\Button_Form_Field $button
	 *
	 * @return YPS\Framework\Form\v346_950_484\Modal_Form_Field
	 */
	public function set_button($button = null){

		if($button !== null){
			$button->add_attribute("data-remodal-target", array($this->get_name()));
		}

		$this->button = $button;

		return $this;
	}

	/**
	 * Get the value of modal_view
	 *
	 * @return mixed
	 */
	public function get_modal_view(){
		return $this->modal_view;
	}

	/**
	 * Set the value of modal_view
	 *
	 * @param   mixed  $modal_view  
	 *
	 * @return  self
	 */
	public function set_modal_view($modal_view){
		$this->modal_view = $modal_view;

		return $this;
	}
    
	/**
	 * Get the value of cancel_button_text
	 *
	 * @return mixed
	 */
	public function get_cancel_button_text(){
		return $this->cancel_button_text;
	}

	/**
	 * Set the value of cancel_button_text
	 *
	 * @param   mixed  $cancel_button_text  
	 *
	 * @return  self
	 */
	public function set_cancel_button_text($cancel_button_text){
		$this->cancel_button_text = $cancel_button_text;

		return $this;
	}

	/**
	 * Get the value of confirm_button_text
	 *
	 * @return mixed
	 */
	public function get_confirm_button_text(){
		return $this->confirm_button_text;
	}

	/**
	 * Set the value of confirm_button_text
	 *
	 * @param   mixed  $confirm_button_text  
	 *
	 * @return  self
	 */
	public function set_confirm_button_text($confirm_button_text){
		$this->confirm_button_text = $confirm_button_text;

		return $this;
	}

	/**
	 * Get the value of title
	 *
	 * @return mixed
	 */
	public function get_title(){
		return $this->title;
	}

	/**
	 * Set the value of title
	 *
	 * @param   mixed  $title  
	 *
	 * @return  self
	 */
	public function set_title($title){
		$this->title = $title;

		return $this;
	}

	/**
	 * Get the value of hash_tracking
	 */ 
	public function get_hash_tracking()
	{
		return $this->hash_tracking;
	}

	/**
	 * Set the value of hash_tracking
	 *
	 * @return  self
	 */ 
	public function set_hash_tracking($hash_tracking)
	{
		$this->hash_tracking = $hash_tracking;

		return $this;
	}

	/**
	 * Get the value of close_on_outside_click
	 */ 
	public function get_close_on_outside_click()
	{
		return $this->close_on_outside_click;
	}

	/**
	 * Set the value of close_on_outside_click
	 *
	 * @return  self
	 */ 
	public function set_close_on_outside_click($close_on_outside_click)
	{
		$this->close_on_outside_click = $close_on_outside_click;

		return $this;
	}

	public function get_remodal_options(){
		$options		= array();

		$options[]	= 'hashTracking: ' . String_Helper::bool_to_string($this->hash_tracking);
		$options[]	= 'closeOnOutsideClick: ' . String_Helper::bool_to_string($this->close_on_outside_click);

		return implode(",", $options);
	}

	/**
	 * Get the value of classes
	 */ 
	public function get_classes()
	{
		return $this->classes;
	}

	/**
	 * Add the value to classes
	 *
	 * @return  self
	 */ 
	public function add_class($class)
	{
		$this->classes[] = $class;

		return $this;
	}

	/**
	 * Set the value of classes
	 *
	 * @return  self
	 */ 
	public function set_classes($classes)
	{
		$this->classes = $classes;

		return $this;
	}
}
