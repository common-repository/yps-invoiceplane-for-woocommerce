jQuery(document).ready(function($){
    
    YPS_Framework = window.YPS_Framework || {};
    
    YPS_Framework.Helper = function(){
            
            /**
             * Get the site url.
             *
             * @return string
             */
            this.get_site_url   = function(){
                return YPS_FRAMEWORK_HELPER_HANDLE.site_url;
            };
            
            /**
             * Return the admin page url.
             *
             * @param string $page "page={$page}"
             * @param object $params List of parameters, example {"controller":"CN_NAME", "action":"ACTION_NAME"}
             * @return string
             */
            this.get_admin_url  = function(page, params){
                var admin_url   = YPS_FRAMEWORK_HELPER_HANDLE.admin_url;
                var page_url    = this.get_page_url(page, params);
                
                var url         = admin_url + "admin.php?page=" + page_url;

                return url;
            };
            
            /**
             * Return the page url: "{$page}?arg1=val1&arg2=val2.
             *
             * @param string $page "{$page}"
             * @param object $params List of parameters, example {"controller":"CN_NAME", "action":"ACTION_NAME"}
             * @return string
             */
            this.get_page_url   = function(page, params){
                var url     = page;
                
                if(params != undefined){
                    $.each(params, function(key, value){
                        if(value != ''){
                            url += '&' + key + '=' + value;
                        }
                    });
                }
                
                return url;
            };
            
            /**
             * Convert URL params to JS Object
             * 
             * @param string url_string
             * @returns Object
             */
            this.get_object_from_url_params   = function(url_string){
                
                var decoded_url_string  = this.decode_url(url_string);

                decoded_url_string      = decoded_url_string.replace(/&/g, "\",\"");
                decoded_url_string      = decoded_url_string.replace(/=/g,"\":\"");
                
                decoded_url_string      = decoded_url_string.replace(/\n/g, "\\n");
                decoded_url_string      = decoded_url_string.replace(/\r/g, "\\r");
                
                console.log('{"' + decodeURI(decoded_url_string) + '"}');
                
                return JSON.parse('{"' + decodeURI(decoded_url_string) + '"}');
                
            };
            
            /**
             * Convert Object into URL params
             * 
             * @param Object object
             * @returns string
             */
            this.get_url_params_from_object   = function(object){
                return $.param(object);
            };
            
            /**
             * Decode URL. It also remove the "+" sign when decoding
             * 
             * @param string str
             * @returns string
             */
            this.decode_url                   = function(str) {
                return decodeURIComponent((str+'').replace(/\+/g, ' '));
            };

            /**
             * Merge defaults with user options
             * @private
             * @param {Object} defaults Default settings
             * @param {Object} options User options
             * @returns {Object} Merged values of defaults and options
            */
            this.extend = function ( defaults, options ) {
                var extended = {};
                var prop;
                for (prop in defaults) {
                    if (Object.prototype.hasOwnProperty.call(defaults, prop)) {
                        extended[prop] = defaults[prop];
                    }
                }
                for (prop in options) {
                    if (Object.prototype.hasOwnProperty.call(options, prop)) {
                        extended[prop] = options[prop];
                    }
                }
                return extended;
            };

    };
      
    

});