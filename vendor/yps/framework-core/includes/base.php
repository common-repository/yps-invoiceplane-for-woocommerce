<?php

namespace YPS\Framework\Core\v346_950_484;

class Base {

    protected $context;
    protected $params       = array();

    /**
     * Init only startup data
     */
    public function __construct($context, $params = array()) {
        $this->context      = $context;
        $this->params       = $params;
    }

    /**
     * Init code after data has been set
     */
    public function init(){
        return $this;
    }

}
