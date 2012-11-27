/**
 * Initialization of jquery buttonset
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){
    
    //Searching for buttonset elements
    jQuery('.neutron-buttonset').each(function(key, value){
        var options = jQuery(this).data('options');
        jQuery('#' + options.id).buttonset(options);
    });
});


