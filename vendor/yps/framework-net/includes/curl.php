<?php
namespace YPS\Framework\Net\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;

class Curl extends Base{

    protected $base_url;

    public function get_data($url, $post_array = array(), $build_post_array = true){

        $url            = "{$this->base_url}/{$url}";
        $data_string    = json_encode($post_array);

        // process API request
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        curl_setopt($ch, CURLOPT_POST, true);

        //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data"));

        if(count($post_array) != 0){
            if($build_post_array == true){
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_array));
            }else{
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
            }
        }

        $response       = curl_exec($ch);
        $http_status    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json_decoded   = json_decode($response, true);

        if($json_decoded == null){
            return $response;
        }

        return $json_decoded;
    }

	/**
	 * Get the value of base_url
	 *
	 * @return mixed
	 */
	public function get_base_url(){
		return $this->base_url;
	}

	/**
	 * Set the value of base_url
	 *
	 * @param   mixed  $base_url  
	 *
	 * @return  self
	 */
	public function set_base_url($base_url){
		$this->base_url = $base_url;

		return $this;
	}
}



