<?php
namespace YPS\Framework\File_System\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;
use YPS\Framework\Core\v346_950_484\Wordpress_Helper;

class File_System_Helper extends Base {


    /**
     * Create folder in the given path (also recursively)
     *
     * @param string $path
     * @return void
     */
    public static function create_folder($path){
        wp_mkdir_p($path);
    }
    
    public static function scandir($path){
        
        if(!file_exists($path)){
            return false;
        }

        return array_diff(scandir($path), array('..', '.'));
    }
    
    /**
     * Recursive copy of files and directories
     * 
     * @param string $src Source path
     * @param string $dest Destination path
     */
    public static function recurse_copy($src, $dest){

        $dir = opendir($src); 

        //Not a directory
        if(!$dir || !is_dir($src)){
            mkdir($src);
        }

        if(!file_exists($dest)){
            mkdir($dest);
        }

        while(false !== ($file = readdir($dir)) ){
            if(($file != '.') && ($file != '..')){
                if(is_dir("{$src}/{$file}")){
                    self::recurse_copy("{$src}/{$file}", "{$dest}/{$file}");
                }else{
                    copy("{$src}/{$file}", "{$dest}/{$file}");
                }
            }
        } 

        closedir($dir);
    }

    public static function get_csv_count($path){
        $rows   = 0;

        if (($fp = fopen($path, "r")) !== FALSE) { 
            while (($record = fgetcsv($fp)) !== FALSE) {
                $rows++;
            }

            fclose($fp);

            return $rows;
        }
    }
    
    public static function seek_csv(&$handle, $line){

        for($i = 0; $i < $line; $i++){
            $handle->fgetcsv(",", "\"", "\\");
        }

    }
    
    public static function download_file($file_path, $filename = null){

        if($filename === null){
            $filename   = basename($file_path);
        }

        header("Cache-Control: public");
        header("Content-type: " . mime_content_type($file_path));
        header("Content-Disposition: attachment; filename= " . $filename);
        header("Content-Transfer-Encoding: binary");

        readfile($file_path);
        die();
    }

}



