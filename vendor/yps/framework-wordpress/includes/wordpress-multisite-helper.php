<?php

namespace YPS\Framework\Wordpress\v346_950_484;

class Wordpress_Multisite_Helper {

    protected $context;

    public function __construct($context) {

        $this->context       = $context;
    }
    

    /**
     * Get the list of child website, main website is excluded
     * @return \WP_Site List of WP_Site objects
     */
    public static function get_children_sites($include_master = false){
        $children   = array();

        $sites      = \get_sites(array(
            'deleted'   => 0
        ));

        foreach($sites as $site){
            if($include_master == true){
                $children[]     = $site;
            }else{
                if($site->blog_id != 1){
                    $children[]     = $site;
                }
            }
        }

        return $children;
    }

}


