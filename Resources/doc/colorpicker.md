ColorPicker
===========

This element is a jQuery colorpicker widget.

See [demo](http://www.eyecon.ro/colorpicker/#about)

**Important:** download source from [here](http://www.eyecon.ro/colorpicker/#download)

### Usage:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        >add('name', 'neutron_colorpicker', array(
            'label' => 'Colorpicker',
            'configs' => array(),
        ))
		// .....
    ;
}
```

in the twig template add following code:

``` jinja
{% block stylesheets %}
            
    {% stylesheets
       'jquery/css/smoothness/jquery-ui.css' 
       'bundles/neutronform/css/form_widgets.css'
       'jquery/plugins/colorpicker/css/colorpicker.css'
        filter='cssrewrite'
    %}
        <link rel="stylesheet" href="{{ asset_url }}" />
    {% endstylesheets %}

{% endblock %}

{% block javascripts %}

    {% javascripts
        'jquery/js/jquery.js'
        'jquery/js/jquery-ui.js'
        'jquery/i18n/jquery-ui-i18n.js'
        'jquery/plugins/colorpicker/js/colorpicker.js'
        'bundles/neutronform/js/colorpicker.js'
    %}
        <script src="{{ asset_url }}"></script>
	{% endjavascripts %}

{% endblock %}

{% form_theme form with ['NeutronFormBundle:Form:fields.html.twig'] %}

``
Option *configs* is converted to json object and passed to jQuery widget options.

[jQuery API documentation](http://www.eyecon.ro/colorpicker/#implement)

**Note:** You must install jQueryUI.

That's it.


