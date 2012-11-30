/**
 * Initialization of input limiter widget
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){
    
    // Searching for input limiter elements
    jQuery('.neutron-input-limiter').each(function(key, value){  
        var options = jQuery(this).data('options'); 
        jQuery('#' + options.id).inputlimiter(options);
    });
    
});