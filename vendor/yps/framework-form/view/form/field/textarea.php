<textarea 
    name="<?php echo $this->view['field_name']; ?>" 
    <?php echo $this->view['field']->get_field_attributes() ?> 
><?php echo $this->view['field']->get_value(); ?></textarea>
