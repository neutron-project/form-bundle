Tinymce
=======

TinyMCE has the ability to convert HTML TEXTAREA fields or other HTML elements to editor instances. 

See [demo](http://www.tinymce.com/tryit/full.php)

### Let's grab the source

[download from here](http://www.tinymce.com/download/download.php)

**Important:** Grab jQuery package.

Put the download folder *tinymce* in web root under */jquery/plugins/*.

If you need more languages you can download them from [here](http://www.tinymce.com/i18n/index.php?ctrl=lang&act=download)

In you config.yml you need to put the following:

``` yaml
# app/config/config.yml

neutron_form:   
    tinymce:
        tiny_mce_path_js: "/jquery/plugins/tinymce/jscripts/tiny_mce/tiny_mce.js" # check your path!!!

```

Let's create our first tinymce editor.

### Usage:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('name', 'neutron_tinymce', array(
            'label' => 'Content',
            'configs' => array(
                'theme' => 'advanced', //simple
                'skin'  => 'o2k7',
                'skin_variant' => 'black',
                'width' => '60%',
                'height' => 300,
                'dialog_type' => 'modal',
                'readOnly' => false,
            ),
        ))
		// .....
    ;
}
```

in the twig template add following code:

``` jinja

{% block javascripts %}

    {% javascripts
        'jquery/js/jquery.js'
        'jquery/plugins/tinymce/jscripts/tiny_mce/jquery.tinymce.js'                    
        'bundles/neutronform/js/tinymce.js' 
    %}
        <script src="{{ asset_url }}"></script>
	{% endjavascripts %}

{% endblock %}

{% form_theme form with ['NeutronFormBundle:Form:fields.html.twig'] %}

```


Run the following command:

``` bash
$ php app/console assetic:dump
```

[API documentation](http://www.tinymce.com/wiki.php)

Default configuration:

``` yaml
# app/config/config.yml

neutron_form:   
    tinymce:
    	filemanager: false
        tiny_mce_path_js: path_to_tiny_mce_js (required)
        ajaxfilemanager_path_php: null
        security: ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN']
        theme: advanced
        skin: default
        skin_variant: silver
        width: "70%"
        height: 300
        dialog_type: window
        content_css: null
```


### Filemanager

I could not find any open source filemanager which can be easily integrated into Symfony 2 application.
The one I found is not secure but do the job. You can test it [here](http://www.phpletter.com/Demo/Tinymce-Ajax-File-Manager/)
I modified it a bit in order to work with *Symfony 2*.

**Important:** I highly recommend not to use it in production!


#####Instalation:

Step 1) Download the file manager from [here](https://github.com/neutron-project/ajax-file-manager). 
Put the source in *plugin folder* of tinymce editor.

Step 2) Create folder */media/filemanager* in the web root (Probably you will need to set proper permissions).

Step 3) Configure the manager:

``` yaml
# app/config/config.yml
neutron_form:   
    tinymce:
        filemanager: true
        tiny_mce_path_js: "/jquery/plugins/tinymce/jscripts/tiny_mce/tiny_mce.js"
        ajaxfilemanager_path_php: "/jquery/plugins/tinymce/jscripts/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php"
    	security: ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN'] # Roles allowed to use the filemanager.
```

Check the paths!

**Note:** If you want to configure *ajaxfilemanager* find */inc/config.base.php*.

That's it.

[back to index](index.md#list)