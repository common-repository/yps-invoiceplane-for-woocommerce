<?php if(isset($this->view['page_title'])): ?>
<div class="row">
    <div class="col-12 text-center mt-5 mt-2">
        <h2><?php echo $this->view['page_title']; ?></h2>
    </div>
</div>
<?php endif; ?>

<?php if($this->get_form()->has_alerts(\YPS\Framework\Form\v346_950_484\Form::ALERT_TYPE_ERROR)): ?>
    <div class="alert alert-danger" role="alert">
        <div class="mb-3"><?php _e("Check the following errors and click on 'Save':", 'yps-framework-form'); ?></div>
        <?php foreach($this->get_form()->get_alerts_by_type(\YPS\Framework\Form\v346_950_484\Form::ALERT_TYPE_ERROR) as $field_name => $errors): ?>

            <?php foreach($errors as $error_message): ?>
                <div>- <?php echo $error_message['message']; ?></div>
            <?php endforeach; ?>

        <?php endforeach; ?>
    </div>
<?php endif; ?>



<?php if($this->get_form()->has_alerts(\YPS\Framework\Form\v346_950_484\Form::ALERT_TYPE_WARNING)): ?>
    <div class="alert alert-warning" role="alert">
        <?php foreach($this->get_form()->get_alerts_by_type(\YPS\Framework\Form\v346_950_484\Form::ALERT_TYPE_WARNING) as $field_name => $warnings): ?>

            <?php foreach($warnings as $warning_message): ?>
                <div>- <?php echo $warning_message['message']; ?></div>
            <?php endforeach; ?>

        <?php endforeach; ?>
    </div>
<?php endif; ?>


<?php if(!empty($this->view['current_message'])): ?>
    <div class="alert alert-success" role="alert">
        <?php echo $this->view['messages'][$this->view['current_message']]; ?>
    </div>
<?php endif; ?>
