InputLimiter
============

This element is a jQuery inputlimiter widget.

See [demo](http://rustyjeans.com/jquery-inputlimiter/demo.htm)

**Important:** download source from [here](http://code.google.com/p/jquery-inputlimiter/downloads/list)

### Usage:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('name', 'neutron_input_limiter', array(
            'label' => 'Limiter',
            'configs' => array(
                'limit' => 255,
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
       'jquery/plugins/inputlimiter/jquery.inputlimiter.1.0.css'
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
        'jquery/plugins/inputlimiter/jquery.inputlimiter.1.3.1.js'
        'bundles/neutronform/js/input-limiter.js'
    %}
        <script src="{{ asset_url }}"></script>
	{% endjavascripts %}

{% endblock %}

{% form_theme form with ['NeutronFormBundle:Form:fields.html.twig'] %}

``
Option *configs* is converted to json object and passed to jQuery widget options.

[jQuery API documentation](http://rustyjeans.com/jquery-plugins/input-limiter#options)

**Note:** Widget translations are located in *Resources/translations/NeutronFormBundle.[lang].xlf*. 
Overwrite them by setting *translation_domain* form type option.

**Note:** You must install jQueryUI.

That's it.


