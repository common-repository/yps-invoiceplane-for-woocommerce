<?php if(!empty($this->get_list_title())): ?>
    <div class="row yps-record-list-title">
        <div class="col text-center">
            <h1><?php echo $this->get_list_title(); ?></h1>
        </div>
    </div>
<?php endif; ?>

<?php if(!empty($this->get_list_description())): ?>
    <div class="row yps-record-list-description">
        <div class="col">
            <?php echo $this->get_list_description(); ?>
        </div>
    </div>
<?php endif; ?>