<input 
    name="<?php echo $this->view['field_name']; ?>" 
    type="checkbox" 
    placeholder="<?php echo $this->view['field']->get_placeholder(); ?>" 
    <?php echo $this->view['field']->get_field_attributes() ?> 
    value="<?php echo $this->view['field']->get_value(); ?>" 
    <?php $this->get_view_helper()->html_if_checked($this->view['field']->get_checked()); ?> 
/>