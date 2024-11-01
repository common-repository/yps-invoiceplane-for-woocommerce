YPS_Framework.Form.Modal_Form_Field        = class {

    constructor(){
        jQuery(document).on('closed', '.yps-framework-form-modal-content', function (e) {

            jQuery(document).trigger("yps_framework_form_modal_form_field_closed", {
                'name':            jQuery(this).data('remodal-name'),
                'modal':           jQuery(this),
                'reason':           e.reason,
            });

        });
    }

}

jQuery(document).ready(function($){
    new YPS_Framework.Form.Modal_Form_Field();
});