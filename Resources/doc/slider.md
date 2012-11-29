Slider
======

This element is a jQuery slider.

See [demo](http://jqueryui.com/slider/)

### Usage:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('name', 'neutron_slider', array(
            'label' => 'Slider',
            'configs' => array(
            	'tpl' => 'Value: __value__',
                'step' => 5, 
                'min' => 35,
                'max' => 80,
            ),
        ))
		// .....
    ;
}
```

**Note:** *__value__* is replaced by the current value of the slider.

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
        'bundles/neutronform/js/slider.js'
   
    %}
        <script src="{{ asset_url }}"></script>
	{% endjavascripts %}

{% endblock %}

{% form_theme form with ['NeutronFormBundle:Form:fields.html.twig'] %}

``
Option *configs* is converted to json object and passed to jQuery widget options.

[jQuery API documentation](http://api.jqueryui.com/slider/)

**Note:** You must install jQueryUI.

That's it.


