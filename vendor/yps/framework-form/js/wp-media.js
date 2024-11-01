jQuery(document).ready(function($){
    
    YPS_Framework = window.YPS_Framework || {};
        
    YPS_Framework.WP_Media = function(){

        var $this       = this;

        this.construct  = function(){

            $(document).on('click', '.yps-wp-media-reset-button', function(e){
                var url_field               = $(this).closest(".yps-wp-media-wrapper").find(".yps-wp-media-url-field");

                $(url_field).val("");
            });

            $(document).on('click', '.yps-wp-media-button', function(e) {

                e.preventDefault();

                var image_preview_selector  = $(this).attr('data-image-preview-selector');
                var image_selector          = $(this).closest(".yps-wp-media-wrapper").find(".yps-wp-media-url-field");
                var image_empty             = $(this).attr('data-empty-img');

                var image_frame;
                if (image_frame) {
                    image_frame.dispose();
                }
                
                image_frame = wp.media({
                    title: 'Select Media',
                    multiple: false,
                    library: {
                        type: 'image',
                    }
                });
                
                /* On Image Library open */
                image_frame.on('open', function () {

                });
                
                /* On Image Library close */
                image_frame.on('close', function () {

                    var selection = image_frame.state().get('selection');
                
                    selection.each(function (attachment) {

                        var url_value;
                        var image_src;

                        if(attachment['attributes'].url) {
                            url_value       = attachment['attributes'].url;
                            image_src       = attachment['attributes'].url;
                        }else{
                            url_value       = attachment['attributes'].url;
                            image_src       = image_empty;
                        }

                        if($(image_preview_selector).length != 0){
                            $(image_preview_selector).attr('src', attachment['attributes'].url);
                        }
                            
                        if($(image_selector).length != 0){
                            $(image_selector).val(attachment['attributes'].url);
                        }

                    });
                
                });
                
                image_frame.open();
            });


        };

        this.construct();
    };

    new YPS_Framework.WP_Media();
    
});
