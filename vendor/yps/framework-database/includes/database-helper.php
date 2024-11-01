<?php

namespace YPS\Framework\Database\v346_950_484;

use YPS\Framework\Wordpress\v346_950_484\Wordpress_Helper;

class Database_Helper {

    const PRIMARY_KEY_INT       = "INT NOT NULL AUTO_INCREMENT";
    const PRIMARY_KEY_UINT      = "INT UNSIGNED NOT NULL AUTO_INCREMENT";

    const UINT                  = "INT UNSIGNED DEFAULT NULL";

    public function __construct($params = array()) {

    }

    /**
     * Return the database connection
     *
     * @return object[database]
     */
    public function get_db(){
        global $wpdb;

        return $wpdb;
    }
    
    public function get_multisite_table_prefix(){
        $db             = $this->get_db();
        $current_site   = 1;

        return $db->get_blog_prefix($current_site);
    }

    /**
     * Return the string prefix used in the table names of the database
     *
     * @return string
     */
    public function get_table_prefix(){
        $db     = $this->get_db();

        return $db->prefix;
    }

    /**
     * Prepare query
     *
     * This function preprocess a given query to a custom format
     *
     * @param string $query the query to be processed
     * @param array $params the attribute to be passed.
     * @return string
     */
    public function prepare_query($query, $params = array(), $force_string_params = false){
        $table_prefix           = $this->get_table_prefix();
        $main_table_prefix      = $this->get_multisite_table_prefix();
        
        $query              = str_replace("[prefix]", $table_prefix, $query);
        $query              = str_replace("[main-prefix]", $main_table_prefix, $query);
        
        /*
            * Sort the variables in descending order of length, for example:
            * 
            * Array
            (
                [valore_111] => 1500
                [valore_11] => 12
                [valore_1] => 100
            )
            * 
            * This will replace the longer one and the smaller one,
            * because it could happen that part of "value_111" comes erroneously
            * replaced by "valore_1"
            */
        uksort($params, function($a, $b){return strlen($a) < strlen($b);});

        foreach($params as $name => $value){
            if(is_numeric($value) && !is_string($value)){
                $query  = str_replace(":{$name}", $value, $query);
            }else{
                $query  = str_replace(":{$name}", "'" . addslashes($value) . "'", $query);
            }

        }

        return $query;
    }

    /**
     * Execute a query to the database.
     *
     * @param string $query the query to be execute.
     * @param array $params the attribute to be passed.
     * @return void
     */
    public function query($query, $params = array()){
        $db             = $this->get_db();
        $query          = $this->prepare_query($query, $params);

        //Wordpress_Helper::write_log($query);
        
        $db->query($query);
        
        if($db->last_error !== ''){
            Wordpress_Helper::write_log($db->last_error);
        }
    }

    /**
     * Given a query, takes all the records
     *
     * @param string $query the query to be execute.
     * @param array $params the attribute to be passed.
     * @param object $output the format of the return data.
     * @return object ARRAY_A | ARRAY_N | OBJECT | OBJECT_K
     */
    public function get_query_rows($query, $params = array(), $output = OBJECT, $debug = false){
        $db             = $this->get_db();
        $query          = $this->prepare_query($query, $params);

        if($debug == true){
            Wordpress_Helper::write_log($query);
        }

        return $db->get_results($query, $output);
    }

    /**
     * Take only one row from the database.
     *
     * @param string $query the query to be execute.
     * @param array $params the attribute to be passed.
     * @param object $output the format of the return data.
     * @return object ARRAY_A | ARRAY_N | OBJECT | OBJECT_K
     */
    public function get_query_row($query, $params = array(), $output = OBJECT, $debug = false){
        $db             = $this->get_db();
        $query          = $this->prepare_query($query, $params);
        
        if($debug == true){
            Wordpress_Helper::write_log($query);
        }
        
        return $db->get_row($query, $output);
    }

    /**
     * Insert data to the database.
     *
     * @param string $table_name the table where we want to insert the data.
     * @param array $record the record columns to be filled with data.
     * @return string | false
     */
    public function insert($table_name, $record = array()){
        $db             = $this->get_db();

        $db->insert($this->prepare_query($table_name), $record);

        $last_interset_id       = $db->insert_id;

        if($last_interset_id == false){
            if(empty($db->last_error)){
                Wordpress_Helper::write_log("ERROR: Can't insert the record (Unknown Reason), data is: " . print_r($record, true));
            }else{
                Wordpress_Helper::write_log("ERROR: {$db->last_error}");
            }
            
        }

        return $last_interset_id;
    }

    /**
     * Update records from the database.
     *
     * @param string $table_name the table we want to update.
     * @param array $record the record columns to be updated.
     * @param array $where the clause in base the data will be modified.
     * @return void
     */
    public function update($table_name, $record = array(), $where = array()){
        $db             = $this->get_db();

        $db->update($this->prepare_query($table_name), $record, $where);
    }

    /**
     * Delete records from the database.
     *
     * @param string $table_name the table where we want to delete the data.
     * @param array $where the clause in base the data will be deleted.
     * @return void
     */
    public function delete($table_name, $where = array()){
        $db             = $this->get_db();

        $db->delete($this->prepare_query($table_name), $where);
    }

    /**
     * Convert an array of the type:
     * 
     * array = (
     *      'key' => 'value',
     * )
     * 
     * in:
     * 
     * array = (
     *      '0' => ''key' = 'value''
     * )
     *
     * @param array $arr associative array to be converted
     *
     * @return array
     *
     */
    public function convert_array_to_values($arr = array()){
        $db             = $this->get_db();

        $ret = array();
        foreach($arr as $arrKey => $arrValue){
            $ret[]   = "{$db->quoteName($arrKey)} = {$db->quote($arrValue)}";
        }

        return $ret;
    }

    /**
     * Database details.
     *
     * Return the type of character set and collation rules of the database.
     *
     * @return object
     */
    public function get_charset_collate(){
        $db             = $this->get_db();

        return $db->get_charset_collate();
    }

    /**
     * Table exist?
     *
     * Check if a specific table exist in the database.
     *
     * @param string $table
     * @return bool
     */
    public function table_exists($table){
        $result     = $this->get_query_rows("SHOW TABLES LIKE '{$table}'");

        if(count($result) == 0){
            return false;
        }

        return true;
    }

    /**
     * Tables structure.
     *
     * Useful for creating new tables and updating existing tables to a new structure.
     *
     * @param string $queries the query to run. Can be multiple queries in an array, or a string of queries separated by semicolons.
     * @param bool $execute whether or not to execute the query right away.
     * @return array
     */
    public function db_delta($queries = '', $execute = true) {

        $queries    = $this->prepare_query($queries);

        // Separate individual queries into an array
        if ( !is_array($queries) ) {
            $queries = explode( ';', $queries );
            $queries = array_filter( $queries );
        }



        $cqueries = array(); // Creation Queries
        $iqueries = array(); // Insertion Queries
        $for_update = array();

        // Create a table name index for an array ($cqueries) of queries
        foreach ($queries as $qry) {
            if ( preg_match( "|CREATE TABLE ([^ ]*)|", $qry, $matches ) ) {
                $cqueries[ trim( $matches[1], '`' ) ] = $qry;
                $for_update[$matches[1]] = 'Created table '.$matches[1];
            } elseif ( preg_match( "|CREATE DATABASE ([^ ]*)|", $qry, $matches ) ) {
                array_unshift( $cqueries, $qry );
            } elseif ( preg_match( "|INSERT INTO ([^ ]*)|", $qry, $matches ) ) {
                $iqueries[] = $qry;
            } elseif ( preg_match( "|UPDATE ([^ ]*)|", $qry, $matches ) ) {
                $iqueries[] = $qry;
            } else {
                // Unrecognized query type
            }
        }

        $text_fields = array( 'tinytext', 'text', 'mediumtext', 'longtext' );
        $blob_fields = array( 'tinyblob', 'blob', 'mediumblob', 'longblob' );

        foreach ( $cqueries as $table => $qry ) {

            // Fetch the table column structure from the database
            if($this->table_exists($table) == false){
                $tablefields = null;
            }else{
                $tablefields = $this->get_query_rows("DESCRIBE {$table};");
            }

            if ( ! $tablefields )
                continue;

            // Clear the field and index arrays.
            $cfields = $indices = $indices_without_subparts = array();

            // Get all of the field names in the query from between the parentheses.
            preg_match("|\((.*)\)|ms", $qry, $match2);
            $qryline = trim($match2[1]);

            // Separate field lines into an array.
            $flds = explode("\n", $qryline);

            // For every field line specified in the query.
            foreach ( $flds as $fld ) {
                $fld = trim( $fld, " \t\n\r\0\x0B," ); // Default trim characters, plus ','.

                // Extract the field name.
                preg_match( '|^([^ ]*)|', $fld, $fvals );
                $fieldname = trim( $fvals[1], '`' );
                $fieldname_lowercased = strtolower( $fieldname );

                // Verify the found field name.
                $validfield = true;
                switch ( $fieldname_lowercased ) {
                    case '':
                    case 'primary':
                    case 'index':
                    case 'fulltext':
                    case 'unique':
                    case 'key':
                    case 'spatial':
                        $validfield = false;

                        /*
                            * Normalize the index definition.
                            *
                            * This is done so the definition can be compared against the result of a
                            * `SHOW INDEX FROM $table_name` query which returns the current table
                            * index information.
                            */

                        // Extract type, name and columns from the definition.
                        preg_match(
                                '/^'
                            .   '(?P<index_type>'             // 1) Type of the index.
                            .       'PRIMARY\s+KEY|(?:UNIQUE|FULLTEXT|SPATIAL)\s+(?:KEY|INDEX)|KEY|INDEX'
                            .   ')'
                            .   '\s+'                         // Followed by at least one white space character.
                            .   '(?:'                         // Name of the index. Optional if type is PRIMARY KEY.
                            .       '`?'                      // Name can be escaped with a backtick.
                            .           '(?P<index_name>'     // 2) Name of the index.
                            .               '(?:[0-9a-zA-Z$_-]|[\xC2-\xDF][\x80-\xBF])+'
                            .           ')'
                            .       '`?'                      // Name can be escaped with a backtick.
                            .       '\s+'                     // Followed by at least one white space character.
                            .   ')*'
                            .   '\('                          // Opening bracket for the columns.
                            .       '(?P<index_columns>'
                            .           '.+?'                 // 3) Column names, index prefixes, and orders.
                            .       ')'
                            .   '\)'                          // Closing bracket for the columns.
                            . '$/im',
                            $fld,
                            $index_matches
                        );

                        // Uppercase the index type and normalize space characters.
                        $index_type = strtoupper( preg_replace( '/\s+/', ' ', trim( $index_matches['index_type'] ) ) );

                        // 'INDEX' is a synonym for 'KEY', standardize on 'KEY'.
                        $index_type = str_replace( 'INDEX', 'KEY', $index_type );

                        // Escape the index name with backticks. An index for a primary key has no name.
                        $index_name = ( 'PRIMARY KEY' === $index_type ) ? '' : '`' . strtolower( $index_matches['index_name'] ) . '`';

                        // Parse the columns. Multiple columns are separated by a comma.
                        $index_columns = $index_columns_without_subparts = array_map( 'trim', explode( ',', $index_matches['index_columns'] ) );

                        // Normalize columns.
                        foreach ( $index_columns as $id => &$index_column ) {
                            // Extract column name and number of indexed characters (sub_part).
                            preg_match(
                                    '/'
                                .   '`?'                      // Name can be escaped with a backtick.
                                .       '(?P<column_name>'    // 1) Name of the column.
                                .           '(?:[0-9a-zA-Z$_-]|[\xC2-\xDF][\x80-\xBF])+'
                                .       ')'
                                .   '`?'                      // Name can be escaped with a backtick.
                                .   '(?:'                     // Optional sub part.
                                .       '\s*'                 // Optional white space character between name and opening bracket.
                                .       '\('                  // Opening bracket for the sub part.
                                .           '\s*'             // Optional white space character after opening bracket.
                                .           '(?P<sub_part>'
                                .               '\d+'         // 2) Number of indexed characters.
                                .           ')'
                                .           '\s*'             // Optional white space character before closing bracket.
                                .        '\)'                 // Closing bracket for the sub part.
                                .   ')?'
                                . '/',
                                $index_column,
                                $index_column_matches
                            );

                            // Escape the column name with backticks.
                            $index_column = '`' . $index_column_matches['column_name'] . '`';

                            // We don't need to add the subpart to $index_columns_without_subparts
                            $index_columns_without_subparts[ $id ] = $index_column;

                            // Append the optional sup part with the number of indexed characters.
                            if ( isset( $index_column_matches['sub_part'] ) ) {
                                $index_column .= '(' . $index_column_matches['sub_part'] . ')';
                            }
                        }

                        // Build the normalized index definition and add it to the list of indices.
                        $indices[] = "{$index_type} {$index_name} (" . implode( ',', $index_columns ) . ")";
                        $indices_without_subparts[] = "{$index_type} {$index_name} (" . implode( ',', $index_columns_without_subparts ) . ")";

                        // Destroy no longer needed variables.
                        unset( $index_column, $index_column_matches, $index_matches, $index_type, $index_name, $index_columns, $index_columns_without_subparts );

                        break;
                }

                // If it's a valid field, add it to the field array.
                if ( $validfield ) {
                    $cfields[ $fieldname_lowercased ] = $fld;
                }
            }

            // For every field in the table.
            foreach ( $tablefields as $tablefield ) {
                $tablefield_field_lowercased = strtolower( $tablefield->Field );
                $tablefield_type_lowercased = strtolower( $tablefield->Type );

                // If the table field exists in the field array ...
                if ( array_key_exists( $tablefield_field_lowercased, $cfields ) ) {

                    // Get the field type from the query.
                    preg_match( '|`?' . $tablefield->Field . '`? ([^ ]*( unsigned)?)|i', $cfields[ $tablefield_field_lowercased ], $matches );
                    $fieldtype = $matches[1];
                    $fieldtype_lowercased = strtolower( $fieldtype );

                    // Is actual field type different from the field type in query?
                    if ($tablefield->Type != $fieldtype) {
                        $do_change = true;
                        if ( in_array( $fieldtype_lowercased, $text_fields ) && in_array( $tablefield_type_lowercased, $text_fields ) ) {
                            if ( array_search( $fieldtype_lowercased, $text_fields ) < array_search( $tablefield_type_lowercased, $text_fields ) ) {
                                $do_change = false;
                            }
                        }

                        if ( in_array( $fieldtype_lowercased, $blob_fields ) && in_array( $tablefield_type_lowercased, $blob_fields ) ) {
                            if ( array_search( $fieldtype_lowercased, $blob_fields ) < array_search( $tablefield_type_lowercased, $blob_fields ) ) {
                                $do_change = false;
                            }
                        }

                        if ( $do_change ) {
                            // Add a query to change the column type.
                            $cqueries[] = "ALTER TABLE {$table} CHANGE COLUMN `{$tablefield->Field}` " . $cfields[ $tablefield_field_lowercased ];
                            $for_update[$table.'.'.$tablefield->Field] = "Changed type of {$table}.{$tablefield->Field} from {$tablefield->Type} to {$fieldtype}";
                        }
                    }

                    // Get the default value from the array.
                    if ( preg_match( "| DEFAULT '(.*?)'|i", $cfields[ $tablefield_field_lowercased ], $matches ) ) {
                        $default_value = $matches[1];
                        if ($tablefield->Default != $default_value) {
                            // Add a query to change the column's default value
                            $cqueries[] = "ALTER TABLE {$table} ALTER COLUMN `{$tablefield->Field}` SET DEFAULT '{$default_value}'";
                            $for_update[$table.'.'.$tablefield->Field] = "Changed default value of {$table}.{$tablefield->Field} from {$tablefield->Default} to {$default_value}";
                        }
                    }

                    // Remove the field from the array (so it's not added).
                    unset( $cfields[ $tablefield_field_lowercased ] );
                } else {
                    // This field exists in the table, but not in the creation queries?
                }
            }

            // For every remaining field specified for the table.
            foreach ($cfields as $fieldname => $fielddef) {
                // Push a query line into $cqueries that adds the field to that table.
                $cqueries[] = "ALTER TABLE {$table} ADD COLUMN $fielddef";
                $for_update[$table.'.'.$fieldname] = 'Added column '.$table.'.'.$fieldname;
            }

            // Index stuff goes here. Fetch the table index structure from the database.
            $tableindices = $this->get_query_rows("SHOW INDEX FROM {$table};");

            if ($tableindices) {
                // Clear the index array.
                $index_ary = array();

                // For every index in the table.
                foreach ($tableindices as $tableindex) {

                    // Add the index to the index data array.
                    $keyname = strtolower( $tableindex->Key_name );
                    $index_ary[$keyname]['columns'][] = array('fieldname' => $tableindex->Column_name, 'subpart' => $tableindex->Sub_part);
                    $index_ary[$keyname]['unique'] = ($tableindex->Non_unique == 0)?true:false;
                    $index_ary[$keyname]['index_type'] = $tableindex->Index_type;
                }

                // For each actual index in the index array.
                foreach ($index_ary as $index_name => $index_data) {

                    // Build a create string to compare to the query.
                    $index_string = '';
                    if ($index_name == 'primary') {
                        $index_string .= 'PRIMARY ';
                    } elseif ( $index_data['unique'] ) {
                        $index_string .= 'UNIQUE ';
                    }
                    if ( 'FULLTEXT' === strtoupper( $index_data['index_type'] ) ) {
                        $index_string .= 'FULLTEXT ';
                    }
                    if ( 'SPATIAL' === strtoupper( $index_data['index_type'] ) ) {
                        $index_string .= 'SPATIAL ';
                    }
                    $index_string .= 'KEY ';
                    if ( 'primary' !== $index_name  ) {
                        $index_string .= '`' . $index_name . '`';
                    }
                    $index_columns = '';

                    // For each column in the index.
                    foreach ($index_data['columns'] as $column_data) {
                        if ( $index_columns != '' ) {
                            $index_columns .= ',';
                        }

                        // Add the field to the column list string.
                        $index_columns .= '`' . $column_data['fieldname'] . '`';
                    }

                    // Add the column list to the index create string.
                    $index_string .= " ($index_columns)";

                    // Check if the index definition exists, ignoring subparts.
                    if ( ! ( ( $aindex = array_search( $index_string, $indices_without_subparts ) ) === false ) ) {
                        // If the index already exists (even with different subparts), we don't need to create it.
                        unset( $indices_without_subparts[ $aindex ] );
                        unset( $indices[ $aindex ] );
                    }
                }
            }

            // For every remaining index specified for the table.
            foreach ( (array) $indices as $index ) {
                // Push a query line into $cqueries that adds the index to that table.
                $cqueries[] = "ALTER TABLE {$table} ADD $index";
                $for_update[] = 'Added index ' . $table . ' ' . $index;
            }

            // Remove the original table creation query from processing.
            unset( $cqueries[ $table ], $for_update[ $table ] );
        }

        $allqueries = array_merge($cqueries, $iqueries);
        if ($execute) {
            foreach ($allqueries as $query) {
                $this->query($query);
            }
        }

        return $for_update;
    }
    
    public static function escape_in($options){
        
        foreach($options as &$value){
            if(is_string($value)){
                $value             = "'" . esc_sql($value) . "'";
            }else{
                $value             = $value;
            }
        }
        
        return implode(",", $options);
    }

    /**
     * 
     * @param type $from
     * @param type $to
     * @param type $data
     * @param type $serialised
     * @return $data
     */
    public function recursive_unserialize_replace($from = '', $to = '', $data = '', $serialised = false){

        try {

            if(is_string($data) && ($unserialized = @unserialize($data)) !== false){
                $data = $this->recursive_unserialize_replace($from, $to, $unserialized, true);
            }else if(is_array($data)){
                
                $_tmp = array();
                
                foreach($data as $key => $value){
                    $_tmp[ $key ] = $this->recursive_unserialize_replace($from, $to, $value, false);
                }

                $data = $_tmp;
                unset($_tmp);
                
            }else if(is_object($data)){
                
                $_tmp = $data; 
                $props = get_object_vars($data);

                foreach($props as $key => $value){
                    $_tmp->$key = $this->recursive_unserialize_replace($from, $to, $value, false);
                }

                $data = $_tmp;
                unset($_tmp);
                
            }else{
                if(is_string($data)){
                    $data = str_replace($from, $to, $data);
                }
            }

            if($serialised){
                return serialize($data);
            }

        }catch(\Exception $error) {}

        return $data;
    }
    
    public function replace_table_prefix($table_name){

        $table_prefix           = $this->get_table_prefix();
        $main_table_prefix      = $this->get_multisite_table_prefix();

        $table_name             = str_replace("[prefix]", $table_prefix, $table_name);
        $table_name             = str_replace("[main-prefix]", $main_table_prefix, $table_name);

        return $table_name;
    }

    public static function get_select_sql_columns($columns){
        $sql_columns        = array();

        foreach($columns as $column_id => $column_name){
            $sql_columns[]      = "`{$column_id}` AS `{$column_name}`";
        }

        return implode(", ", $sql_columns);
    }
    
    public function db_replacer($search = '', $replace = '', $tables = array()){

        global $wpdb;

        $guid           = 1;
        $exclude_cols   = array();

        if(is_array( $tables ) && ! empty($tables)){

            foreach($tables as $table){
                $columns    = array();

                // Get a list of columns in this table
                $fields = $wpdb->query( 'DESCRIBE ' . $table );
                if (!$fields) {
                    continue;
                }

                $columns_gr = $wpdb->get_results('DESCRIBE ' . $table);

                foreach($columns_gr as $column){
                    $columns[$column->Field] = $column->Key == 'PRI' ? true : false;
                }

                // Count the number of rows we have in the table if large we'll split into blocks, This is a mod from Simon Wheatley
                $row_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $table);   
                
                if($row_count == 0){
                    continue;
                }

                $page_size      = 50000;
                $pages          = ceil($row_count / $page_size);

                for($page = 0; $page < $pages; $page++) {

                        $current_row    = 0;
                        $start          = $page * $page_size;
                        $end            = $start + $page_size;
                        
                        // Grab the content of the table
                        $data = $wpdb->query(sprintf('SELECT * FROM %s LIMIT %d, %d', $table, $start, $end ));

                        $rows_gr = $wpdb->get_results( sprintf('SELECT * FROM %s LIMIT %d, %d', $table, $start, $end ));

                        foreach($rows_gr as $row) {

                                $current_row++;

                                $update_sql     = array();
                                $where_sql      = array();
                                $upd            = false;

                                foreach($columns as $column => $primary_key) {
                                        if($guid == 1 && in_array($column, $exclude_cols)){
                                            continue;
                                        }

                                        $edited_data = $data_to_fix = $row->$column;

                                        // Run a search replace on the data that'll respect the serialisation.
                                        $edited_data = $this->recursive_unserialize_replace($search, $replace, $data_to_fix);

                                        // Something was changed
                                        if($edited_data != $data_to_fix){
                                            $update_sql[]   = $column . ' = "' . esc_sql($edited_data) . '"';
                                            $upd            = true;
                                        }

                                        if($primary_key){
                                            $where_sql[]    = $column . ' = "' . esc_sql($data_to_fix) . '"';
                                        }
                                                
                                }

                                if($upd && ! empty($where_sql)) {
                                    $sql        = 'UPDATE ' . $table . ' SET ' . implode(', ', $update_sql) . ' WHERE ' . implode(' AND ', array_filter($where_sql));
                                    $result     = $wpdb->query($sql);   
                                }


                        }

                }

            }

        }

    }
}

