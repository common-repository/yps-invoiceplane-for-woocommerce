
YPS_Framework.Core.Loader   = class {

    jquery                  = null;

    constructor($){

        this.jquery           = $;
    }

    static show_full_page_loader(){

        $("body").append('<div class="yps-full-page-loader-overlay"></div>');
        $("body").append('<div class="yps-full-page-loader-image-wrapper"><div class="yps-full-page-loader-image"></div></div>');

    }

    static hide_full_page_loader(){
        $(".yps-full-page-loader-overlay").remove();
        $(".yps-full-page-loader-image-wrapper").remove();
    }

    static show_loader(selector){

        $(selector).addClass("yps-loader-relative");

        $(selector).append('<div class="yps-loader-overlay"></div>');
        $(selector).append('<div class="yps-loader-image-wrapper"><div class="yps-loader-image"></div></div>');
    }

    static hide_loader(selector){

        $(selector).removeClass("yps-loader-relative");

        $(selector).find('.yps-loader-overlay').remove();
        $(selector).find(".yps-loader-image-wrapper").remove();
    }

    static show_loader_icon(selector){
        $(selector).addClass("yps-loader-icon-relative");

        $(selector).append('<div class="yps-loader-icon-overlay"></div>');
        $(selector).append('<div class="yps-loader-image-icon-wrapper"><div class="yps-loader-image-icon"></div></div>');
    }

    static hide_loader_icon(selector){
        $(selector).removeClass("yps-loader-icon-relative");

        $(selector).find('.yps-loader-icon-overlay').remove();
        $(selector).find(".yps-loader-image-icon-wrapper").remove();
    }

}