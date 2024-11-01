<input 
    name="<?php echo $this->view['field_name']; ?>" 
    type="text" 
    class="yps-color-picker form-control" 
    <?php echo $this->view['field']->get_field_attributes() ?> 
    value="<?php echo $this->view['field']->get_value(); ?>" 
/>