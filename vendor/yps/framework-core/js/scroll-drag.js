jQuery(document).ready(function($){
    
    YPS_Framework = window.YPS_Framework || {};
        
    /**
     * Scroll the scrollbars, by dragging the mouse
     * 
     * @param jquery_selector draggable_element_selector The element you want to drag
     * @param jquery_selector element_to_move_selector The element you want to scroll
     */
    YPS_Framework.Scroll_Drag = function(draggable_element_selector, element_to_move_selector){
        
        this.curDown        = false;
        this.curYPos        = 0;
        this.curXPos        = 0;
        this.curPointer     = null;
        
        this.construct      = function(){
            
            var object      = this;
            
            $(draggable_element_selector).mousemove(function(m){
                if(object.curDown == true){
                    $(element_to_move_selector).scrollTop($(element_to_move_selector).scrollTop() + (object.curYPos - m.pageY)); 
                    $(element_to_move_selector).scrollLeft($(element_to_move_selector).scrollLeft() + (object.curXPos - m.pageX));
                }
            });

            $(draggable_element_selector).mousedown(function(m){
                object.curDown        = true;
                object.curYPos        = m.pageY;
                object.curXPos        = m.pageX;
                object.curPointer     = $(draggable_element_selector).css('cursor');

                $(draggable_element_selector).css('cursor', 'move');
            });

            $(window).mouseup(function(){
                object.stop();
            });
            
            $(document).bind("drop", function(){
                object.stop();
            });
        };
        
        this.stop           = function(){
            this.curDown = false;
            $(draggable_element_selector).css('cursor', this.curPointer);
        };

        this.construct();
    };
    
});
