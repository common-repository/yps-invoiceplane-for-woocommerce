<?php

namespace YPS\Framework\Woocommerce\v346_950_484;

use YPS\Framework\Woocommerce\v346_950_484\Woocommerce_Helper;

class Woocommerce_Product_Helper {

    public function __construct($params = array()) {

        $this->params       = $params;
    }

    public static function get_product_id($product){
        if(is_numeric($product)){
            $product_id     = $product;
        }else{
            $product_id     = $product->get_id();
        }

        return $product_id;
    }
    
    public static function get_current_product(){
        $product        = \wc_get_product();

        if(empty($product)){
            return null;
        }

        return $product;
    }

    public static function get_current_product_id(){
        $product        = self::get_current_product();

        if($product === null){
            return null;
        }

        return $product->get_id();
    }

    public static function get_parent_product($product){
        if(empty($product->get_parent_id())){
            return $product;
        }
        
        return \wc_get_product($product->get_parent_id());
    }

    public static function get_array_of_products($query_args = array()){

        $products   = array();
        $ret        = self::get_products($query_args);

        foreach($ret['products'] as $product){
            $products[$product->get_id()]     = array(
                'id'        => $product->get_id(),
                'name'      => $product->get_name(),
            );
        }

        return $products;
    }

    public static function get_array_of_product_categories($product, $array_key = 'slug'){
        $ret        = array();
        $terms      = get_the_terms($product->get_id(), 'product_cat');

        foreach($terms as $term){
            $ret[$term->$array_key]        = $term->name;
        }

        return $ret;
    }

    /**
     * Get a product attribute by its name
     * 
     * @param string $attribute_name Name of the attribute
     * @param type $product WooCommerce Product
     * @return WC_Product_Attribute https://docs.woocommerce.com/wc-apidocs/class-WC_Product_Attribute.html
     */
    public static function get_product_attribute($attribute_name, $product){

        // For Product Variation type
        if( $product->get_parent_id() > 0 ){
            $parent 		= \wc_get_product($product->get_parent_id());
            $attributes             = $parent->get_attributes();
        // For other Product types
        }else{
            $attributes             = $product->get_attributes();
        }

        if(isset($attributes["pa_{$attribute_name}"])){
            return $attributes["pa_{$attribute_name}"];
        }
        
        return null;
    }
    
    /**
     * Get an attribute from product without worrying about if it's a variation or simple product
     * 
     * @param string $attribute_name Name of the attribute
     * @param type $product WooCommerce Product
     * @return string
     */
    public static function get_product_attribute_value($attribute_name, $product){
        $attribute      = null;
        
        // For Product Variation type
        if( $product->get_parent_id() > 0 ){
            $parent 		= \wc_get_product($product->get_parent_id());
            $attribute              = $parent->get_attribute($attribute_name);

        // For other Product types
        }else{
            $attribute          = $product->get_attribute($attribute_name);
        }
        
        return $attribute;
    }
    
    /**
     * Giving a WooCommerce "package" calculates the total weight
     * 
     * @param "package" $package
     * @return int Total Package Weigth
     */
    public static function calculate_package_total_weigth($package){
        $weight     = 0;
        
        foreach($package['contents'] as $key => $row){
            $product_weight     = $row['data']->get_weight();
            
            if(!empty($product_weight)){
                $weight             += $row['quantity']*$product_weight;
            }
        }
        
        return $weight;
    }
    
    public static function calculate_package_total($package){
        $price      = 0;
        
        foreach($package['contents'] as $key => $row){
            $price  += $row['line_total']+$row['line_tax'];
        }
        
        return $price;
    }
            
    /**
     * Return a list of all the Woocommerce products.
     *
     * @param int $productsPerPage  number of product to return (-1 for no limits)
     * @param int $start offset to start
     * @param string $orderBy type of order
     * @param string $orderDir the directory order
     * @param string $search
     * @return array
     */
    public static function get_products($query_args = array(), $only_id = false){

        $products   = array();

        $args = array( 
            'post_type'         => 'product', 
        );
        
        $args   = array_merge($args, $query_args);

        $loop               = new \WP_Query($args);
        $totalProducts      = $loop->found_posts;
        $count              = $loop->post_count;

        while ( $loop->have_posts() ) : $loop->the_post(); 
            global $product; 
            
            if($only_id == true){
                $products[]     = $product->get_id();
            }else{
                $products[]     = $product;
            }
            
        endwhile; 
            wp_reset_query();


        return array(
            'products'          => $products,
            'totalProducts'     => $totalProducts,
            'count'             => $count,
        );

    }
    
    public static function get_product_tags($product_id){
        $terms          = get_the_terms($product_id, 'product_tag');
        $tags           = array();

        if (!empty($terms) && !is_wp_error($terms) ){
            foreach ($terms as $term) {
                $tags[]     = $term;
            }
        }
        
        return $tags;
    }
    
    public static function get_all_product_tags($query_args = array()){
        
        $args           = array(
            'posts_per_page'        => -1,
        ) + $query_args;

        $products       = self::get_products($args, true);
        $all_tags       = array();
                    
        //print_r($products['products']);

        foreach($products['products'] as $product_id){
            $tags       = self::get_product_tags($product_id);
            
            foreach($tags as $tag){
                $all_tags[$tag->term_id]        = $tag;
            }
        }

        return $all_tags;
    }
    
    
    /**
     * Products category.
     *
     * Get the categories of all the products searching by slug.
     *
     * @param string $productCategoryName the category ofa product
     * @return array
     */
    public static function get_products_by_category_slug($productCategorySlug = null){
        /* Using Raw MySQL Query instead of \WP_Query which throw a "Allowed memory" error */
        $database_helper        = new Database_Helper();
        
        
        $rows = $database_helper->get_query_rows("SELECT object_id FROM [prefix]terms
            LEFT JOIN [prefix]term_taxonomy ON [prefix]term_taxonomy.term_id = [prefix]terms.term_id
            LEFT JOIN [prefix]term_relationships ON [prefix]term_relationships.term_taxonomy_id = [prefix]term_taxonomy.term_taxonomy_id 
            WHERE slug = :slug
            AND taxonomy = 'product_cat';", array(
                'slug'	=> $productCategorySlug,
        ));

        $products   = array();
        foreach($rows as $row){
            $products[]		= $row->object_id;
        }

        return $products;
    }

    /**
     * Products category.
     *
     * Get the categories of all the products searching by ID.
     *
     * @param int $categoryId the id of a specific category
     * @return array
     */
    public static function get_products_by_category_id($categoryId = null){
        $term = get_term($categoryId, 'product_cat');

        if(empty($term)){
            return array();
        }

        if($categoryId == null){
            $slug   = null;
        }else{
            $slug = $term->slug;
        }

        return self::get_products_by_category_slug($slug);
    }
    
    /**
     * 
     * @param string $field: 'slug' => Search for slug, 'id' => Search for ID
     */
    public static function get_products_by_tag($search, $field = 'slug'){
        $all_ids = get_posts( array(
            'post_type' => 'product',
            'numberposts' => -1,
            'post_status' => 'publish',
            'fields' => 'ids',
            'tax_query' => array(
                array(
                    'taxonomy'     => 'product_tag',
                    'field'        => $field,
                    'terms'        => $search,
                    'operator'     => 'IN',
                )
            ),
        ));
        
        return $all_ids;
    }
    
    public static function get_products_by_merge($product_ids = array(), $category_ids = array(), $tag_ids = array()){
        $ret_ids   = array();

        $ret_ids   = array_merge($ret_ids, $product_ids);

        foreach($category_ids as $category_id){
            $category_products  = self::get_products_by_category_id($category_id);
            $ret_ids            = array_merge($ret_ids, $category_products);
        }

        foreach($tag_ids as $tag_id){
            $tag_products       = self::get_products_by_tag($tag_id, 'id');
            $ret_ids            = array_merge($ret_ids, $tag_products);
        }
        
        return $ret_ids;
    }
    
    public static function price_to_db($price){
        $decimal_separator      = \wc_get_price_decimal_separator();
        
        return floatval(str_replace($decimal_separator, ".", $price));
    }
    
    public static function price_from_db($price){
        $decimal_separator      = \wc_get_price_decimal_separator();
        
        return str_replace(".", $decimal_separator, $price);
    }
    
    public static function delete_product($id, $force = FALSE){
        $product = \wc_get_product($id);

        if(empty($product)){
            return false;
        }

        // If we're forcing, then delete permanently.
        if($force){
            if($product->is_type('variable')){
                
                foreach ($product->get_children() as $child_id){
                    $child = \wc_get_product($child_id);
                    $child->delete(true);
                }
            }else if ($product->is_type('grouped')){
                foreach ($product->get_children() as $child_id)
                {
                    $child = \wc_get_product($child_id);
                    $child->set_parent_id(0);
                    $child->save();
                }
            }

            $product->delete(true);
            $result = $product->get_id() > 0 ? false : true;
        }else{
            $product->delete();
            $result = 'trash' === $product->get_status();
        }

        if(!$result){
            return false;
        }

        // Delete parent product transients.
        if($parent_id = wp_get_post_parent_id($id)){
            \wc_delete_product_transients($parent_id);
        }
        
        return true;
    }
    
    public static function get_product_image_src($post){
        $data   = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID));
        
        if(empty($data[0])){
            return \wc_placeholder_img_src();
        }
        
        return $data[0];
    }

    /**
     * Redirect user to WooCommerce My Account
     */
    public static function redirect_to_my_account(){
        wp_redirect(get_permalink(get_option('woocommerce_myaccount_page_id')));
    }
    
    public static function is_product_attribute_in_cart($attribute_name, $attribute_value){
        global $woocommerce;
        
        $items = $woocommerce->cart->get_cart();

        foreach($items as $item => $values) { 
            $product = \wc_get_product($values['data']->get_id());
            
            if(empty($product->get_parent_id())){
                $value   = $product->get_attribute($attribute_name);
            }else{
                $parent  = \wc_get_product($product->get_parent_id());
                $value   = $parent->get_attribute($attribute_name);
            }

            if($value == $attribute_value){
                return true;
            }
        }
        
        return false;
    }
    
    public static function is_product_attribute_in_order($order, $attribute_name, $attribute_value){

        foreach($order->get_items() as $item_key => $item){
            $product      = $item->get_product();
            
            if(empty($product->get_parent_id())){
                $value        = $product->get_attribute($attribute_name);
            }else{
                $parent       = \wc_get_product($product->get_parent_id());
                $value        = $parent->get_attribute($attribute_name);
            }

            if($value == $attribute_value){
                return true;
            }
        }

        return false;
    }
}
