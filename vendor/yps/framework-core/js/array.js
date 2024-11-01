YPS_Framework.Core.Array   = class {

    static in_array(needle, haystack){

        for (var i = 0; i < haystack.length; i++) {
            if(haystack[i] == needle){
                return true;
            }
        }

        return false;
    }

    static array_move(arr, old_index, new_index){

        if(new_index >= arr.length){
            
            var k = new_index - arr.length + 1;

            while(k--){
                arr.push(undefined);
            }
        }

        arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);

        return arr; // for testing
    };
    
}