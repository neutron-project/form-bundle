DateRangePicker
===============

This is a jQuery datepicker with two date range text fields.

See [demo](http://jqueryui.com/datepicker/#date-range)

### Usage with default options:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('name', 'neutron_daterangepicker', array(
            'label' => 'DateRangePicker',
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

**Note:** Each date field has the same options as datepicker. Use *options* to pass options to both fields or *first_option*, *second_option*.

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

```
Option *configs* is converted to json object and passed to jQuery widget options.

[jQuery API documentation](http://api.jqueryui.com/datepicker/)

**Note:** You must install jQueryUI.

**Note:** Datepicker is locale aware. 

**Limitations** The only available format is *Y-m-d*.

That's it.


