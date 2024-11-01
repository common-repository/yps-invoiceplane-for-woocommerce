<div class="btn-group <?php echo($this->view['field']->get_full_width() == true)?'yps-full-width':''; ?>" role="group">
    <?php foreach($this->view['field']->get_buttons() as $button_key => $button): ?>
        <?php echo $button->get_controller_view($this); ?>
    <?php endforeach; ?>
</div>