jQuery(document).ready(function($){
    
    YPS_Framework = window.YPS_Framework || {};
        
    YPS_Framework.Record = function(){

        var $this                           = this;
        this.request_ajax_data_table        = null;
        
        this.construct  = function(){
            
            this.init_list_table();
            this.init_numeric_elements();
            
            $(document).on('click', '.yps-record-delete', function(e){
                e.preventDefault();
                
                if(confirm("Are you sure you want to delete this record?") == true){
                    window.location     = $(this).attr('href');
                }
            });
        };
        
        this.init_list_table        = function(){
            
            $.each($(".yps-record-table"), function(index, table_element){
                
                var filters_selector    = $(table_element).attr('data-filters-selector');
                var default_order_by    = $(table_element).attr('data-default-order-by');
                var default_order_dir   = $(table_element).attr('data-default-order-dir');

                var columns             = [];

                /* Get the class for each column */
                $.each($(this).find("th[data-row-class]"), function(index, value){
                    columns.push({className: $(this).attr('data-row-class').trim()});
                });
                

                var table           = $(this).DataTable({
                    "paging"            : true,
                    "processing"        : true,
                    "bProcessing"       : true,
                    "retrieve"          : true,
                    "serverSide"        : true,
                    "language"          : YPS_DATA_TABLES.lang,
                    "columns"           : columns,
                    "initComplete"      : function( settings, json ) {
                        $(document).trigger('yps_record_table_init_complete');
                    },
                    "ajax": {
                        url     : $(this).attr('data-url'),
                        data    : function (d){

                            console.log(d);

                            $.each($(filters_selector), function(index, filter){
                                d[$(filter).attr('id')]     = $(filter).val();
                            });

                        },
                        beforeSend : function(jqXHR, settings) {

                            var table_id        = $(table_element).attr('id');
                            var processing_id   = "#" + table_id + "_processing";
                            
                            if($this.request_ajax_data_table && $this.request_ajax_data_table.readyState != 4){
                                $this.request_ajax_data_table.abort();
                            }

                            $this.request_ajax_data_table   = jqXHR;

                            $(processing_id).show();
                        }
                    },

                });

                

                if(filters_selector != ''){
                    $(document).on('change', filters_selector, function(){
                        table.draw();
                    });
                }


                /* Ordering by column "ordering" */
                table.order([default_order_by, default_order_dir]).draw();
                
            });

        };

        this.init_numeric_elements      = function(){
            $('.yps-record-integer').numeric(
                false,
                function () {
                    alert('Only integer values');
                    this.value = '';
                    this.focus();
                }
            );
    
            $('.yps-record-decimal').numeric({ 
                decimal : ".",
                decimalPlaces: 2,
                negative: false,
            });
        };


        this.construct();
    };

    new YPS_Framework.Record();
    
});
