<?php if(!empty($this->view['field']->get_label()) && $this->view['field']->get_show_label() == true): ?>
    <label class="yps-field-label" for="">

        <?php if($this->view['field']->get_allow_empty() == false): ?>
            <span class='yps-mandatory-field'>*</span>
        <?php endif; ?>
        
        <?php echo $this->view['field']->get_label(); ?>

    </label>
<?php endif; ?>