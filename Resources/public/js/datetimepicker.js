/**
 * Initialization of jquery datetime picker
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){
    
    // Searching for datetimepicker elements
    jQuery('.neutron-datetimepicker').each(function(key, value){
        
        var options = jQuery(this).data('options'); 
        var lang = options.lang;
        
            jQuery.datepicker
                .setDefaults(jQuery.datepicker.regional[(lang == 'en') ? 'en-GB' : lang]);
            
            jQuery.timepicker
                .setDefaults(jQuery.timepicker.regional[(lang == 'en') ? null : lang]);

        jQuery('#' + options.id).datetimepicker(options);

    });
});


