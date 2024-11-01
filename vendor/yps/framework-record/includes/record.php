<?php

namespace YPS\Framework\Record\v346_950_484;

use YPS\Framework\Core\v346_950_484\Base;
use YPS\Framework\Core\v346_950_484\Helper;

class Record extends Base {

    public $helper;

    public function __construct($context, $params = array()) {
        $this->helper           = new Helper($context, $params);

        parent::__construct($context, $params);
    }

}
