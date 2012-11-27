/**
 * Initialization of jquery autocomplete
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){
    
	// building categories
    jQuery.widget( "custom.catcomplete", jQuery.ui.autocomplete, {
    	_renderMenu: function( ul, items ) {
            var that = this,
                currentCategory = "";
            jQuery.each( items, function( index, item ) {
                if ( item.category != currentCategory ) {
                    ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
                    currentCategory = item.category;
                }
                that._renderItemData( ul, item );
            });
        }
    });
    
    //Searching for autocomplete elements
    jQuery('.neutron_autocomplete').each(function(key, value){
        var options = jQuery(this).data('options'); 
        var el = jQuery(this).prev();

        if(options.use_categories){
            el.catcomplete(options);
        } else {
            el.autocomplete(options);
        }
    });
}); 


