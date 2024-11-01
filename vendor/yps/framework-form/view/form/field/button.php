<button 
    name="<?php echo $this->view['field_name']; ?>"
    id="<?php echo $this->view['field']->get_id(); ?>" 
    type="button" 
    class="btn btn-<?php echo $this->view['field']->get_button_type(); ?> <?php echo($this->view['field']->get_full_width() == true)?'yps-full-width':''; ?>"
    <?php echo $this->view['field']->get_field_attributes() ?>
    >
    <i class="<?php echo $this->view['field']->get_button_icon(); ?>"></i> <?php echo $this->view['field']->get_button_text(); ?>
</button>