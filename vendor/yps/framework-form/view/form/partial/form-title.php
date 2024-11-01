<?php if(!empty($this->get_form_title())): ?>
    <div class="row">
        <div class="col text-center">
            <h1><?php echo $this->get_form_title(); ?></h1>
        </div>
    </div>
<?php endif; ?>

<?php if(!empty($this->get_form_description())): ?>
    <div class="row">
        <div class="col">
            <?php echo $this->get_form_description(); ?>
        </div>
    </div>
<?php endif; ?>