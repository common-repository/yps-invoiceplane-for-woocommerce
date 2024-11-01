<?php if($this->view['field']->get_button() !== null): ?>
    <?php $this->view['field']->get_button()->get_view()->display(); ?>
<?php endif; ?>

<?php echo $this->get_framework_view('Form', "form/field/modal-content.php"); ?>