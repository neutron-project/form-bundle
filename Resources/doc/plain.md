Plain
=====

Converts value to string

### Usage:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('name', 'neutron_plain', array(
            'label' => 'Plain',
            'data' => new \DateTime(),
        ))
		// .....
    ;
}
```
You have the following options if *DateTime* instance. These are the default options:

``` php
	array(
        'date_format' => \IntlDateFormatter::LONG,
        'date_pattern' => null,
        'time_format' => \IntlDateFormatter::MEDIUM,
    ))
```

[IntlDateFormatter](http://php.net/manual/en/class.intldateformatter.php)

In the twig template add following code:

``` jinja
{% form_theme form with ['NeutronFormBundle:Form:fields.html.twig'] %}

```

That's it.

[back to index](index.md#list)
