Datetimepicker
==============

This is an add-on to jQuery datepicker which adds a timepicker. 

See [demo](http://trentrichardson.com/examples/timepicker/)

### Usage:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('name', 'neutron_datetimepicker', array(
            'label' => 'DateTimePicker',
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
        'bundles/neutronform/js/datepicker.js'
   
    %}
        <script src="{{ asset_url }}"></script>
	{% endjavascripts %}

{% endblock %}

{% form_theme form with ['NeutronFormBundle:Form:fields.html.twig'] %}

```
Option *configs* is converted to json object and passed to jQuery widget options.

[jQuery API documentation](http://api.jqueryui.com/datepicker/)

[Add-on API documentation](http://trentrichardson.com/examples/timepicker/)

**Note:** You must install jQueryUI and [timepicker add-on](https://github.com/trentrichardson/jQuery-Timepicker-Addon).

**Note:** Datetimepicker is locale aware. 

**Limitations** The only available format is *Y-m-d H:i:s*.

That's it.


