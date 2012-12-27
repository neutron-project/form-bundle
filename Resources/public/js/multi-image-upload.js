/**
 * Initialization of multi image upload widget
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){
    
    // Creating buttons
    jQuery('.neutron-image-upload-button').button();

    // Searching for multi image upload elements
    jQuery('.neutron-multi-image-upload').each(function(key, value){
        
        var options = jQuery(this).data('options');  
        var prototype = jQuery(this).attr('data-prototype');
        
        jQuery('#neutron-image-btn-upload-' + options.id).click(function(){
            return false;
        });
        
        // Disables buttons
        var disableButtons = function(){    
            jQuery('#neutron-image-btn-enabled-' + options.id).button( "option", "disabled", true ).data(null);
            jQuery('#neutron-image-btn-view-' + options.id).button( "option", "disabled", true ).data(null);
            jQuery('#neutron-image-btn-crop-' + options.id).button( "option", "disabled", true ).data(null);
            jQuery('#neutron-image-btn-remove-' + options.id).button( "option", "disabled", true ).data(null);
            jQuery('#neutron-image-btn-reset-' + options.id).button( "option", "disabled", true ).data(null);
            jQuery('#neutron-meta-btn-edit-' + options.id).button( "option", "disabled", true ).data(null);
            jQuery('#neutron-image-btn-rotate-' + options.id).button( "option", "disabled", true ).data(null);
            jQuery('#neutron-dlg-meta-edit-' + options.id).data(null);
            jQuery('#neutron-dlg-image-crop-' + options.id).data(null);
        };

        //  Enables buttons
        var enableButtons = function(data){ 
            jQuery('#neutron-image-btn-enabled-' + options.id).button( "option", "disabled", false ).data(data);
            jQuery('#neutron-image-btn-view-' + options.id).button( "option", "disabled", false ).data(data);
            jQuery('#neutron-image-btn-crop-' + options.id).button( "option", "disabled", false ).data(data);
            jQuery('#neutron-image-btn-remove-' + options.id).button( "option", "disabled", false ).data(data);
            jQuery('#neutron-image-btn-reset-' + options.id).button( "option", "disabled", false ).data(data);
            jQuery('#neutron-meta-btn-edit-' + options.id).button( "option", "disabled", false ).data(data);
            jQuery('#neutron-image-btn-rotate-' + options.id).button( "option", "disabled", false ).data(data);

            jQuery('#neutron-dlg-meta-edit-' + options.id).data(data);
            jQuery('#neutron-dlg-image-crop-' + options.id).data(data);
        };

        var toggleContainer = function(){
            var elmCount = jQuery('#neutron-multi-image-upload-container-' + options.id).children().length;
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
            jQuery('#neutron-multi-image-upload-error-' + options.id)
                .fadeIn(function(){
                    jQuery('body').trigger('refreshPlUpload');
                })
                .find('.neutron-imageupload-error')
                .html(errorMsg);

            jQuery('#neutron-image-btn-upload-' + options.id).button( "option", "disabled", true );
            disableButtons();
        };
        
        // Check if any errors displayed
        var hasError = function(){
            return jQuery('#neutron-multi-image-upload-error-' + options.id).is(':visible');
        };

        disableButtons();

        // Initializing sortable
        jQuery( "#neutron-multi-image-upload-container-" + options.id ).sortable({
            placeholder: "ui-state-highlight",
            forcePlaceholderSize:true,
            update: function(event, ui){
                var elms = jQuery('#neutron-multi-image-upload-container-' + options.id + ' li');
                console.log(elms);
                elms.each(function(key, value){
                    jQuery(this).find(':hidden').eq(5).val(key);
                });
            }
        });
        
        jQuery( "neutron-multi-image-upload-container-" + options.id ).disableSelection();

        // Toggle enabled button
        var toggleActive = function(elm){
            var button = jQuery('#neutron-image-btn-enabled-' + options.id);
            var elm = elm.find(':hidden').eq(6); 
            
            if(elm.val() == 0){
                button.removeClass('ui-icon-bullet').addClass('ui-icon-radio-on');
            } else {
                button.removeClass('ui-icon-radio-on').addClass('ui-icon-bullet');
            }
        };

        // Registering double click event on all existing images
        jQuery("#neutron-multi-image-upload-container-"+ options.id +" li").live('dblclick', function(event) {
            jQuery(this).addClass("selected").siblings().removeClass("selected");
            toggleActive(jQuery(this));
            enableButtons({
                elm: jQuery(this)
            });
        });


        // Configuring uploader
        var uploader = new plupload.Uploader({
            runtimes : options.runtimes,
            multi_selection:true,
            multiple_queues : true,
            dragdrop : true,
            drop_element: 'drag-drop-area-' + options.id,
            browse_button : 'neutron-image-btn-upload-' + options.id,
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

        // Uploader Event: Init
        uploader.bind('Init', function(up, params) {
            var availableDragAndDrop = ['gears', 'html5'];
            if(jQuery.inArray(params.runtime, availableDragAndDrop) == -1){
                jQuery('#neutron-drag-drop-info-' + options.id)
                    .text(jQuery('#neutron-drag-drop-info-' + options.id).attr('trans-no-images'));
            } else {
                jQuery('#neutron-drag-drop-info-' + options.id)
                    .text(jQuery('#neutron-drag-drop-info-' + options.id).attr('trans-drag'));
            }
        });


        // Uploader Event: FilesAdded
        uploader.bind('FilesAdded', function(up, files) {

            jQuery('#drag-drop-area-' + options.id).fadeOut();

            jQuery.each(files, function(i, file) {
                var html = jQuery('#neutron-progressbar-prototype-' + options.id).html()
                    .replace('__image_name__', file.name)
                    .replace('__image_size__', plupload.formatSize(file.size))
                    .replace(/__id__/g, file.id);

                jQuery('#multi-image-progress-' + options.id).append(html).queue(function(){
                    jQuery('#' + file.id).progressbar();
                    jQuery('#neutron-multi-upload-remove-image-' + file.id).live('click', function(){
                        uploader.removeFile(file);
                        jQuery('#' + file.id).fadeOut().next().fadeOut(function(){
                            jQuery('#' + file.id).next().remove().end().remove();
                        });
                     
                        return false;
                    });
                    
                    jQuery(this).dequeue();
                });
            });

            jQuery('#neutron-image-btn-upload-' + options.id).button( "option", "disabled", true );
            disableButtons();
            
            setTimeout(function () {
                up.start();
            }, 100);
            
            jQuery('body').trigger('refreshPlUpload');

        });

        // Uploader Event: UploadProgress
        uploader.bind('UploadProgress', function(up, file) {

            var percent = file.percent;

            jQuery('#' + file.id).progressbar("option", "value", percent);

            jQuery('#' + file.id).next().find('strong').html(percent + '%');
            
        });

        // Uploader Event: FileUploaded
        uploader.bind("FileUploaded", function(up, file, response) {
            /**
             * response from server
	     */
            var data = jQuery.parseJSON(response.response); 

            if(data.success === false){ 
                uploader.stop();
                showError(file.name+ ': ' + data.err_msg);

            } else if(data.success == true){
                var collectionHolder = jQuery('#neutron-multi-image-upload-container-' + options.id);
                
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

                collectionHolder.append(jQuery('<li data-index="'+ elementIdx +'"><img src="'+ 
                    options.base_url + options.dir + data.name +'" width="'+ options.minWidth +'"  height="'
                    + options.minHeight +'" /></li>').append(elm));

                var formElm = collectionHolder.find('[data-index="'+ elementIdx +'"]').find(':hidden');
 
                formElm.eq(0).val(data.name);			
                formElm.eq(4).val(data.hash);
                formElm.eq(5).val(parseInt(collectionHolder.children().length) - 1);
                formElm.eq(6).val(0);


            }

            jQuery('#' + file.id).fadeOut().next().fadeOut(function(){
                jQuery('#' + file.id).next().remove().end().remove();
            });

        });

        // PlUpload Event: UploadComplete
        uploader.bind("UploadComplete", function(up, files){

            jQuery('#multi-image-progress-' + options.id).children().fadeOut().end().html('');
            jQuery('#neutron-image-btn-upload-' + options.id).button( "option", "disabled", false);
            if(jQuery("#neutron-multi-image-upload-container-"+ options.id).find('.selected').length == 1){
                enableButtons();
            } 
            toggleContainer();
  
        });

        // Initializing uploader
        uploader.init();
        
        jQuery('body').bind('refreshPlUpload', function(){
            uploader.refresh();
        });

        // Closes the error message and starts the upload of remaining items.
        jQuery('#neutron-multi-upload-error-cancel-' + options.id).click(function(){
            jQuery('#neutron-multi-image-upload-error-' + options.id)
            .fadeOut(function(){
                jQuery('body').trigger('refreshPlUpload');
                if(uploader.files.length > 0){
                    uploader.start();
                } else {
                    toggleContainer();
                    jQuery('#neutron-image-btn-upload-' + options.id).button('option', 'disabled', false);
                }
                
            });

            return false;
        });

        // Active image handler
        jQuery('#neutron-image-btn-enabled-' + options.id).click(function(){
            var elm = jQuery(this).data().elm;
            var activeElm = elm.find(':hidden').eq(6);
            
            
            if(jQuery(this).hasClass('ui-icon-bullet')){
                jQuery(this).removeClass('ui-icon-bullet').addClass('ui-icon-radio-on');
                activeElm.val(0);
            } else {
                jQuery(this).removeClass('ui-icon-radio-on').addClass('ui-icon-bullet');
                activeElm.val(1);
            }
            
            return false;
        });

        // Colorbox handler
        jQuery('#neutron-image-btn-view-' + options.id).click(function(){
            var elm = jQuery(this).data().elm;
            var name = elm.find(':hidden').eq(0).val();
            var title = elm.find(':hidden').eq(1).val();
            jQuery.colorbox({
                href: options.dir + name + '?t=' + new Date().getTime(), 
                title: title
            });
            
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

        // Reset coordinates of cropper to default ones
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
            var elm = jQuery('#neutron-dlg-image-crop-' + options.id).data().elm;
            var name = elm.find(':hidden').eq(0).val();

            jQuery(this).button({
                disabled: true
            });
			

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
            }).attr({src : options.base_url + options.dir + name +  '?t=' + new Date().getTime()});
            
            return false;

        });

        // Triggers actual cropping.
        jQuery('#neutron-crop-dlg-save-btn-' + options.id).click(function(){
            jQuery('#neutron-crop-dlg-save-btn-' + options.id).button( "option", "disabled", true);
            var elm = jQuery('#neutron-dlg-image-crop-' + options.id).data().elm;
            var image = elm.find('img');
            var name = elm.find(':hidden').eq(0).val();
            var hash = elm.find(':hidden').eq(4);

            jQuery.post(options.crop_url, {
                name: name,
                x: jQuery('#x-' + options.id).val(),
                y: jQuery('#y-' + options.id).val(),
                w: jQuery('#w-' + options.id).val(),
                h: jQuery('#h-' + options.id).val()
            }, function(response){
                if(response.success === false){
                    showError(response.err_msg);
                } else if(response.success === true) {
                    image.fadeOut(function(){
                        image.attr({
                            'src': options.base_url + options.dir + response.name +  
                            '?t=' + new Date().getTime(), 
                            width: options.minWidth, 
                            height: options.minHeight
                        });
                        
                        hash.val(response.hash);
                        image.fadeIn();
                    });

                }

                jQuery('#neutron-crop-dlg-save-btn-' + options.id).button( "option", "disabled", false);
                jQuery("#neutron-dlg-image-crop-" + options.id).dialog('close');

            });
        });

        // Closes crop dialog
        jQuery('#neutron-crop-dlg-cancel-btn-' + options.id).click(function(){
            jQuery("#neutron-dlg-image-crop-" + options.id).dialog('close');
        });


        // Configuring dialog multi image meta information
        jQuery("#neutron-dlg-meta-edit-" + options.id).dialog({
            'autoOpen' : false,
            'modal' : true,
            'width' : 'auto',
            close: function(event, ui) { 
                var elm = jQuery(this).data().elm.find(':hidden');
                elm.eq(1).val(jQuery('#neutron-meta-title-' + options.id).val());
                elm.eq(2).val(jQuery('#neutron-meta-caption-' + options.id).val());
                elm.eq(3).val(jQuery('#neutron-meta-description-' + options.id).val());
            }
        });

        // Opens dialog image edit meta
        jQuery('#neutron-meta-btn-edit-' + options.id).click(function(){

            var elm = jQuery(this).data().elm.find(':hidden');

            jQuery('#neutron-meta-title-' + options.id).val(elm.eq(1).val());
            jQuery('#neutron-meta-caption-' + options.id).val(elm.eq(2).val());
            jQuery('#neutron-meta-description-' + options.id).val(elm.eq(3).val());
            jQuery('#neutron-dlg-meta-edit-' + options.id).dialog('open');

        });

        jQuery('#neutron-edit-dlg-done-btn-' + options.id).click(function(){
            jQuery('#neutron-dlg-meta-edit-' + options.id).dialog('close');
        });


        // Rotates image
        jQuery('#neutron-image-btn-rotate-' + options.id).click(function(){
            var button = jQuery(this);
            button.button( "option", "disabled", true );

            var elm = jQuery(this).data().elm;
            var image = elm.find('img');
            var hash = elm.find(':hidden').eq(4);
            var name = elm.find(':hidden').eq(0).val();

            jQuery.post(options.rotate_url, {
                name: name
            }, function(response){
                if(response.success === false){
                    showError(response.err_msg);
                } else if(response.success === true) {
                    hash.val(response.hash);
                    image.fadeOut(function(){
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
        });

        // Resets image
        jQuery('#neutron-image-btn-reset-' + options.id).click(function(){
            var button = jQuery(this);
            button.button( "option", "disabled", true );

            var elm = jQuery(this).data().elm;
            var image = elm.find('img');
            var hash = elm.find(':hidden').eq(4);
            var name = elm.find(':hidden').eq(0).val();

            jQuery.post(options.reset_url, {
                name: name
            }, function(response){
                if(response.success === false){
                    showError(response.err_msg);
                } else if(response.success === true) {
                    hash.val(response.hash);
                    image.fadeOut(function(){
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
        });

        // Remove button handler
        jQuery('#neutron-image-btn-remove-' + options.id).click(function(event){

            jQuery(this).data().elm.fadeOut(function(){
                jQuery(this).remove();
                toggleContainer();
                var elms = jQuery('#neutron-multi-image-upload-container-' + options.id).children();
                elms.each(function(key, value){
                    jQuery(this).find(':hidden').eq(5).val(key);
                });
            });

            disableButtons();

        });

    });
    
    jQuery('.neutron-multi-image-upload-main').fadeIn(1000);
});