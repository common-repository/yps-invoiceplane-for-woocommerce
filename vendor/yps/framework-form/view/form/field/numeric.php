<input 
    name="<?php echo $this->view['field_name']; ?>" 
    data-decimals="<?php echo $this->view['field']->get_decimals(); ?>" 
    data-decimal_separator="<?php echo $this->view['field']->get_decimal_separator(); ?>" 
    type="text" 
    placeholder="<?php echo $this->view['field']->get_placeholder(); ?>" 
    <?php echo $this->view['field']->get_field_attributes() ?> 
    value="<?php echo $this->view['field']->get_value(); ?>" 
/>