<?php

namespace YPS\Framework\Wordpress\v346_950_484;

use YPS\Framework\Core\v346_950_484\Helper;

class Wordpress_Helper {

    protected $context;
    protected $params;

    public function __construct($context, $params = array()) {

        $this->context      = $context;
        $this->params       = $params;

        $this->helper       = $context->get_framework_class('Core', 'Helper');

    }
    
    public static function get_current_user_email(){
        $current_user       = \wp_get_current_user();
        $email              = $current_user->user_email; 

        return $email;  
    } 
    
    /**
     * wp-config.php must be set as following:
     * 
     * define( 'WP_DEBUG', true );
     * define( 'WP_DEBUG_DISPLAY', false );
     * define( 'WP_DEBUG_LOG', true );
     * 
     * @param type $log
     */
    public static function write_log($context, $log = "", $filename = ""){
        
        $helper             = new Helper($context);
        
        if (defined('YPS_DEBUG_LOG')){
            if(YPS_DEBUG_LOG == true){
                $debug              = debug_backtrace()[1];
                $function_name      = $debug['function'];
                $class_name         = $debug['class'];
                $date               = date('Y-m-d H:i:s');
                
                $debug_file_path    = "{$helper->get_site_path()}/wp-content/debug-{$filename}.log";
    
                if (is_array($log) || is_object($log)){
                    $value          = print_r($log, true);
                }else{
                    $value          = $log;
                }
                
                if(empty($filename)){
                    error_log("{$date} {$class_name}->{$function_name}###########\t{$value}");
                }else{
                    error_log("{$date} {$class_name}->{$function_name}###########\t{$value}\n", 3, $debug_file_path);
                }
            }

        }
    }
    
    /**
     * 
     * @return WP_User
     */
    public static function get_administrator_users(){
        return get_users(array(
                'role__in'      => array(
                    'administrator',
                ),
                'orderby'       => 'user_nicename',
                'order'         => 'ASC'
        ));
    }

    public static function send_email_to_admin($subject, $body){
        $admin_email    = get_bloginfo('admin_email');
        $headers        = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($admin_email, $subject, $body, $headers);
    }
        
    public static function set_new_blog($id_default_blog, $blog_id){


        
        global $wpdb;

        $database_helper        = new Database_Helper();
        $copy_users             = get_option('wpmuclone_copy_users');
        
        $tables                 = array();
        $new_tables             = array();
        
        if(!$id_default_blog){ 
            return false; 
        }

        $old_url = get_site_url($id_default_blog);

        switch_to_blog($blog_id);

        $new_url 		= get_site_url();
        $new_name 		= get_bloginfo('title','raw');
        $admin_email        = get_bloginfo('admin_email','raw');

        $prefix             = $wpdb->base_prefix;
        $prefix_escaped     = str_replace('_', '\_', $prefix);

        //List all tables for the default blog,
        $tables_q           = $wpdb->get_results("SHOW TABLES LIKE '{$prefix_escaped}{$id_default_blog}\_%'");

        foreach($tables_q as $table){
            $in_array           = get_object_vars($table);
            $old_table_name     = current($in_array);
            $tables[]           = str_replace("{$prefix}{$id_default_blog}_", '', $old_table_name);
            unset($in_array);
        }

        //Replace tables from the new blog with the ones from the default blog
        foreach($tables as $table){
            $new_table = "{$prefix}{$blog_id}_{$table}";
            $old_table = "{$prefix}{$id_default_blog}_{$table}";

            unset($queries);
            $queries = array();

            $queries[] = "DROP TABLE IF EXISTS " . $new_table ;
            $queries[] = "CREATE TABLE " . $new_table . " LIKE " . $old_table;
            $queries[] = "INSERT INTO " . $new_table . " SELECT * FROM " . $old_table;

            foreach($queries as $query){
                $wpdb->query($query);
            }

            $new_tables[] = $new_table;
        }

        $wp_uploads_dir             = wp_upload_dir();
        $base_dir                   = $wp_uploads_dir['basedir'];
        $relative_base_dir          = str_ireplace(get_home_path(), '', $base_dir);

        //I need to get the previous folder before the id, just in case this is different to 'sites'
        $dirs_relative_base_dirs    = explode('/', $relative_base_dir);
        $sites_dir                  = $dirs_relative_base_dirs[count($dirs_relative_base_dirs)-2];

        $old_uploads                = str_ireplace("/{$sites_dir}/{$blog_id}", "/{$sites_dir}/{$id_default_blog}", $relative_base_dir);
        $new_uploads                = $relative_base_dir;

        //Replace URLs and paths in the DB

        $old_url = str_ireplace(array('http://', 'https://'), '://', $old_url);
        $new_url = str_ireplace(array('http://', 'https://'), '://', $new_url);

        $database_helper->db_replacer(array($old_url, $old_uploads), array($new_url, $new_uploads), $new_tables);

        //Update Title
        update_option('blogname', $new_name);

        //Update Email
        update_option('admin_email', $admin_email);

        //Copy Files
        $old_uploads = str_ireplace("/{$sites_dir}/{$blog_id}", "/{$sites_dir}/{$id_default_blog}", $base_dir);
        $new_uploads = $base_dir;

        FileSystem_Helper::recurse_copy($old_uploads, $new_uploads);

        //User Roles
        $user_roles_sql = "UPDATE {$prefix}{$blog_id}_options SET option_name = '{$prefix}{$blog_id}_user_roles' WHERE option_name = '{$prefix}{$id_default_blog}_user_roles';";
        $wpdb->query($user_roles_sql);

        //Copy users
        if($copy_users){
            $users = get_users("blog_id={$id_default_blog}");

            function user_array_map( $a ){ 
                return $a[0]; 
            }

            foreach($users as $user){

                $all_meta = array_map( 'user_array_map', get_user_meta( $user->ID ) );

                foreach($all_meta as $metakey => $metavalue) {
                    $prefix_len         = strlen($prefix . $id_default_blog);
                    $metakey_prefix     = substr($metakey, 0, $prefix_len);
                    
                    if($metakey_prefix == "{$prefix}{$id_default_blog}"){
                        $raw_meta_name = substr($metakey,$prefix_len);
                        update_user_meta( $user->ID, $prefix . $blog_id . $raw_meta_name, maybe_unserialize($metavalue) );
                    }
                }

            }
        }

        //Restores main blog
        switch_to_blog($id_default_blog);
    }

    public static function display_404(){
        global $wp_query;
        
        $wp_query->set_404();
        status_header(404);
        get_template_part(404); 
        
        exit();
    }
    
    public static function get_user_roles_by_id($id){
        $user_info      = get_userdata($id);
        
        if(empty($user_info)){
            return null;
        }
        
        return $user_info->roles;
    }
    
    public static function get_user_roles(){
        $user_id        = get_current_user_id();
        return self::get_user_roles_by_id($user_id);
    }
    
    public static function user_has_role($role_id){
        $roles      = self::get_user_roles();

        if($roles === null){
            return false;
        }
        
        if(in_array($role_id, $roles)){
            return true;
        }
        
        return false;
    }
    
    public static function rename_role($role_id, $new_role_name){
        global $wp_roles;
        
        if(!empty($wp_roles)){
            $new_role_name = sanitize_text_field($new_role_name);

            $wp_roles->roles[$role_id]['name'] = $new_role_name;
            update_option($wp_roles->role_key, $wp_roles->roles);
        }
    }
    
    public static function order_user_roles(){
        global $wp_roles;

        if (!isset($wp_roles)){
            $wp_roles = new WP_Roles();
        }
        
        array_multisort( array_column($wp_roles->roles, "name"), SORT_DESC, $wp_roles->roles);

    }

    public static function get_short_locale(){
        $locale     = get_locale();
        
        return explode("_", $locale)[0];
    }

    /**
     * Get the user agent received from a Wordpress website, example:
     * WordPress/5.4.2; https://www.website.com
     * 
     * @return array(
     *      'software'  => Software name
     *      'version'   => Wordpress version
     *      'protocol'  => https or http
     *      'site'      => Website domain without www
     * )
     */
    public static function get_wordpress_data_from_user_agent(){
        $user_agent     = $_SERVER['HTTP_USER_AGENT'];
        $pattern        = '/(.*)\\/(.*); (.*):\\/\\/(.*)/';

        $return         = preg_match($pattern, $user_agent, $matches);
    
        return array(
            'software'      => $matches[1],
            'version'       => $matches[2],
            'protocol'      => $matches[3],
            'site'          => str_replace("www.", "", $matches[4]),
        );
    }

    public static function get_roles(){
        global $wp_roles;
        return $wp_roles->roles; 
    }

    public static function get_role_names(){
        $ret        = array();
        $roles      = self::get_roles();

        foreach($roles as $role_id => $role_data){
            $ret[$role_id]      = $role_data['name'];
        }

        return $ret;
    }

    public static function upload_image($url, $overwrite_image_path = null){
        $url            = str_replace(" ", "%20", $url);
            
        $wp_upload_dir = wp_upload_dir();
                    
        if( !class_exists( 'WP_Http' ))
                include_once( ABSPATH . WPINC . '/class-http.php' );

        $http = new \WP_Http();
        $response = $http->request($url);

        try {
            if(is_a($response, "WP_Error")){
                if(count($response->errors) != 0){
                    return false;
                }
            }else{
                if($response['response']['code'] != 200) {
                    return false;
                }
            }
        }catch(\Exception $ex){
            return false;
        }

        
        if($overwrite_image_path !== null){
            file_put_contents($overwrite_image_path, fopen($url, 'r'));
            
            $file_path = $overwrite_image_path;
            $file_name = basename($overwrite_image_path);
        }else{
            $upload = wp_upload_bits(basename($url), null, $response['body'] );
            if( !empty( $upload['error'] ) ) {
                    return false;
            }

            $file_path = $upload['file'];
            $file_name = basename($file_path);
        }
        
        $file_type          = wp_check_filetype($file_name, null );
        $attachment_title   = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );

        return array(
            'attachment_title'  => $attachment_title,
            'file_name'         => $file_name,
            'file_path'         => $file_path,
            'path'              => $wp_upload_dir['url'] . '/' . $file_name,
            'mime_type'         => $file_type['type'],
        );
    }

    /**
     * Insert an attachment from an URL address.
     *
     * @param  string $url 
     * @param  int    $post_id 
     * @param  array  $meta_data 
     * @return int    Attachment ID
     */
    public static function insert_attachment_from_url($url){

        $image                              = self::upload_image($url);
        
        $post_info = array(
            'guid'			=> $image['path'], 
            'post_mime_type'        => $image['mime_type'],
            'post_title'		=> $image['attachment_title'],
            'post_content'		=> '',
            'post_status'		=> 'inherit',
        );  

        // Create the attachment
        $attach_id = wp_insert_attachment($post_info, $image['file_path']);

        // Include image.php
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        // Define attachment metadata
        $attach_data    = wp_generate_attachment_metadata($attach_id, $image['file_path']);

        // Assign metadata to attachment
        wp_update_attachment_metadata($attach_id,  $attach_data);
                    
        return $attach_id;

    }
}
