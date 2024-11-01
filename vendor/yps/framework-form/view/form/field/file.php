<input 
    name="<?php echo $this->view['field_name']; ?>" 
    type="file" 
    <?php echo $this->view['field']->get_field_attributes() ?> 
    value="<?php echo $this->view['field']->get_value(); ?>" 
/>