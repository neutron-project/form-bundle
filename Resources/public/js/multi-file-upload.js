/**
 * Initialization of multi file upload widget
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){
    
    /**
     * Creating buttons
     */
    jQuery('.neutron-file-upload-button').button();


    /**
     * Searching for multi file upload elements
     */
    jQuery('.neutron-multi-file-upload').each(function(key, value){
        
        var options = jQuery(this).data('options');  
        var prototype = jQuery(this).attr('data-prototype');
        
        jQuery('#neutron-file-btn-upload-' + options.id).click(function(){
            return false;
        });

        /**
	 * Disables buttons
	 */
        var disableButtons = function(){
            jQuery('#neutron-file-btn-enabled-' + options.id).button( "option", "disabled", true ).data(null);
            jQuery('#neutron-file-btn-remove-' + options.id).button( "option", "disabled", true ).data(null);
            jQuery('#neutron-meta-btn-edit-' + options.id).button( "option", "disabled", true ).data(null);
            jQuery('#neutron-dlg-meta-edit-' + options.id).data(null);
        };

        /**
	 * Enables buttons
	 */
        var enableButtons = function(data){
            jQuery('#neutron-file-btn-enabled-' + options.id).button( "option", "disabled", false ).data(data);
            jQuery('#neutron-file-btn-remove-' + options.id).button( "option", "disabled", false ).data(data);
            jQuery('#neutron-meta-btn-edit-' + options.id).button( "option", "disabled", false ).data(data);
            jQuery('#neutron-dlg-meta-edit-' + options.id).data(data);
        };

        var toggleContainer = function(){
            var elmCount = jQuery('#neutron-multi-file-upload-container-' + options.id).children().length;
            console.log(elmCount); 
            if(elmCount === 0){
                jQuery('#drag-drop-area-' + options.id).fadeIn(function(){
                    jQuery('body').trigger('refreshPlUpload');
                });
            } else {
                jQuery('#drag-drop-area-' + options.id).fadeOut(function(){
                    jQuery('body').trigger('refreshPlUpload');
                });
            }
        };

        var showError = function(errorMsg)
        {
            jQuery('#neutron-multi-file-upload-error-' + options.id)
                .fadeIn(function(){
                    jQuery('body').trigger('refreshPlUpload');
                })
                .find('.neutron-fileupload-error')
                .html(errorMsg);

            jQuery('#neutron-file-btn-upload-' + options.id).button( "option", "disabled", true );
            disableButtons();
        };
        
        /**
         * Check if any errors displayed
         */
        var hasError = function(){
            return jQuery('#neutron-multi-file-upload-error-' + options.id).is(':visible');
        }

        disableButtons();

        /**
         * Initializing sortable
         */
        jQuery( "#neutron-multi-file-upload-container-" + options.id ).sortable({
            placeholder: "ui-state-highlight",
            forcePlaceholderSize:true,
            update: function(event, ui){
                var elms = jQuery('#neutron-multi-file-upload-container-' + options.id + ' li');
                elms.each(function(key, value){
                    jQuery(this).find(':hidden').eq(7).val(key);
                });
            }
        });
        
        jQuery( "neutron-multi-file-upload-container-" + options.id).disableSelection();

        /**
         * Toggle enabled button
         */
        var toggleActive = function(elm){
            var button = jQuery('#neutron-file-btn-enabled-' + options.id);
            var elm = elm.find(':hidden').eq(8); 
            
            if(elm.val() == 0){
                button.removeClass('ui-icon-bullet').addClass('ui-icon-radio-on');
            } else {
                button.removeClass('ui-icon-radio-on').addClass('ui-icon-bullet');
            }
        };

        /**
         * Registering double click event on all existing files
         */
        jQuery("#neutron-multi-file-upload-container-"+ options.id +" li").live('dblclick', function(event) {
            jQuery(this).addClass("selected").siblings().removeClass("selected");
            toggleActive(jQuery(this));
            enableButtons({
                elm: jQuery(this)
            });
        });


        /**
	 * Configuring uploader
	 */
        var uploader = new plupload.Uploader({
            runtimes : options.runtimes,
            multi_selection:true,
            multiple_queues : true,
            dragdrop : true,
            drop_element: 'drag-drop-area-' + options.id,
            max_file_count : options.maxUploadedFiles,
            browse_button : 'neutron-file-btn-upload-' + options.id,
            multipart: true,
            multipart_params: {
                neutron_id: options.id
            },
            url : options.upload_url,
            flash_swf_url : options.plupload_flash_path_swf
        });
        
        jQuery('body').bind('refreshPlUpload', function(){
            uploader.refresh();
        });

        /**
	 * Uploader Event: Init
	 */
        uploader.bind('Init', function(up, params) {
            var availableDragAndDrop = ['gears', 'html5'];
            if(jQuery.inArray(params.runtime, availableDragAndDrop) == -1){
                jQuery('#neutron-drag-drop-info-' + options.id)
                    .text(jQuery('#neutron-drag-drop-info-' + options.id).attr('trans-no-files'));
            } else {
                jQuery('#neutron-drag-drop-info-' + options.id)
                    .text(jQuery('#neutron-drag-drop-info-' + options.id).attr('trans-drag'));
            }
        });


        /**
	 * Uploader Event: FilesAdded
	 * 
         */
        uploader.bind('FilesAdded', function(up, files) {

            jQuery('#drag-drop-area-' + options.id).fadeOut();

            jQuery.each(files, function(i, file) {
                var html = jQuery('#neutron-progressbar-prototype-' + options.id).html()
                    .replace('__file_name__', file.name)
                    .replace('__file_size__', plupload.formatSize(file.size))
                    .replace(/__id__/g, file.id);

                jQuery('#multi-file-progress-' + options.id).append(html).queue(function(){
                    jQuery('#' + file.id).progressbar();
                    jQuery('#neutron-multi-upload-remove-file-' + file.id).live('click', function(){
                        uploader.removeFile(file);
                        jQuery('#' + file.id).fadeOut().next().fadeOut(function(){
                            jQuery('#' + file.id).next().remove().end().remove();
                        });
 
                        return false;
                    });
                
                    jQuery(this).dequeue();
                });
            });

            jQuery('#neutron-file-btn-upload-' + options.id).button( "option", "disabled", true );
            if(jQuery("#neutron-multi-file-upload-container-"+ options.id).find('.selected').length == 1){
                disableButtons();
            } 

            setTimeout(function () {
                up.start();
            }, 100);
            
            jQuery('body').trigger('refreshPlUpload');

        });

        /**
	 * Uploader Event: UploadProgress
	 */
        uploader.bind('UploadProgress', function(up, file) {

            var percent = file.percent;

            jQuery('#' + file.id).progressbar("option", "value", percent);

            jQuery('#' + file.id).next().find('strong').html(percent + '%');
            
        });

        /**
	 * Uploader Event: FileUploaded
	 */
        uploader.bind("FileUploaded", function(up, file, response) {
            /**
             * response from server
	     */
            var data = jQuery.parseJSON(response.response); 

            if(data.success === false){ 
                uploader.stop();
                showError(file.name+ ': ' + data.err_msg);

            } else if(data.success == true){
                var collectionHolder = jQuery('#neutron-multi-file-upload-container-' + options.id);
                
                var elementIdx = 0;
                
                if(collectionHolder.find('li').length > 0){
                    jQuery.each(collectionHolder.find('li'),function(k,v){
                        var idx = jQuery(this).data('index');
                        if(idx >= elementIdx){
                            elementIdx = idx + 1;
                        }
                    });
                }
                
                var elm = prototype.replace(/__name__/g, elementIdx);
                
                var elmHolder = jQuery('#neutron-multi-file-prototype-' + options.id).html()
                    .replace('__name__', file.name)
                    .replace('__size__', plupload.formatSize(file.size));
                    
                collectionHolder.append(jQuery('<li data-index="'+ elementIdx +'">'+ elmHolder +'</li>').append(elm));
                
                var formElm = collectionHolder.find('[data-index="'+ elementIdx +'"]').find(':hidden');
            
                formElm.eq(0).val(data.name);			
                formElm.eq(1).val(file.name);			
                formElm.eq(2).val(file.size);			
                formElm.eq(6).val(data.hash);
                formElm.eq(7).val(parseInt(collectionHolder.children().length) - 1);
                formElm.eq(8).val(0);


            }

            jQuery('#' + file.id).fadeOut().next().fadeOut(function(){
                jQuery('#' + file.id).next().remove().end().remove();
            });

        });

        /**
         * PlUpload Event: UploadComplete
         */
        uploader.bind("UploadComplete", function(up, files){

            jQuery('#multi-file-progress-' + options.id).children().fadeOut().end().html('');
            jQuery('#neutron-file-btn-upload-' + options.id).button( "option", "disabled", false);
            if(jQuery("#neutron-multi-file-upload-container-"+ options.id).find('.selected').length == 1){
                enableButtons();
            }
            toggleContainer();
        });

        /**
         * Initializing uploader
	 */
        uploader.init();
        
        jQuery('body').bind('refreshPlUpload', function(){
            uploader.refresh();
        });

        /**
	 * Closes the error message and starts the upload of remaining items.
	 */
        jQuery('#neutron-multi-upload-error-cancel-' + options.id).click(function(){
            jQuery('#neutron-multi-file-upload-error-' + options.id)
            .fadeOut(function(){
                jQuery('body').trigger('refreshPlUpload');
                if(uploader.files.length > 0){
                    uploader.start();
                } else {
                    jQuery('#neutron-file-btn-upload-' + options.id).button('option', 'disabled', false);
                    toggleContainer();
                }
            });
            
            return false;
        });

        /**
	 * Active file handler
	 */
        jQuery('#neutron-file-btn-enabled-' + options.id).click(function(){
            var elm = jQuery(this).data().elm;
            var activeElm = elm.find(':hidden').eq(8);
            
            
            if(jQuery(this).hasClass('ui-icon-bullet')){
                jQuery(this).removeClass('ui-icon-bullet').addClass('ui-icon-radio-on');
                activeElm.val(0);
            } else {
                jQuery(this).removeClass('ui-icon-radio-on').addClass('ui-icon-bullet');
                activeElm.val(1);
            }
            
            return false;
        });



        /**
	 * Configuring dialog meta information
	 */
        jQuery("#neutron-dlg-meta-edit-" + options.id).dialog({
            'autoOpen' : false,
            'modal' : true,
            'width' : 'auto',
            close: function(event, ui) { 
                var elm = jQuery(this).data().elm.find(':hidden');
                elm.eq(3).val(jQuery('#neutron-meta-title-' + options.id).val());
                elm.eq(4).val(jQuery('#neutron-meta-caption-' + options.id).val());
                elm.eq(5).val(jQuery('#neutron-meta-description-' + options.id).val());
            }
        });

        /**
	 * Opens dialog file edit meta
	 */
        jQuery('#neutron-meta-btn-edit-' + options.id).click(function(){

            var elm = jQuery(this).data().elm.find(':hidden');

            jQuery('#neutron-meta-title-' + options.id).val(elm.eq(3).val());
            jQuery('#neutron-meta-caption-' + options.id).val(elm.eq(4).val());
            jQuery('#neutron-meta-description-' + options.id).val(elm.eq(5).val());
            jQuery('#neutron-dlg-meta-edit-' + options.id).dialog('open');

        });

        jQuery('#neutron-edit-dlg-done-btn-' + options.id).click(function(){
            jQuery('#neutron-dlg-meta-edit-' + options.id).dialog('close');
        });

        /**
	 * Remove button handler
	 */
        jQuery('#neutron-file-btn-remove-' + options.id).click(function(event){
            jQuery(this).data().elm.fadeOut(function(){
                jQuery(this).remove();
                toggleContainer();
                var elms = jQuery('#neutron-multi-file-upload-container-' + options.id).children();
                elms.each(function(key, value){
                    jQuery(this).find(':hidden').eq(7).val(key);
                })
            });

            disableButtons();

        });

    });

    jQuery('.neutron-multi-file-upload-main').fadeIn(1000);    
});