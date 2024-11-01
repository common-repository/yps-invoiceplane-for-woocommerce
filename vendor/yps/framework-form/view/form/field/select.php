

<div class="input-group">
  
    <select 
        name="<?php echo $this->view['select_field_name']; ?>" 
        <?php echo $this->view['field']->get_field_attributes() ?> 
        <?php echo ($this->view['field']->get_is_multiple() == true)?"multiple":""; ?>
        >

        <?php foreach($this->view['field']->get_options() as $option_value => $option_label): ?>
            <option 
                value="<?php echo $option_value; ?>" 
                <?php $this->get_view_helper()->html_selected($option_value, $this->view['field']->get_value()); ?>
                >
                    <?php echo $option_label; ?>
            </option>
        <?php endforeach; ?>

    </select>

    <?php if($this->view['field']->get_add_new_button() == true): ?>
    <div class="input-group-append">
        <button 
            id="<?php echo $this->view['field']->get_new_button_id(); ?>" 
            class="btn btn-outline-secondary yps-new-button" 
            type="button"
            data-ajax-url="<?php echo $this->view['field']->get_new_button_ajax_url(); ?>">
            <i class="<?php echo $this->view['field']->get_new_button_icon(); ?>"></i> <?php echo $this->view['field']->get_new_button_label(); ?>
        </button>
    </div>
    <?php endif; ?>

</div>