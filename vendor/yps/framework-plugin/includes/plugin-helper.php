<?php

namespace YPS\Framework\Core\v346_950_484;

/**
 * Generic useful functions
 */
class Plugin_Helper extends Base {

    /**
     * Enqueue plugin stylesheet file
     *
     * @param string $name
     * @param string $plugin_rel_url
     * @param string $version
     * @param array $deps
     * @return void
     */
    public function enqueue_style($name, $plugin_rel_url, $deps = array()){

        $plugin_code    = $this->context->get_plugin_code();
        $plugin_version = $this->context->get_plugin_version();

        Helper::enqueue_style($name, $plugin_code, $plugin_rel_url, $plugin_version, $deps);
    }

    /**
     * Enqueue plugin script file
     *
     * @param string $name
     * @param string $plugin_rel_url
     * @param string $version
     * @param array $deps
     * @return void
     */
    public function enqueue_script($name, $plugin_rel_url, $deps = array(), $footer = false){

        $plugin_code    = $this->context->get_plugin_code();
        $plugin_version = $this->context->get_plugin_version();

        Helper::enqueue_script($name, $plugin_code, $plugin_rel_url, $deps, $plugin_version, $footer);
    }
}

