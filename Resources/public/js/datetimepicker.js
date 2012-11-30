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
    
    // Searching for datetimerangepicker elements
    jQuery('.neutron-datetimerangepicker').each(function(key, value){
    	var options = jQuery(this).data('options'); 
    	
    	var firstEl = jQuery('#' + options.id + '_' + options.first_name);
    	var secondEl = jQuery('#' + options.id + '_' + options.second_name);
    	
    	var onSelect = function(selectedDate) { 
            var date = new Date(selectedDate);
            
            if(this.id == options.id + '_' + options.first_name){
                secondEl.datepicker( "option", 'minDate', date );
            } else {
                firstEl.datepicker( "option", 'maxDate', date );
            }
        };
        
        firstEl.datepicker( "option", 'onSelect', onSelect);
        secondEl.datepicker( "option", 'onSelect', onSelect);      
    });
});


