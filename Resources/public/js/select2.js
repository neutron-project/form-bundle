/**
 * Initialization of jquery select2 widget
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){
    
    //Searching for select2 elements
    jQuery('.neutron-select2').each(function(key, value){
        var options = jQuery(this).data('options'); 
        var id = options._id;
        delete options._id;
        
        var firstOpt = jQuery('#' + id + ' option').eq(0); 

        if(firstOpt.text() === options.placeholder){
            firstOpt.text(''); 
        }
        

        if(options.ajax != undefined){
        	
	        options.initSelection = function (element, callback) {
	        	if(options.multiple === true){
	            	var data = [];
	                jQuery(element.val().split(",")).each(function () {
	                    data.push({id: this, text: this});
	                });
	            } else {
	            	var data = {id: element.val(), text: element.val()};
	            }
	        	
	            callback(data);
	            return false;
	        };
        }
        
        var evaluateFn = function(options){
        	jQuery.each(options, function(k,v){
        		if(typeof(v) == 'string' && isNaN(v)){
        			if(v.match('^function')){
        				eval('options.'+ k +' = ' + v);
        			}
                } else if(jQuery.isPlainObject(v)){
        			evaluateFn(v);
        		}
        	});
        };
        
        evaluateFn(options);

        jQuery('#' + id).select2(options);
        
    });
});


