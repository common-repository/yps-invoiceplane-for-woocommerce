<?php

namespace YPS\Framework\Form\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;
use YPS\Framework\Core\v346_950_484\Controller;
use YPS\Framework\Core\v346_950_484\View;

class Form_Field extends Base {

	const TYPE_EMAIL			= "email";
	const TYPE_NUMERIC			= "numeric";
	const TYPE_PASSWORD			= "password";
	const TYPE_PROGRESS_BAR		= "progress-bar";
	const TYPE_SELECT			= "select";
	const TYPE_TEXT				= "text";
	const TYPE_TEXTAREA			= "textarea";

	private static $instance = null;

	protected $context;

	protected $id;
	protected $name;
	protected $label;
	protected $type;
	protected $value				= null;
	protected $default_value		= null;
	protected $placeholder			= null;
	
	protected $allow_empty;

	protected $show_label			= true;
	
	protected $group_name;
	protected $group_row_number;

	protected $is_primary_key;
	protected $is_index_key;
	protected $sql_column;

	protected $wrapper_classes;
	protected $attributes			= array();

	protected $is_table_header;
	protected $is_table_edit_url;
	protected $table_style;

	protected $disable_output;
	protected $hide;
	protected $text_before_field;
	protected $text_after_field;

	protected $is_to_be_saved;
	
	protected $full_width   = false;

	protected $add_new_button;
	protected $new_button_id;
	protected $new_button_label;
	protected $new_button_icon;
	protected $new_button_ajax_url;
	
	public function __construct($context, $params = array())
	{

		parent::__construct($context, $params);
		
		$this->is_index_key			= false;
		
		$this->is_to_be_saved		= true;
		$this->allow_empty			= true;

		return $this;
	}

	public static function get_instance()
	{
		if(self::$instance == null){   
			$c = __CLASS__;
			self::$instance = new $c;
		}
		
		return self::$instance;
	}

	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	
	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
	
		return $this;
	}

	/**
	 * Get the value of name
	 *
	 * @return mixed
	 */
	public function get_name(){
		return $this->name;
	}

	/**
	 * Set the value of the field name
	 * The name will be also used to created SQL columns
	 *
	 * @param   mixed  $name  
	 *
	 * @return  self
	 */
	public function set_name($name){
		$this->name = $name;

		return $this;
	}

	/**
	 * Get the value of label
	 *
	 * @return mixed
	 */
	public function get_label(){
		return $this->label;
	}

	public function get_label_or_name(){
		if(empty($this->label)){
			return $this->name;
		}

		return $this->label;
	}

	/**
	 * Set the value of label
	 *
	 * @param   mixed  $label  
	 *
	 * @return  self
	 */
	public function set_label($label){
		$this->label = $label;

		return $this;
	}

	/**
	 * Get the value of type
	 *
	 * @return mixed
	 */
	public function get_type(){
		return $this->type;
	}

	/**
	 * Set the value of type
	 *
	 * @param   mixed  $type  
	 *
	 * @return  self
	 */
	public function set_type($type){
		$this->type = $type;

		return $this;
	}

	/**
	 * Get the value of allow_empty
	 *
	 * 
	 * @return mixed
	 */
	public function get_allow_empty(){
		return $this->allow_empty;
	}

	/**
	 * Set the value of allow_empty
	 * If field is empty on save, an error will be shown
	 *
	 * @param   mixed  $allow_empty  
	 *
	 * @return  self
	 */
	public function set_allow_empty($allow_empty){
		$this->allow_empty = $allow_empty;

		return $this;
	}

	/**
	 * Get the value of group_name
	 *
	 * @return mixed
	 */
	public function get_group_name(){
		return $this->group_name;
	}

	/**
	 * Set the value of group_name
	 * The field will be show within this group (Group must be created)
	 * 
	 * @param   mixed  $group_name  
	 *
	 * @return  self
	 */
	public function set_group_name($group_name){
		$this->group_name = $group_name;

		return $this;
	}

	/**
	 * Get the value of group_row_number
	 * 
	 * @return mixed
	 */
	public function get_group_row_number(){
		return $this->group_row_number;
	}

	/**
	 * Set the value of group_row_number
	 * Display order of the field inside the Group
	 * 
	 * @param   mixed  $group_row_number  
	 *
	 * @return  self
	 */
	public function set_group_row_number($group_row_number){
		$this->group_row_number = $group_row_number;

		return $this;
	}

	/**
	 * Get the value of is_primary_key
	 *
	 * @return mixed
	 */
	public function get_is_primary_key(){
		return $this->is_primary_key;
	}

	/**
	 * Set the value of is_primary_key
	 * Declare if is a MySQL Primary Key (Check 'DatabaseHelper' constants)
	 * 
	 * @param   mixed  $is_primary_key  
	 *
	 * @return  self
	 */
	public function set_is_primary_key($is_primary_key){
		$this->is_primary_key = $is_primary_key;

		return $this;
	}

	
	/**
	 * Get the value of is_index_key
	 *
	 * @return mixed
	 */
	public function get_is_index_key(){
		return $this->is_index_key;
	}

	/**
	 * Set the value of is_index_key
	 *
	 * @param   mixed  $is_index_key  
	 *
	 * @return  self
	 */
	public function set_is_index_key($is_index_key){
		$this->is_index_key = $is_index_key;

		return $this;
	}

	/**
	 * Get the value of sql_column
	 *
	 * @return mixed
	 */
	public function get_sql_column(){
		return $this->sql_column;
	}

	/**
	 * Set the value of sql_column
	 * The SQL column (ex: MEDIUMTEXT NOT NULL)
	 * 
	 * @param   mixed  $sql_column  
	 *
	 * @return  self
	 */
	public function set_sql_column($sql_column){
		$this->sql_column = $sql_column;

		return $this;
	}

	/**
	 * Get the value of wrapper_classes
	 *
	 * @return mixed
	 */
	public function get_wrapper_classes(){
		return $this->wrapper_classes;
	}

	/**
	 * Set the value of wrapper_classes
	 * CSS class for the wrapper
	 * 
	 * @param   mixed  $wrapper_classes  
	 *
	 * @return  self
	 */
	public function set_wrapper_classes($wrapper_classes){
		$this->wrapper_classes = $wrapper_classes;

		return $this;
	}

	/**
	 * Get the value of attributes
	 *
	 * @return mixed
	 */
	public function get_attributes(){
		return $this->attributes;
	}

	/**
	 * Set the value of attributes
	 * HTML attributes of the field
	 * 
	 * @param   mixed  $attributes  
	 *
	 * @return  self
	 */
	public function set_attributes($attributes){
		$this->attributes = $attributes;

		return $this;
	}

	/**
	 * Add an attribute: If $attribute_name already exists, $attribute_values are merged
	 * 
	 * @param string $attribute_name
	 * @param array $attribute_values
	 * @param bool $replace If false add the attribute_value, otherwise it will be replaced
	 */
	public function add_attribute($attribute_name, $attribute_values, $replace = false){

		if(!is_array($attribute_values)){
			throw new \Exception("passed '{$attribute_name}' must be an 'array'");
		}

		if(isset($this->attributes[$attribute_name]) && $replace === false){
			$this->attributes[$attribute_name]		= array_merge($this->attributes[$attribute_name], $attribute_values);
		}else{
			$this->attributes[$attribute_name]		= $attribute_values;
		}
		
		$this->attributes[$attribute_name]			= array_unique($this->attributes[$attribute_name]);
	}

	public function remove_attribute($attribute_name, $attribute_value){

		if(isset($this->attributes[$attribute_name])){

			foreach($this->attributes[$attribute_name] as $attr_key => $attr_value){
				if($attr_value == $attribute_value){
					unset($this->attributes[$attribute_name][$attr_key]);
				}
			}
			
		}

	}

	/**
	 * Get the value of is_table_header
	 * 
	 * @return mixed
	 */
	public function get_is_table_header(){
		return $this->is_table_header;
	}

	/**
	 * Set the value of is_table_header
	 * Show the column in the list table?
	 * 
	 * @param   mixed  $is_table_header  
	 *
	 * @return  self
	 */
	public function set_is_table_header($is_table_header){
		$this->is_table_header = $is_table_header;

		return $this;
	}

	/**
	 * Get the value of is_table_edit_url
	 *
	 * @return mixed
	 */
	public function get_is_table_edit_url(){
		return $this->is_table_edit_url;
	}

	/**
	 * Set the value of is_table_edit_url
	 * If true the column in the list table is clickable
	 * 
	 * @param   mixed  $is_table_edit_url  
	 *
	 * @return  self
	 */
	public function set_is_table_edit_url($is_table_edit_url){
		$this->is_table_edit_url = $is_table_edit_url;

		return $this;
	}

	/**
	 * Get the value of table_style
	 *
	 * @return mixed
	 */
	public function get_table_style(){
		return $this->table_style;
	}

	/**
	 * Set the value of table_style
	 *
	 * @param   mixed  $table_style  
	 *
	 * @return  self
	 */
	public function set_table_style($table_style){
		$this->table_style = $table_style;

		return $this;
	}

	/**
	 * Get the value of hide
	 *
	 * @return mixed
	 */
	public function get_hide(){
		return $this->hide;
	}

	/**
	 * Set the value of hide
	 * Doesn't show the field in the edit form (Input field won't exists)
	 * 
	 * @param   mixed  $hide  
	 *
	 * @return  self
	 */
	public function set_hide($hide){
		$this->hide = $hide;

		return $this;
	}

	/**
	 * Get the value of text_before_field
	 *
	 * @return mixed
	 */
	public function get_text_before_field(){
		return $this->text_before_field;
	}

	/**
	 * Set the value of text_before_field
	 *
	 * @param   mixed  $text_before_field  
	 *
	 * @return  self
	 */
	public function set_text_before_field($text_before_field){
		$this->text_before_field = $text_before_field;

		return $this;
	}

	/**
	 * Get the value of text_after_field
	 *
	 * @return mixed
	 */
	public function get_text_after_field(){
		return $this->text_after_field;
	}

	/**
	 * Set the value of text_after_field
	 *
	 * @param   mixed  $text_after_field  
	 *
	 * @return  self
	 */
	public function set_text_after_field($text_after_field){
		$this->text_after_field = $text_after_field;

		return $this;
	}

	/**
	 * Get the value of is_to_be_saved
	 *
	 * @return mixed
	 */
	public function get_is_to_be_saved(){
		return $this->is_to_be_saved;
	}

	/**
	 * Set the value of is_to_be_saved
	 *
	 * @param   mixed  $is_to_be_saved  
	 *
	 * @return  self
	 */
	public function set_is_to_be_saved($is_to_be_saved){
		$this->is_to_be_saved = $is_to_be_saved;

		return $this;
	}

	/**
	 * Get $add_empty_button
	 *
	 * @return bool
	 */
	public function get_add_new_button(){
		return $this->add_new_button;
	}

	/**
	 * Set $add_empty_button
	 *
	 * @param   bool  $add_new_button
	 *
	 * @return  self
	 */
	public function set_add_new_button(bool $add_new_button){
		$this->add_new_button = $add_new_button;

		return $this;
	}

	/**
	 * Get the value of new_button_label
	 *
	 * @return mixed
	 */
	public function get_new_button_label(){

		if(empty($this->new_button_label)){
			return __("New...", 'yps-framework-core');
		}

		return $this->new_button_label;
	}

	/**
	 * Set the value of new_button_label
	 *
	 * @param   mixed  $new_button_label  
	 *
	 * @return  self
	 */
	public function set_new_button_label($new_button_label){
		$this->new_button_label = $new_button_label;

		return $this;
	}

	/**
	 * Get the value of new_button_icon
	 *
	 * @return mixed
	 */
	public function get_new_button_icon(){

		if(empty($this->new_button_icon)){
			return 'fas fa-plus';
		}

		return $this->new_button_icon;
	}

	/**
	 * Set the value of new_button_icon
	 *
	 * @param   mixed  $new_button_icon  
	 *
	 * @return  self
	 */
	public function set_new_button_icon($new_button_icon){
		$this->new_button_icon = $new_button_icon;

		return $this;
	}


	/**
	 * Get the value of new_button_id
	 *
	 * @return mixed
	 */
	public function get_new_button_id(){

		if(empty($this->new_button_id)){
			return "new_{$this->name}";
		}

		return $this->new_button_id;
	}

	/**
	 * Set the value of new_button_id
	 *
	 * @param   mixed  $new_button_id  
	 *
	 * @return  self
	 */
	public function set_new_button_id($new_button_id){
		$this->new_button_id = $new_button_id;

		return $this;
	}

	/**
	 * Get the value of new_button_ajax_url
	 *
	 * @return mixed
	 */
	public function get_new_button_ajax_url(){
		return $this->new_button_ajax_url;
	}

	/**
	 * Set the value of new_button_ajax_url
	 *
	 * @param   mixed  $new_button_ajax_url  
	 *
	 * @return  self
	 */
	public function set_new_button_ajax_url($new_button_ajax_url){
		$this->new_button_ajax_url = $new_button_ajax_url;

		return $this;
	}

	public function get_label_view(){
		
		$view			= new View($this->context, $this->params);

		$view->set_framework_template("Form", "form/partial/label.php");
		$view->set_view_params(array(
			'field'		=> $this
		));

		return $view;
	}

	/**
	 * Get the field view
	 */
	public function get_view($optional_params = array()){

		$view			= new View($this->context, $this->params);
		
		$view->set_view_params($this->get_default_view_params($optional_params));
		$view->set_framework_template($this->get_framework_view_module_name(), $this->get_framework_view_path());

		return $view;
	}

	public function get_default_view_params($optional_params = array()){

		$params		= array(
			'field_name'        => $this->get_name(),
			'field'             => $this,
		);

		return array_merge($params, $optional_params);
	}

	public function get_framework_view_module_name(){
		return 'Form';
	}

	public function get_framework_view_path(){
		return "form/field/{$this->get_type()}.php";
	}

	public function get_field_attributes($attribute_key = 'attributes'){

        $attributes     = $this->__get($attribute_key);

        $ret_attributes = array();

        if($attributes === null){
            return "";
        }

        foreach($attributes as $attribute_name => $properties){
            $implode_properties     = implode(" ", $properties);
            $ret_attributes[]       = "{$attribute_name}=\"{$implode_properties}\"";
        }

        return implode(" ", $ret_attributes);
    }


	/**
	 * Get the value of show_label
	 */ 
	public function get_show_label()
	{
		return $this->show_label;
	}

	/**
	 * Set the value of show_label
	 *
	 * @return  self
	 */ 
	public function set_show_label($show_label)
	{
		$this->show_label = $show_label;

		return $this;
	}

	/**
     * Get the value of full_width
     */ 
    public function get_full_width()
    {
        return $this->full_width;
    }

    /**
     * Set the value of full_width
     *
     * @return  self
     */ 
    public function set_full_width($full_width)
    {
        $this->full_width = $full_width;

        return $this;
    }

	/**
	 * Get the value of id
	 */ 
	public function get_id()
	{
		return $this->id;
	}

	/**
	 * Set the value of id
	 *
	 * @return  self
	 */ 
	public function set_id($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * Get the value of value
	 */ 
	public function get_value()
	{
		if($this->value === "" || $this->value === null){
			return $this->get_default_value();
		}
		
		return $this->value;
	}

	/**
	 * Set the value of value
	 *
	 * @return  self
	 */ 
	public function set_value($value)
	{
		$this->value = $value;

		return $this;
	}

	/**
	 * Get the value of disable_output
	 */ 
	public function get_disable_output()
	{
		return $this->disable_output;
	}

	/**
	 * Set the value of disable_output
	 *
	 * @return  self
	 */ 
	public function set_disable_output($disable_output)
	{
		$this->disable_output = $disable_output;

		return $this;
	}

	/**
	 * Get the value of default_value
	 *
	 * @return mixed
	 */
	public function get_default_value(){
		return $this->default_value;
	}

	/**
	 * Set the value of default_value
	 *
	 * @param   mixed  $default_value  
	 *
	 * @return  self
	 */
	public function set_default_value($default_value){
		$this->default_value = $default_value;

		return $this;
	}

	/**
	 * Get the value of placeholder
	 *
	 * @return mixed
	 */
	public function get_placeholder(){
		return $this->placeholder;
	}

	/**
	 * Set the value of placeholder
	 *
	 * @param   mixed  $placeholder  
	 *
	 * @return  self
	 */
	public function set_placeholder($placeholder){
		$this->placeholder = $placeholder;

		return $this;
	}
}
