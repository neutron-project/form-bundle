/**
 * Initialization of jquery spinner widget
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){

    //Searching for spinner widgets
    jQuery('.neutron-spinner').each(function(key, value){
        var options = jQuery(this).data('options'); 
        var el = jQuery('#' + options.id);

        el.spinner(options);
    });
}); 


