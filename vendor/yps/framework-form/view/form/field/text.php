<input 
    name="<?php echo $this->view['field_name']; ?>" 
    type="text" 
    placeholder="<?php echo $this->view['field']->get_placeholder(); ?>" 
    <?php echo $this->view['field']->get_field_attributes() ?> 
    value="<?php echo $this->view['field']->get_value(); ?>" 
/>