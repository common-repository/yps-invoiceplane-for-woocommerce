<div class="yps-bootstrap yps-plugin">
    
    <div class="container-fluid">

        <?php if(isset($_GET['message'])): ?>
            <div class="alert alert-primary" role="alert">
                <?php echo esc_html($this->get_request('message')); ?>
            </div>
        <?php endif; ?>
        
        <?php if($this->view['list_show_card'] == true): ?>
            <div class="card">
                <div class="card-header">
                    <?php echo $this->get_list_header_view(); ?>
                </div>

                <div class="card-body">
                    <?php echo $this->get_framework_view('Record', 'record/list-content.php'); ?>
                </div>

                <div class="card-footer">
                <?php echo $this->get_list_footer_view(); ?>
                </div>
                
            </div>
        <?php else: ?>
            <?php echo $this->get_list_header_view(); ?>
            <?php echo $this->get_framework_view('Record', 'record/list-content.php'); ?>
            <?php echo $this->get_list_footer_view(); ?>
        <?php endif; ?>

    </div>
</div>