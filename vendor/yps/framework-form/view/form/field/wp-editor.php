<?php wp_editor($this->view['field']->get_value(), $this->view['field_name'], array(
    'textarea_name'     => $this->view['field_name'],
    'textarea_rows'     => $this->view['field']->get_textarea_rows(),
)); ?>