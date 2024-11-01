jQuery(document).ready(function($){
    
    YPS_Framework = window.YPS_Framework || {};
    
    YPS_Framework.File_Upload = function(upload_url){

        this.set_delete_url = function(delete_url){
            this.delete_url = delete_url;
        };
        
        this.add_upload_progress = function(upload_field, filename, upload_id){
            var model   = $(".yps-file-upload-model").first().clone();
            
            $(model).removeClass("yps-file-upload-model");
            $(model).addClass("yps-file-upload-element");
            
            $(model).find(".delete").attr('data-filename', filename);
            $(model).find(".delete").attr('data-upload-id', upload_id);
            $(model).find(".title").html(filename);
            $(model).show();

            var appendElement   = $(upload_field).closest(".yps-file-upload").find(".yps-file-upload-target");
            
            console.log(appendElement);
            
            return $(model).appendTo($(appendElement));
        };
        
        this.set_upload_progress = function(element, percentage){
            $(element)
                    .find(".progress-bar")
                    .html(percentage+'%')
                    .css('width', percentage+'%')
                    .attr('aria-valuenow', percentage);
        };
        
        this.construct = function(){

            var file_upload     = this;
            
            /* Add Button Click: It trigger the change event */
            $(".yps-file-upload-add-button").click(function() {
                $(this).closest(".yps-file-upload").find("input[type='file']").trigger('click');
            });
            
            /* Delete Button Click */
            $(document).on('click', ".yps-file-upload-element .delete", function(){
                
                var delete_button       = $(this);
                var filename            = $(this).attr('data-filename');
                var upload_id           = $(this).attr('data-upload-id');
                
                $(delete_button).attr('disabled', true);
                
                $.ajax({
                    type:'POST',
                    url: file_upload.delete_url,
                    data: {
                        'filename': filename,
                        'upload_id': upload_id
                    },
                    dataType: "json",
                    success:function(data){
                        $(delete_button).closest(".yps-file-upload-element").remove();
                    },

                    error: function(data){
                        console.log(data);
                    }
                });
                
                
            });
            
            /* Add Button Click */
            $(".yps-file-upload input[type='file']").change(function(e){
                
                var progress_bars    = [];
                var upload_field     = $(this);
                
                /* Hide of previous errors */
                $.each($(".yps-file-upload-element"), function(index, element){
                    if($(element).find('[data-error="1"]').length){
                        $(element).hide();
                    }
                }); 

                $.each($(this)[0].files, function(index, element){

                    var form_data                = new FormData();
                    
                    var accept_files             = $(upload_field).attr('data-accept-files');
                    var custom_parameters        = $(upload_field).attr('data-custom-parameters');

                    try {
                        var accept_files_array        = $.parseJSON(accept_files);
                    }catch(e){
                        alert("data-accept-files is not defined in HTML component!");
                        return false;
                    }

                    var file_extension          = element.name.substr(element.name.lastIndexOf('.') + 1).toLowerCase();
                    var max_files_size          = $(upload_field).attr('data-max-files-size');
                    var upload_id               = $(upload_field).attr('data-upload-id');
                    
                    if(max_files_size == ""){
                        alert("data-max-files-size is not defined in HTML component!");
                        return false;
                    }
                    
                    if(upload_id == ""){
                        alert("data-upload-id is not defined in HTML component!");
                        return false;
                    }

                    form_data.append('file', element);
                    form_data.append('upload_id', upload_id);
                    form_data.append('custom_parameters', custom_parameters);
                    
                    progress_bars[index]     = file_upload.add_upload_progress(upload_field, element.name, upload_id);

                    if(accept_files_array.indexOf(file_extension) == -1){
                        $(progress_bars[index])
                                .find(".error")
                                .attr("data-error", "1")
                                .html("Supported file extensions are: " + accept_files);
                        
                        $(progress_bars[index])
                                .find(".delete")
                                .hide();
                        
                        return;
                        
                    }
                    
                    if(element.size > max_files_size){
                        $(progress_bars[index])
                                .find(".error")
                                .attr("data-error", "1")
                                .html("Max allowed file size is " + max_files_size + " bytes");
                        
                        $(progress_bars[index])
                                .find(".delete")
                                .hide();
                        
                        return;
                    }
                    
                    $.ajax({
                        type:'POST',
                        url: file_upload.upload_url,
                        data:form_data,
                        dataType: "json",
                        xhr: function() {
                                var myXhr = $.ajaxSettings.xhr();

                                if(myXhr.upload){
                                    myXhr.upload.addEventListener('progress',function(e){
                                        if(e.lengthComputable){
                                            var max         = e.total;
                                            var current     = e.loaded;

                                            var percentage = Math.round((current * 100)/max);

                                            file_upload.set_upload_progress(progress_bars[index], percentage);

                                            if(percentage >= 100){
                                               // process completed  
                                            }
                                        }  
                                    }, false);
                                }

                                return myXhr;
                        },
                        cache: false,
                        contentType: false,
                        processData: false,

                        success:function(data){

                            if(data.message){
                                $(progress_bars[index])
                                        .find(".error")
                                        .html(data.message);

                                return;
                            }

                            if(data == ""){
                                $(progress_bars[index])
                                        .find(".error")
                                        .html("Unable to upload file");
                                
                                return;
                            }
                            
                            if(data.name){
                                
                                $(progress_bars[index])
                                        .find(".progress")
                                        .html("<span class=\"success\">The file has been uploaded</span>");
                            }

                        },

                        error: function(data){
                            console.log(data);
                        }
                    });
                    
                    

                
                    console.log(element);
                });
                
                /* Empty the upload field */
                $(this).val('');
                
                e.preventDefault(true);
            });

        };
        
        this.delete_url         = null;
        this.upload_url         = upload_url;
        
        this.construct();
    };
    
});