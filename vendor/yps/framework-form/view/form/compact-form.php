<div class="yps-bootstrap yps-plugin">
    <div class="container-fluid">

            <form 
                id="<?php echo $this->get_form()->get_id(); ?>"
                method="POST" 
                action="" 
                class="yps-form yps-compact-form <?php echo ($this->get_form()->get_is_ajax_form() == true)?'yps-ajax-form':''; ?>"
                data-ajax-url="<?php echo $this->view['ajax_url']; ?>"
                >

                <?php echo $this->get_framework_view("Form", "form/partial/alerts.php"); ?>
                
                <!-- Draw fields -->
                <?php foreach($this->get_form()->get_fields() as $field): ?>
                    <div class="form-group" style="<?php echo ($field->get_hide() == true)?"display:none":""; ?>">
                        <?php if($field->get_disable_output() !== true): ?>

                            <?php if(!empty($field->get_text_before_field())): ?>
                                <small class='mb-3'><?php echo $field->get_text_before_field(); ?></small>
                            <?php endif; ?>

                            <?php $field->get_label_view()->display(); ?>
                            <?php $field->get_view()->display(); ?>

                            <?php if(!empty($field->get_text_after_field())): ?>
                                <small><?php echo $field->get_text_after_field(); ?></small>
                            <?php endif; ?>

                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <input type="hidden" name="form_task" value="save" />

            </form>
    </div>
    
    
</div>

