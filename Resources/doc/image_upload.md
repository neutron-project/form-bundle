ImageUpload
===========

ImageUpload type is based on *plupload*, *Jrop* and *colorbox* jquery plugins. 
In the backend it uses [avalanche123/Imagine](https://github.com/avalanche123/Imagine) and [AvalancheImagineBundle](https://github.com/avalanche123/AvalancheImagineBundle) to manipulate and render the image.
It provides the following functionalities:
- upload a single image
- preview the image with colorbox plugin
- crop the image with Jcrop
- rotate the image
- reset the image to its original state
- add image metadata (title, caption and description)
- enable/disable the image
- remove the image
- easy and transparent integration with doctrine orm

You can enable and disable most of these functionalities from the configurations.

*Note:* ImageUpload supports html5(recommended) and flash only.

### Usage :

*Important:* You have to install *jquery* and *jqueryui*

###### Step 1) Download jquery plugins:
- plupload (https://github.com/moxiecode/plupload)
- Jcrop (https://github.com/tapmodo/Jcrop)
- colorbox (https://github.com/jackmoore/colorbox)

Put the sources somewhere in the *web* folder. EX: *web/jquery/plugins/*

##### Step 2) Configuration.

``` yml
neutron_form:
	plupload: ~
```

If you want to use flash plugin then you have to set .swf path:

``` yml
neutron_form:
	plupload: 
		plupload_flash_path_swf: "/jquery/plugins/plupload/js/plupload.flash.swf" 
```

##### Step 3) In your form do the following:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('image', 'neutron_image_upload', array(
            'data_class' => 'AppBundle\Entity\Image', // if you use doctrine
		    'configs' => array(
		        'minWidth' => 200,
		        'minHeight' => 100,
		        'extensions' => 'jpeg,jpg',
		        'maxSize' => '2M',
		    ),
		))
		// .....
    ;
}
```

*Important:* Configs *'minWidth', 'minHeight', 'maxSize', 'extensions'* are required.

##### Step 4) in the twig template include the following assets.

``` jinja
    {% block stylesheets %}
                
        {% stylesheets
           'jquery/css/smoothness/jquery-ui.css' 
           'jquery/plugins/jcrop/css/jquery.Jcrop.css'
           'jquery/plugins/colorbox/example1/colorbox.css'
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
        'jquery/plugins/plupload/js/plupload.js'                    
        'jquery/plugins/plupload/js/plupload.html5.js'                    
        'jquery/plugins/plupload/js/plupload.flash.js'                    
        'jquery/plugins/jcrop/js/jquery.Jcrop.js'                    
        'jquery/plugins/colorbox/colorbox/jquery.colorbox.js'                                                                                                                                                          
        'bundles/neutronform/js/image-upload.js'                                                                                                                                  
	%}
		<script src="{{ asset_url }}"></script>
	{% endjavascripts %}
   
{% endblock %}

{% form_theme form with ['NeutronFormBundle:Form:fields.html.twig'] %}
           
<form action="" method="post" {{ form_enctype(form) }} novalidate="novalidate">
    {{ form_errors(form) }}
	{{ form_widget(form) }}

    <input type="submit" />
</form>
```
**Note:** Update your assets running the following command:

``` bash
$ php app/console assetic:dump
```

If you can render the form  you will see the widget. By default all features are enable.

You can disable them:

``` yml
neutron_form:   
    plupload: 
        plupload_flash_path_swf: "/jquery/plugins/plupload/js/plupload.flash.swf" 
        image_options:
            enabled_button: false
            view_button: false
            crop_button: false
            meta_button: false
            rotate_button: false
            reset_button: false
```

##### Step 5) Doctrine integration.

First create *Image* entity by extending  *AbstractImage* class.

``` php
<?php
namespace AppBundle\Entity;

use Neutron\FormBundle\Entity\AbstractImage;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Image extends AbstractImage
{
    /**
     * @var integer 
     *
     * @ORM\Id @ORM\Column(name="id", type="integer")
     * 
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    public function getUploadDir()
    {
        return '/media/images/image';
    }
}
```

*Note:* Method *ImageInterface::getUploadDir()* must return the path where image will be moved after entity is saved to DB (executed in postFlush event).

Create *Product* entity:

``` php
<?php
namespace AppBundle\Entity;

use Neutron\FormBundle\Model\ImageInterface;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Product
{
    /**
     * @var integer 
     *
     * @ORM\Id @ORM\Column(name="id", type="integer")
     * 
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string 
     *
     * @ORM\Column(type="string", name="name", length=255, nullable=true, unique=false)
     */
    protected $name;
    
    /**
     * @var decimal
     *
     * @ORM\Column(type="decimal", name="price", scale=2, precision=10)
     */
    protected $price;
    
    /**
     * @ORM\OneToOne(targetEntity="Image", cascade={"all"}, orphanRemoval=true, fetch="EAGER")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $image;

    public function getId()
    {
        return $this->id;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setPrice($price)
    {
        $this->price = $price;
    }
    
    public function getPrice()
    {
        return $this->price;
    }
    
    public function setImage(ImageInterface $image = null)
    {
        $this->image = $image;
    }
    
    public function getImage()
    {
        return $this->image;
    }
}
```

**Note:** Update your database schema running the following command:

``` bash
$ php app/console doctrine:schema:update --force
```

As you see we have a product that has an one-to-one unidirectional association with an image and image could be optional.
When you do flush the image will be moved to permenent directory.

The form is a standard symfony class.

*Note:* If you want to validate the image data use the standard symfony validators (Image mimetype is validated on upload and it is done by the bundle).


*Theory of operation:* User uploads an image to the web directory of the server in the temp folder (you can change the temp folder in the configs). 
Image is validated and normalized (by default image could be maximum 1000px width or 1000px height. You can change it in the configs).
There user makes the manipulation and when ready submits the form. If form is valid and entity is flushed the image is moved from temporary folder to permenent folder.
It is all done in the backgroud by the bundle.
It always keeps original image and changes can be reverted at any time.
When user perform updates  the image is copied from permenent to temporary directory. All manipulations are done on the temporary image. 
If image hash is changed then on *postFlush*  overrides the image in the permenent directory. If user replaces or deletes the image then it is removed from the permenent directory.

### Image version uses optimistic lock  [more info](http://docs.doctrine-project.org/en/2.0.x/reference/transactions-and-concurrency.html#optimistic-locking):
By default versioning is disabled. You can enable it

``` yml
#app/config.config.yml

neutron_form:   
    plupload: 
		enable_version: true
```

When you flush the entity you have to use try/catch statement. EX:

``` php
try{
	$em->flush();
} catch(OptimisticLockException $e){
	return 'some message';
} catch (\Neutron\FormBundle\Exception\ImageHashException $e) {
	// this exception is thrown when temporary image hash is different than the image you try to save.
	// this can happen if someoneelse has changed the image in the temp directory.
	return 'some message';
}
```

##### Step 6) Rendering the image. 
To render the image we are going to use avalanche bundle.

``` yml
#app/config.config.yml

avalanche_imagine:
    driver: imagick 
    filters:
        Image_thumb:
            type:    thumbnail
            options: { size: [600, 200], mode: outbound, quality: 80 }
```

More information about [avalanche bundle](https://github.com/avalanche123/AvalancheImagineBundle)

In the twig template use the following twig function:

``` jinja
{{ neutron_image(entity.image, 'Image_thumb', {'alt': 'image thumb'}) }} 
```

### Cleaning the temp directory.

On some circumstances temporary images are not deleted. You can delete them by running the following command:

``` bash
$ php app/console neutron:form:remove-unused-images 7200
```
*Note:* this command will delete all images older than 7200 seconds. You can set a crone job to do that.


### Security - In *ImageController* image is validated by symfony image and file validators. Your job is to secure the urls.
There are four urls:
- upload url (/_neutron_form/image-upload)
- crop url (/_neutron_form/image-crop)
- rotate url (/_neutron_form/image-rotate)
- reset url (/_neutron_form/image-reset)

[symfony security docs](http://symfony.com/doc/master/book/security.html)

### All available configs:

``` yml
#app/config.config.yml

neutron_form:
	plupload:
		runtimes: "html5,flash"
		plupload_flash_path_swf: path_to_file
		temporary_dir: temp
		max_upload_size: "4M"
		normalize_width: 1000
		normalize_height: 1000
		enable_version: false
		image_options:
			enabled_button: true
			view_button: true
			crop_button: true
			meta_button: true
			rotate_button: true
			reset_button: true
		file_options: # used by neutron_fole_upload
			enabled_button: true
			meta_button: true

```

That's it.

In the next major version a cloud support and mongodb/couchdb will be added.
 
