/**
 * Initialization of jquery datepicker
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){
    
    // Searching for datepicker elements
    jQuery('.neutron-datepicker').each(function(key, value){
        var options = jQuery(this).data('options');
        var el = jQuery('#' + options.id);
        
        var lang = options.lang;
            jQuery.datepicker
            .setDefaults(jQuery
            .datepicker.regional[(lang == 'en') ? 'en-GB' : lang]);
    
        el.datepicker(options);
    });
});


