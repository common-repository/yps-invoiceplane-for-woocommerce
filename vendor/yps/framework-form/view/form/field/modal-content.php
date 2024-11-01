<div 
    class="yps-framework-form-modal-content remodal <?php echo implode($this->view['field']->get_classes()); ?>" 
    data-remodal-name="<?php echo $this->view['field_name']; ?>"
    data-remodal-id="<?php echo $this->view['field_name']; ?>"
    data-remodal-options="<?php echo $this->view['field']->get_remodal_options() ;?>"
    data-remodal-params="<?php (isset($this->view['modal_params']))?$this->get_view_helper()->html_json_encode($this->view['modal_params']):""; ?>"
    >


    <button data-remodal-action="close" class="remodal-close"></button>
    
    <div class="yps-bootstrap yps-plugin">
        <div class="container-fluid">
            <?php if(!empty($this->view['field']->get_title())): ?>
                <div class="row mt-3 mb-3">
                    <div class="col-sm-12 mx-auto">
                        <h2><?php echo $this->view['field']->get_title(); ?></h2>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row mt-3 mb-3">
                <div class="col-sm-12 modal-view">
                    <?php echo $this->view['field']->get_modal_view(); ?>
                </div>
            </div>

            <?php if(!empty($this->view['field']->get_cancel_button_text()) || !empty($this->view['field']->get_confirm_button_text())): ?>
                <div class="row mt-3 mb-3">
                    <div class="col-sm-12 text-center mx-auto">
                        <?php if(!empty($this->view['field']->get_cancel_button_text())): ?>
                            <button data-remodal-action="cancel" class="remodal-cancel btn btn-danger">
                                <?php echo $this->view['field']->get_cancel_button_text(); ?>
                            </button>
                        <?php endif; ?>

                        <?php if(!empty($this->view['field']->get_confirm_button_text())): ?>
                            <button data-remodal-action="confirm" class="remodal-confirm btn btn-primary">
                                <?php echo $this->view['field']->get_confirm_button_text(); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>

    </div>


</div>