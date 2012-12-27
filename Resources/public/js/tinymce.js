/**
 * Initialization of jquery tinymce
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){
    	
    // Searching for tinymce elements
    jQuery('.neutron-tinymce').each(function(key, value){  
        var options = jQuery(this).data('options'); 
        var element = jQuery('#' + options.id);
        element.tinymce({
            // Location of TinyMCE script
            script_url : options.base_url + options.tiny_mce_path_js,

            // General options
            theme : options.theme,
            skin : options.skin,
            skin_variant : options.skin_variant,
            width: options.width,
            height: options.height,
            dialog_type : options.dialog_type,
            readonly: options.readOnly,

            plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions," +
                      "iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print," +
                      "contextmenu,paste,directionality,fullscreen,noneditable,visualchars," +
                      "nonbreaking,xhtmlxtras,template",

            // Theme options
            theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|," +
                                      "justifyleft,justifycenter,justifyright,justifyfull,styleselect," +
                                      "formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|," +
                                      "outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup," +
                                      "help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell," +
                                      "media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr," +
                                      "acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,

            file_browser_callback : function(field_name, url, type, win) {

                if(!options.filemanager){
                    return false;
                }

                tinyMCE.activeEditor.windowManager.open(
                {
                    url: options.base_url + options.ajaxfilemanager_path_php + "?editor=tinymce&language=" + options.lang,
                    width: 782,
                    height: 460,
                    inline : "yes",
                    close_previous : "yes"
                },
                {
                    window : win,
                    input : field_name
                });		
            },

            // Example content CSS (should be your site CSS)
            content_css : options.content_css ? options.base_url + options.content_css : null,
            relative_urls : false,
            convert_urls : true,    
            language : options.lang

        });

    });
 
});


