Using NeutronFormBundle
===========================
<a name="top"></a>
NeutronFormBundle adds supports for building RIA form elements without writing a single line of javascript code!

<a name="installation"></a>

## Installation

### Step 1) Get the bundle

First, grab the  NeutronFormBundle using composer (symfony 2.1 pattern)

Add on composer.json (see http://getcomposer.org/)

    "require" :  {
        // ...
        "neutron/form-bundle":"dev-master",
    }

### Step 2) Register the bundle

To start using the bundle, register it in your Kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Neutron\FormBundle\NeutronFormBundle(),
        // if you use form types which require neutron/datagrid-bundle
        new Neutron\DataGridBundle\NeutronDataGridBundle(),
        // if you use plupload
        new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
    );
    // ...
}
```
### Step 3) Register the bundle routes

``` yaml
# app/config/routing.yaml
# needed for plupload
neutron_form:
    resource: "@NeutronFormBundle/Resources/config/routing.xml"
    prefix:   / 
    
_imagine:
    resource: .
    type:     imagine
```

### Step 4) Download jQuery, jQueryUI 

You need to download latest version of [jQuery](http://jquery.com/), [jqueryui](http://jqueryui.com/) 
then put the sources somewhere in the web folder. EX: *web/jquery*

**Note:** Each form element requires its own javascript library.

### Step 5) Securing Specific URL Patterns

NeutronFromBundle uses ajax/post requests to upload, crop, rotate and reset images and files.

- neutron_form_media_image_upload (used to upload image: */_neutron_form/image-upload*  
- neutron_form_media_image_crop (used to crop image): */_neutron_form/image-crop* 
- neutron_form_media_image_rotate (used to rotate image): */_neutron_form/image-rotate* 
- neutron_form_media_image_reset (used to reset image): */_neutron_form/image-reset* 
- neutron_form_media_file_upload (used to upload file): */_neutron_form/file_upload* 

``` yaml
# app/config/security.yml
security:
    # ...
    access_control:
        - { path: ^/_neutron_form/image-upload, roles: ROLE_ADMIN }
        - { path: ^/_neutron_form/image-crop, roles: ROLE_ADMIN }
        - { path: ^/_neutron_form/image-rotate, roles: ROLE_ADMIN }
        - { path: ^/_neutron_form/image-reset, roles: ROLE_ADMIN }
        - { path: ^/_neutron_form/file-upload, roles: ROLE_ADMIN }
```
Or you can secure *^/admin* section and then prefix the bundle routing.

``` yaml
# app/config/routing.yml

neutron_form:
    resource: "@NeutronFormBundle/Resources/config/routing.xml"
    prefix:   /admin

```
[For more information go to symfony documentation](http://symfony.com/doc/current/book/security.html#securing-specific-url-patterns)

<a name="list"></a>
**List of all form elements**

* [Autocomplete](autocomplete.md)
* [Buttonset](buttonset.md)
* [Toggle button](toggle_button.md)
* [Slider](slider.md)
* [Slider Range](slider_range.md)
* [Spinner](spinner.md)
* [Datepicker](datepicker.md)
* [DateRangePicker](daterangepicker.md)
* [DateTimePicker](datetimepicker.md)
* [DateTimeRangePicker](datetimerangepicker.md)
* [Timepicker](timepicker.md)
* [ColorPicker](colorpicker.md)
* [InputLimiter](inputlimiter.md)
* [Plain](plain.md)
* [Rating](rating.md)
* [Recaptcha](recaptcha.md)
* [Select2](select2.md)
* [Select2 dependent](select2_dependent.md)
* [Tinymce](tinymce.md)
* [MultiSelectSortable](multi_select_sortable.md)
* [MultiSelect](multi_select.md)
* [Plupload](plupload.md)

[back to top](#top)