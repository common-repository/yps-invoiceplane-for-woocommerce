<?php if($this->show_actions_new_button == true): ?>
    <a class="btn btn-primary" href="<?php echo $this->get_edit_url(); ?>">
        <?php printf(__('New %s', 'yps-framework-core'), $this->record_singular_name); ?>
    </a>
<?php endif; ?>

<?php echo $this->get_index_custom_buttons(); ?>