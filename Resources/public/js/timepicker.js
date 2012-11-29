/**
 * Initialization of jquery timepicker
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){
    
	// Searching for timepicker elements
    jQuery('.neutron-timepicker').each(function(key, value){
        var options = jQuery(this).data('options'); console.log(options);
        var lang = options.lang;

        jQuery.timepicker.setDefaults(jQuery.timepicker.regional[(lang == 'en') ? null : lang]);
        
        jQuery('#' + options.id).timepicker(options);
    });
});


