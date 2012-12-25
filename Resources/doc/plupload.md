Plupload
========

*Plupload* is a jquery plugin [see demo](http://www.plupload.com/example_custom.php). It is used by four elements in *NeutronFormBundle*
- [neutron_image_upload](image_upload.md)
- [neutron_file_upload](file_upload.md)
- [neutron_multi_image_upload](multi_image_upload.md)
- [neutron_multi_file_upload](multi_file_upload.md)

**Theory of operation:** User uploads an image/file to the web directory of the server in the temp folder (you can change the temp folder in the configs). 
Image/FIle is validated and normalized (by default image could be maximum 1000px width or 1000px height. You can change it in the configs).
There user makes the manipulation and when ready submits the form. If form is valid and entity is flushed the image is moved from temporary to permenent folder.
It is handled in the backgroud by the bundle.
It always keeps original image and changes can be reverted at any time.
When user perform updates  the image is copied from permenent to temporary directory. All manipulations are done on the temporary image. 
If image hash is changed then on *postFlush*  overrides the image in the permenent directory. If user replaces or deletes the image then it is removed from the permenent directory.

**Note:** Plupload supports html5(recommended) and flash only.

### Instalation.

###### Step 1) Download jquery plugins:
- plupload (https://github.com/moxiecode/plupload)
- Jcrop (https://github.com/tapmodo/Jcrop)
- colorbox (https://github.com/jackmoore/colorbox)

Put the sources somewhere in the *web* folder. EX: *web/jquery/plugins/*

##### Step 2) Configuration.

``` yml
neutron_form:
	plupload: ~
```

If you want to use flash plugin as well then you have to set .swf path:

``` yml
neutron_form:
	plupload: 
		plupload_flash_path_swf: "/jquery/plugins/plupload/js/plupload.flash.swf" 
```

##### Step 3) in the twig template include the following assets.

``` jinja
	{% block stylesheets %}
                
		{% stylesheets
			'jquery/css/smoothness/jquery-ui.css' 
            'jquery/plugins/jcrop/css/jquery.Jcrop.css'
            'jquery/plugins/colorbox/example1/colorbox.css'
            'bundles/neutronform/css/form_widgets.css'
            filter='cssrewrite'
        %}
			<link rel="stylesheet" href="{{ asset_url }}" />
        {% endstylesheets %}

	{% endblock %}
    
{% block javascripts %}

	{% javascripts
		'jquery/js/jquery.js'
        'jquery/js/jquery-ui.js'
        'jquery/plugins/plupload/js/plupload.js'                    
        'jquery/plugins/plupload/js/plupload.html5.js'                    
        'jquery/plugins/plupload/js/plupload.flash.js'                    
        'jquery/plugins/jcrop/js/jquery.Jcrop.js'                    
        'jquery/plugins/colorbox/colorbox/jquery.colorbox.js'                                                                                                                                                          
        'bundles/neutronform/js/image-upload.js'                                                                                                                                  
        'bundles/neutronform/js/file-upload.js'                                                                                                                                  
        'bundles/neutronform/js/multi-image-upload.js'                                                                                                                                  
        'bundles/neutronform/js/multi-file-upload.js'                                                                                                                                  
	%}
		<script src="{{ asset_url }}"></script>
	{% endjavascripts %}
   
{% endblock %}

{% form_theme form with ['NeutronFormBundle:Form:fields.html.twig'] %}
           
<form action="" method="post" {{ form_enctype(form) }} novalidate="novalidate">
    {{ form_errors(form) }}
	{{ form_widget(form) }}

    <input type="submit" />
</form>
```
**Note:** Update your assets running the following command:

``` bash
$ php app/console assetic:dump
```

### Security - In *ImageController* and *FileController* images and files are validated by symfony image and file validators. Your job is to secure the urls.
There are five urls:
- image upload url (/_neutron_form/image-upload)
- file upload url (/_neutron_form/file-upload)
- crop url (/_neutron_form/image-crop)
- rotate url (/_neutron_form/image-rotate)
- reset url (/_neutron_form/image-reset)

[symfony security docs](http://symfony.com/doc/master/book/security.html)

### Doctrine ORM integration. The implementation is different for every type. 

##### Cleaning the temp directory.

On some circumstances temporary images and files are not deleted. You can delete them by running the following command:

``` bash
$ php app/console neutron:form:remove-unused-files 7200
```
**Note:** this command will delete all images and files older than 7200 seconds. You can set a crone job to do that.


### All available configs:

``` yml
#app/config.config.yml

neutron_form:
	plupload:
		runtimes: "html5,flash"
		plupload_flash_path_swf: path_to_file
		temporary_dir: temp
		max_upload_size: "4M"
		normalize_width: 1000
		normalize_height: 1000
		enable_version: false
		image_options:  # used by neutron_image_upload and neutron_multi_image_upload
			enabled_button: true
			view_button: true
			crop_button: true
			meta_button: true
			rotate_button: true
			reset_button: true
		file_options: # used by neutron_file_upload and neutron_multi_file_upload
			enabled_button: true
			meta_button: true

```

In the next major version a cloud support and mongodb/couchdb will be added.