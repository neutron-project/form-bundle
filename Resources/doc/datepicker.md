Datepicker
===========

This is a jQuery datepicker.

See [demo](http://jqueryui.com/datepicker/)

### Usage:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('name', 'neutron_datepicker', array(
            'label' => 'Datepicker',
            'input' => 'datetime', // [datetime, string, array, timestamp]
            'date_timezone' => null,
            'user_timezone' => null,
            'configs' => array(
                'maxDate' => '+10D'
            )
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
        'bundles/neutronform/js/datepicker.js'
   
    %}
        <script src="{{ asset_url }}"></script>
	{% endjavascripts %}

{% endblock %}

{% form_theme form with ['NeutronFormBundle:Form:fields.html.twig'] %}

``
Option *configs* is converted to json object and passed to jQuery widget options.

[jQuery API documentation](http://api.jqueryui.com/datepicker/)

**Note:** You must install jQueryUI.

**Note:** Datepicker is locale aware. 

**Limitations** The only available format is *Y-m-d*.

That's it.


