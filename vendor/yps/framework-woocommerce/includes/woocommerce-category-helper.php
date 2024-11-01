<?php

namespace YPS\Framework\Woocommerce\v346_950_484;

class Woocommerce_Category_Helper {

    public function __construct($params = array()) {

        $this->params       = $params;
    }

    /**
     * Get products categories.
     *
     * @param $language Language (If null selected language). Language example: en
     * @return array
     */
    public static function get_product_categories($language = null){

        global $sitepress;

        $result     = array();
        
        if($language !== null){
            $current_lang = $sitepress->get_current_language();
            $default_lang = $sitepress->get_default_language();
    
            $sitepress->switch_lang($language);
        }

       
        foreach (get_terms('product_cat', array('hide_empty' => 0, 'parent' => 0)) as $each) {
            $result     = $result + self::get_product_categories_recursive($each->taxonomy, $each->term_id);
        }

        if($language !== null){
            $sitepress->switch_lang($current_lang);
        }

        return $result;
    }


    /**
     *
     * @param string $taxonomy
     * @param int $term_id
     * @param string $separator
     * @param bool $parent_shown
     * @return array
     */
    public static function get_product_categories_recursive($taxonomy = '', $term_id, $separator='', $parent_shown = true){

        $args   = array(
            'hierarchical'      => 1,
            'taxonomy'          => $taxonomy,
            'hide_empty'        => 0,
            'orderby'           => 'id',
            'parent'            => $term_id,
        );
        
        $term           = get_term($term_id , $taxonomy); 
        $result         = array();
        
        if ($parent_shown) {
            $result[$term->term_id]     = html_entity_decode($term->name);
            $parent_shown               = false;
        }
        
        $terms          = get_terms($taxonomy, $args);
        $separator      .= html_entity_decode($term->name) . ' > ';  

        if(count($terms) > 0){            
            foreach ($terms as $term) {
                $result[$term->term_id]         = $separator . html_entity_decode($term->name);
                $result                         = $result + self::get_product_categories_recursive($taxonomy, $term->term_id, $separator, $parent_shown);
            }
        }
        
        return $result;
    }
    



}
