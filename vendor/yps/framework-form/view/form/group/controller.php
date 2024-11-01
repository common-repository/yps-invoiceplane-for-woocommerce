<?php

$params             = $this->view['group']['controller_options']['params'];
$params             = array_merge($params, array(
    'parent_record_id'      => $this->record_id,
    'parent_record_entity'  => $this->record_entity,
));

$controller         = new $this->view['group']['controller_options']['class']($params);

$controller->{"{$this->view['group']['controller_options']['default_action']}_action"}();