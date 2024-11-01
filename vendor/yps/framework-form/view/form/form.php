<div class="yps-bootstrap yps-plugin">
    <div class="container-fluid">

            <form 
                id="<?php echo $this->get_form()->get_id(); ?>"
                method="POST" 
                action="" 
                class="yps-form <?php echo ($this->get_form()->get_is_ajax_form() == true)?'yps-ajax-form':''; ?>"
                data-ajax-url="<?php echo $this->view['ajax_url']; ?>"
                >

                <?php echo $this->get_form_title_view(); ?>

                <?php echo $this->get_toolbar_view(); ?>
                
                <?php echo $this->get_framework_view("Form", "form/partial/alerts.php"); ?>
                
                <!-- Draw groups and fields -->
                <?php echo $this->get_framework_view("Form", "form/groups.php"); ?>

                <input type="hidden" name="form_task" value="save" />

                <?php echo $this->get_toolbar_view(); ?>
            </form>

            <?php echo $this->get_form_footer_view(); ?>
        
    </div>
    
    
</div>

