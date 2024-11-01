<input 
    name="<?php echo $this->view['field_name']; ?>" 
    type="text" 
    <?php echo $this->view['field']->get_field_attributes() ?> 
    value="<?php echo $this->view['field']->get_value(); ?>" 
/>