jQuery(document).ready(function($){
    
    YPS_Framework = window.YPS_Framework || {};
        
        /**
         * Creates a Data Updater
     * 
         * @param {type} ajaxDataUrl
         * @param {type} ajaxRequestData
     * @returns {YPS_Framework.Data_Updater}
         */
    YPS_Framework.Data_Updater = function(ajaxDataUrl, ajaxRequestData){
            this.ajaxDataUrl        = ajaxDataUrl;
            this.updaters           = [];
            this.ajaxRequestData    = ajaxRequestData;
            
            /*
             * updateMode: value | html
             */
            this.add_updater        = function(elementSelector, fieldId, updateMode){
                this.updaters.push({
                    elementSelector : elementSelector,
                    fieldId         : fieldId,
                    updateMode      : updateMode
                });
            };

            this.set_updater_data   = function(){
                var updaters        = this.updaters;

                $.ajax({
                    type:'POST',
                    url: this.ajaxDataUrl,
                    data: this.ajaxRequestData,
                    dataType: "json",
                    success:function(data){
                        $.each(updaters, function(index, updater){
                            
                            if(typeof updater.fieldId === "function"){
                                var value       = updater.fieldId(data);
                            }else{
                                var value       = data[updater.fieldId];
                            }

                            if(updater.updateMode == "value"){
                                $(updater.elementSelector).val(value);
                            }else{
                                $(updater.elementSelector).html(value);
                            }
                        });
                    },

                    error: function(data){

                    }
                });
            }
            
            
    };
        
    YPS_Framework.Data_Table_Selector = function(){
            this.destElement        = null;
            this.modalElement       = null;
            
            this.set_dest_element   = function(destElement){
                this.destElement    = destElement;
            };
            
            this.set_modal          = function(modalElement){
                this.modalElement   = modalElement;
            };
            
            this.attach             = function(){
                var destElement     = this.destElement;
                var modalElement    = this.modalElement;
                
                $(this.modalElement).on("click", ".data-table-select", function(){

                    var value           = $(this).attr('data-table-select-value');
                    
                    $(document).trigger("data-table-selector-click", {
                        'value': value,
                        'dest_element': destElement,
                        'modal_element': modalElement
                    });
                    
                    $(destElement).val(value);
                    $(modalElement).modal('hide');
                });
            };
    };
    
});
