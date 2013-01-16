/**
 * Initialization of image upload widget
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){ 
    
    // Creates buttons
    jQuery('.neutron-image-upload-button').button();

    // Searching for image upload elements
    jQuery('.neutron-image-upload').each(function(key, value){  
        var options = jQuery(this).data('options'); 

        jQuery('#neutron-image-btn-upload-' + options.id).click(function(){
            return false;
        });
      

        // Disables buttons
        var disableButtons = function(){
            jQuery('#neutron-image-btn-crop-' + options.id).button( "option", "disabled", true );
            jQuery('#neutron-image-btn-enabled-' + options.id).button( "option", {disabled: true});
            jQuery('#neutron-image-btn-view-' + options.id).button( "option", "disabled", true );
            jQuery('#neutron-image-btn-remove-' + options.id).button( "option", "disabled", true );
            jQuery('#neutron-image-btn-reset-' + options.id).button( "option", "disabled", true );
            jQuery('#neutron-meta-btn-edit-' + options.id).button( "option", "disabled", true );
            jQuery('#neutron-image-btn-rotate-' + options.id).button( "option", "disabled", true );
        };

        // Enables buttons
        var enableButtons = function(){
            jQuery('#neutron-image-btn-crop-' + options.id).button( "option", "disabled", false );
            jQuery('#neutron-image-btn-enabled-' + options.id).button( "option", {disabled:false});
            jQuery('#neutron-image-btn-view-' + options.id).button( "option", "disabled", false );
            jQuery('#neutron-image-btn-remove-' + options.id).button( "option", "disabled", false );
            jQuery('#neutron-image-btn-reset-' + options.id).button( "option", "disabled", false );
            jQuery('#neutron-meta-btn-edit-' + options.id).button( "option", "disabled", false );
            jQuery('#neutron-image-btn-rotate-' + options.id).button( "option", "disabled", false );
        };
                
        var showError = function(err_msg){
            jQuery('#neutron-image-upload-container-' + options.id)
                .find('.ui-state-error')
                .fadeIn(function(){
                    jQuery('body').trigger('refreshPlUpload');
                })
                .find('.neutron-imageupload-error')
                .html(err_msg);
            jQuery('#neutron-image-btn-upload-' + options.id).button( "option", "disabled", true );
            disableButtons();
        };
                
        // Check if any errors displayed
        var hasError = function(){
            return jQuery('#neutron-image-upload-container-' + options.id)
                .find('.ui-state-error').is(':visible');
        }
        
        // Activate enabled button
        var activate = function(){
            var button = jQuery('#neutron-image-btn-enabled-' + options.id);
            var elm = jQuery('#' + options.enabled_id); 
            if(button.hasClass('ui-icon-radio-on')){
                button.removeClass('ui-icon-radio-on').addClass('ui-icon-bullet');
                elm.val(1);
            }
        };
        
        // Deactivate enabled button
        var deactivate = function(){
            var button = jQuery('#neutron-image-btn-enabled-' + options.id);
            var elm = jQuery('#' + options.enabled_id); 
            if(button.hasClass('ui-icon-bullet')){
                button.removeClass('ui-icon-bullet').addClass('ui-icon-radio-on');
                elm.val(0);
            }
        };
        
        // Toggle enabled button 
        var toggleActive = function(){
            var button = jQuery('#neutron-image-btn-enabled-' + options.id);
            var elm = jQuery('#' + options.enabled_id); 
            if(button.hasClass('ui-icon-bullet')){
                button.removeClass('ui-icon-bullet').addClass('ui-icon-radio-on');
                elm.val(0);
            } else {
                button.removeClass('ui-icon-radio-on').addClass('ui-icon-bullet')
                    .attr('title', options.button_enabled_title);
                elm.val(1);
            }
        };
        
        jQuery('#neutron-image-btn-enabled-' + options.id).click(function(event){
            toggleActive();
        });
                
        // Colorbox handler
        jQuery('#neutron-image-btn-view-' + options.id).click(function(){ 
            jQuery.colorbox({
                href: options.base_url + options.dir + jQuery('#' + options.name_id).val() + '?t=' + new Date().getTime(), 
                title: jQuery('#' + options.title_id).val()
            });
            return false;
        });
                
        // Closes the error message.
        jQuery('#neutron-upload-error-cancel-' + options.id).click(function(){
            jQuery('#neutron-image-upload-container-' + options.id)
                .find('.ui-state-error')
                .fadeOut(function(){
                    jQuery('body').trigger('refreshPlUpload');
                });
            jQuery('#neutron-image-btn-upload-' + options.id).button( "option", "disabled", false );
            if(jQuery('#' + options.name_id).val() != ''){
                enableButtons();
            }
                    
            return false;
        });

        // Populate image meta data
        var populateMeta = function(){ 
            jQuery('#neutron-meta-title-' + options.id).val(jQuery('#' + options.title_id).val());
            jQuery('#neutron-meta-caption-' + options.id).val(jQuery('#' + options.caption_id).val());
            jQuery('#neutron-meta-description-' + options.id).val(jQuery('#' + options.description_id).val());                       
        };

        //  Reset image meta data
        var resetMeta = function(){
            jQuery('#' + options.title_id).val('');
            jQuery('#neutron-meta-title-' + options.id).val('');
            jQuery('#' + options.caption_id).val('');
            jQuery('#neutron-meta-caption-' + options.id).val('');
            jQuery('#' + options.description_id).val(''); 
            jQuery('#neutron-meta-description-' + options.id).val('');
        };

        // Checking if value is empty
        if(jQuery('#' + options.name_id).val() == ''){
            disableButtons();
        } else {
            jQuery('#neutron-image-' + options.id)
            .attr({
                'src': options.base_url + options.dir + jQuery('#' + options.name_id).val() 
                    +  '?t=' + new Date().getTime(), 
                width: options.minWidth, 
                height: options.minHeight
            });
            populateMeta();
        }

        // Progress bar
        var progressbar = jQuery('#neutron-progressbar-' + options.id).progressbar();

        // Configuring uploader
        var uploader = new plupload.Uploader({
            runtimes : options.runtimes,
            multi_selection:false,
            multiple_queues : false,
            dragdrop : true,
            drop_element: 'neutron-image-' + options.id,
            max_file_count : 1,
            browse_button : 'neutron-image-btn-upload-' + options.id,
            multipart: true,
            multipart_params: {
                neutron_id: options.id
            },
            container: 'neutron-image-upload-container-' + options.id,
            url : options.upload_url,
            flash_swf_url : options.plupload_flash_path_swf			

        });
            
        // Custom event used for refreshing (flash) plupload
        jQuery('body').bind('refreshPlUpload', function(){
            uploader.refresh();
        });

        // Uploader Event: FilesAdded We make sure one file is uploaded
        uploader.bind('FilesAdded', function(up, files) {

            var fileCount = up.files.length,
            i = 0,
            ids = jQuery.map(up.files, function (item) {
                return item.id;
            });

            for (i = 0; i < fileCount; i++) {
                uploader.removeFile(uploader.getFile(ids[i]));
            }

            setTimeout(function () {
                up.start();
            }, 100);

            jQuery('#neutron-upload-remove-image-' + options.id).find('a').attr('id', files[0].id);
                        
            var html = files[0].name + ' (' + plupload.formatSize(files[0].size) + ')';
                            
            jQuery('#neutron-image-info-'+ options.id).html(html);
        });


        // Uploader Event: UploadFile
        uploader.bind('UploadFile', function(up) { 
             jQuery('#neutron-image-btn-upload-' + options.id).button( "option", "disabled", true );
             disableButtons();
            progressbar.fadeIn().next().fadeIn(function(){
                jQuery('body').trigger('refreshPlUpload');
            });
                       
        });

        // Uploader Event: UploadProgress
        uploader.bind('UploadProgress', function(up, file) {
            jQuery('#neutron-progressbar-' + options.id).progressbar("option", "value", file.percent);
            jQuery('#neutron-progressbar-' + options.id).next().find('strong').html(file.percent + '%');

        });

        // Uploader Event: FileUploaded
        uploader.bind("FileUploaded", function(up, file, response) { 
            progressbar.fadeOut();

            // response from server
            var data = jQuery.parseJSON(response.response); 

            if(data.success === false){
                showError(data.err_msg);
                                
                if(jQuery('#' + options.name_id).val() == ''){
                    disableButtons();
                }

            } else if(data.success == true){
                jQuery('#' + options.name_id).val(data.name);
                jQuery('#' + options.hash_id).val(data.hash);
                jQuery('#neutron-image-' + options.id).fadeOut(function(){
                    jQuery(this).attr({
                        'src': options.base_url + options.dir 
                            + data.name +  '?t=' + new Date().getTime(), 
                        width: options.minWidth, 
                        height: options.minHeight
                    });
                }).fadeIn(function(){
                    jQuery('body').trigger('refreshPlUpload');
                });

                enableButtons();
                 jQuery('#neutron-image-btn-upload-' + options.id).button( "option", "disabled", false);
                resetMeta();
            }

            jQuery('#neutron-progressbar-' + options.id).next().fadeOut(function(){
                jQuery('body').trigger('refreshPlUpload');
            });
            

        });

        // Initializing uploader
        uploader.init();

        // Removes image from upload queue
        jQuery('#neutron-upload-remove-image-' + options.id).find('a').click(function(){
            uploader.removeFile(uploader.getFile(jQuery(this).attr('id')));
            jQuery('#neutron-progressbar-' + options.id).fadeOut().next().fadeOut(function(){
                enableButtons();
                jQuery('#neutron-image-btn-upload-' + options.id).button( "option", "disabled", false);
                jQuery('body').trigger('refreshPlUpload');
            });
            
            return false;
        });

        // Remove button click event
        jQuery('#neutron-image-btn-remove-' + options.id).click(function(){ 
            jQuery('#' + options.hash_id).val('');
            jQuery('#' + options.scheduled_for_deletion_id).val(true);
            resetMeta();
            disableButtons();
            jQuery('#neutron-image-' + options.id).fadeOut(function(){
                jQuery(this).attr({
                    'src': options.base_url + 'bundles/neutronform/images/noImage.png'
                })
                .removeAttr('width').removeAttr('height');
            }).fadeIn(function(){
                jQuery('body').trigger('refreshPlUpload');
            });
            
            deactivate();
            return false;
        });


        // Checking if user made selection on image
        var checkCoords = function ()
        {
            if (parseInt(jQuery('#w-' + options.id).val()) >= options.minWidth && 
                parseInt(jQuery('#h-' + options.id).val()) >= options.minHeight) {
                return true;
            }
            return false;
        };

        // Updates coordinates of cropper if there is a selection it enables crop button
        var updateCoords = function (c)
        { 
            jQuery('#x-' + options.id).val(c.x);
            jQuery('#y-' + options.id).val(c.y);
            jQuery('#w-' + options.id).val(c.w);
            jQuery('#h-' + options.id).val(c.h);
            if(!checkCoords()){ 
                jQuery('#neutron-crop-dlg-save-btn-' + options.id).button('disable');
            } else {
                jQuery('#neutron-crop-dlg-save-btn-' + options.id).button('enable');
            }
        };

        //  Reset coordinates of cropper to default ones
        var resetCoords =  function ()
        {
            jQuery('#x-' + options.id).val(0);
            jQuery('#y-' + options.id).val(0);
            jQuery('#w-' + options.id).val(options.minWidth);
            jQuery('#h-' + options.id).val(options.minHeight);
        };

        // Configuring crop dialog
        jQuery("#neutron-dlg-image-crop-" + options.id).dialog({
            'autoOpen' : false,
            'modal' : true,
            'width' : 'auto',
            close: function(event, ui) { 
                jcrop.destroy();
                resetCoords();
                jQuery('#neutron-image-crop-' + options.id).empty(); 
                if(hasError() === false){
                    jQuery('#neutron-image-btn-crop-' + options.id).button( "option", "disabled", false);
                }
				
            }
        });

        // Crop button click event
        jQuery('#neutron-image-btn-crop-' + options.id).click(function(){
            jQuery(this).button({disabled: true});
			

            var img = new Image();
            $(img).load(function () {
                $(this).css("display", "none"); 
                $(this).hide(); 
                jQuery('#neutron-image-crop-' + options.id).empty().append(this);
                $(this).fadeIn(function(){
                    jQuery('#neutron-image-crop-' + options.id).find('img').Jcrop({
                        setSelect: [0,options.minHeight,options.minWidth,0],
                        aspectRatio: options.minWidth / options.minHeight,
                        minSize: [options.minWidth, options.minHeight],
                        onChange: updateCoords
                    }, function(){
                        jcrop = this;
                    });

                    jQuery('#neutron-dlg-image-crop-' + options.id).dialog('open');
                });
            }).attr({src : options.base_url + options.dir 
                    + jQuery('#' + options.name_id).val() +  '?t=' + new Date().getTime()});
            
            return false;
        });

        // Triggers actual cropping.
        jQuery('#neutron-crop-dlg-save-btn-' + options.id).click(function(){
            var button = jQuery(this);
            button.button( "option", "disabled", true);
			
            jQuery.post(options.crop_url, {
                name: jQuery('#' + options.name_id).val(),
                x: jQuery('#x-' + options.id).val(),
                y: jQuery('#y-' + options.id).val(),
                w: jQuery('#w-' + options.id).val(),
                h: jQuery('#h-' + options.id).val()
            }, function(response){
                if(response.success === false){
                    showError(response.err_msg);
                } else if(response.success === true) {
                    jQuery('#neutron-image-' + options.id).fadeOut(function(){
                        jQuery(this).attr({
                            'src': options.base_url + options.dir + response.name +  
                            '?t=' + new Date().getTime(), 
                            width: options.minWidth, 
                            height: options.minHeight
                        });
                    }).fadeIn();
                    button.button( "option", "disabled", false); 
                    jQuery('#' + options.hash_id).val(response.hash);
                    
                }

				
                jQuery("#neutron-dlg-image-crop-" + options.id).dialog('close');

            });
        });

        // Closes crop dialog
        jQuery('#neutron-crop-dlg-cancel-btn-' + options.id).click(function(){
            jQuery("#neutron-dlg-image-crop-" + options.id).dialog('close');
        });

        /**
	 * Configuring dialog image meta information
	 */
        jQuery("#neutron-dlg-meta-edit-" + options.id).dialog({
            'autoOpen' : false,
            'modal' : true,
            'width' : 'auto',
            close: function(event, ui) { 
                jQuery('#' + options.title_id).val(jQuery('#neutron-meta-title-' + options.id).val());
                jQuery('#' + options.caption_id).val(jQuery('#neutron-meta-caption-' + options.id).val());
                jQuery('#' + options.description_id).val(jQuery('#neutron-meta-description-' + options.id).val());
            }
        });

        //  Opens dialog image edit meta
        jQuery('#neutron-meta-btn-edit-' + options.id).click(function(){ 
            jQuery('#neutron-dlg-meta-edit-' + options.id).dialog('open');
        });

        // Saves changes of image meta information and closes dialog
        jQuery('#neutron-edit-dlg-done-btn-' + options.id).button({
            icons: {
                primary: "ui-icon ui-icon-check"
            }
        }).click(function(){
            jQuery('#' + options.title_id).val(jQuery('#neutron-meta-title-' + options.id).val());
            jQuery('#' + options.caption_id).val(jQuery('#neutron-meta-caption-' + options.id).val());
            jQuery('#' + options.description_id).val(jQuery('#neutron-meta-description-' + options.id).val());
            jQuery('#neutron-dlg-meta-edit-' + options.id).dialog('close');
        });


       // Rotates image
        jQuery('#neutron-image-btn-rotate-' + options.id).click(function(){
            var button = jQuery(this);
            button.button( "option", "disabled", true );


            jQuery.post(options.rotate_url, {
                name: jQuery('#' + options.name_id).val()
            }, function(response){
                if(response.success === false){
                    showError(response.err_msg);
                } else if(response.success === true) {
                    jQuery('#' + options.hash_id).val(response.hash);

                    jQuery('#neutron-image-' + options.id).fadeOut(function(){
                        jQuery(this).attr({
                            'src': options.base_url + options.dir + response.name +  
                            '?t=' + new Date().getTime(), 
                            width: options.minWidth, 
                            height: options.minHeight
                        });
                    }).fadeIn(function(){
                        button.button( "option", "disabled", false);
                    });
                    
                    
                }

            });

            return false;
        });

        // Resets image
        jQuery('#neutron-image-btn-reset-' + options.id).click(function(){

            var button = jQuery(this);
            button.button( "option", "disabled", true );


            jQuery.post(options.reset_url, {
                name: jQuery('#' + options.name_id).val()
            }, function(response){
                if(response.success === false){
                    showError(response.err_msg);
                } else if(response.success === true) {
                    jQuery('#' + options.hash_id).val(response.hash);
                    jQuery('#neutron-image-' + options.id).fadeOut(function(){
                        jQuery(this).attr({
                            'src': options.base_url + options.dir + response.name +  
                            '?t=' + new Date().getTime(), 
                            width: options.minWidth, 
                            height: options.minHeight
                        });
                    }).fadeIn(function(){
                        button.button( "option", "disabled", false);
                    });
                }

            });

            return false;
        });

        
    });
    
    jQuery('.neutron-image-upload-main').fadeIn(1000);
});