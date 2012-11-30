DateTimeRangePicker
===================

This is a jQuery datetimepicker with two datetime range text fields.

See [demo](http://trentrichardson.com/examples/timepicker/)

### Usage with default options:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('name', 'neutron_datetimerangepicker', array(
            'label' => 'DateTimeRangePicker',
            'options' => array(),
            'first_options'  => array(),
            'second_options' => array(),
            'first_name'     => 'first_date',
            'second_name'    => 'second_date',
        ))
		// .....
    ;
}
```

**Note:** Each datetime field has the same options as datetimepicker. Use *options* to pass options to both fields or *first_option*, *second_option*.

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
        'bundles/neutronform/js/datetimepicker.js'
   
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

**Note:** Datetimerangepicker is locale aware. 

**Note:** If you do not want to use localization just remove *'jquery/plugins/timepicker/localization/*'*. 

**Limitations** The only available format is *Y-m-d H:i:s*.

That's it.


