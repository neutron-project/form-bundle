Timepicker
==========

This is a jQuery timepicker add-on.

See [demo](http://trentrichardson.com/examples/timepicker/)

### Usage:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('name', 'neutron_timepicker', array(
            'label' => 'Timepicker',
            'with_seconds' => false,
            'use_meridiem' => false,
            'input' => 'datetime',
            'date_timezone' => null,
            'user_timezone' => null,
            'configs' => array()
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
        'jquery/plugins/timepicker/jquery-ui-timepicker-addon.css'
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
		'jquery/plugins/timepicker/jquery-ui-timepicker-addon.js'
		'jquery/plugins/timepicker/jquery-ui-sliderAccess.js'
		'jquery/plugins/timepicker/localization/*' 
		'bundles/neutronform/js/timepicker.js'           
	%}
        <script src="{{ asset_url }}"></script>
    {% endjavascripts %}

{% endblock %}

{% form_theme form with ['NeutronFormBundle:Form:fields.html.twig'] %}

``
Option *configs* is converted to json object and passed to jQuery widget options.

[jQuery API documentation](http://trentrichardson.com/examples/timepicker/)

**Note:** You must install jQueryUI.

**Note:** Timepicker is locale aware. 

**Note:** If you do not want to use localization just remove *'jquery/plugins/timepicker/localization/*'*. 

**Limitations:** The only available format is *H:i:s*.

That's it.


