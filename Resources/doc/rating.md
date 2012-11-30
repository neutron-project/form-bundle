Rating
======

This element is a jQuery raty widget.

See [demo](http://wbotelhos.com/raty)

**Important:** download source from [here](https://github.com/wbotelhos/raty)

### Usage:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('name', 'neutron_rating', array(
            'label' => 'Rating',
            'configs' => array(
                'path' => '/jquery/plugins/raty/img',
                'number' => 5,
            ),
        ))
		// .....
    ;
}
```

**Important:** Option *path* is required otherwise widget can not find its images!

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
        'jquery/plugins/raty/js/jquery.raty.js'
        'bundles/neutronform/js/rating.js'
    %}
        <script src="{{ asset_url }}"></script>
	{% endjavascripts %}

{% endblock %}

{% form_theme form with ['NeutronFormBundle:Form:fields.html.twig'] %}

``
Option *configs* is converted to json object and passed to jQuery widget options.

[API documentation](http://wbotelhos.com/raty)

**Note:** You must install jQueryUI.

That's it.


