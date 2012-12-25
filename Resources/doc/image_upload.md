ImageUpload
===========

ImageUpload provides the following functionalities:
- upload a single image
- preview the image with colorbox plugin
- crop the image with Jcrop
- rotate the image
- reset the image to its original state
- add image metadata (title, caption and description)
- enable/disable the image
- remove the image
- easy and transparent integration with doctrine orm

### Usage:

Before you start see [plupload doc](plupload.md)

##### In your form do the following:

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

**Important:** Configs *'minWidth', 'minHeight', 'maxSize', 'extensions'* are required.


### Doctrine integration.

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

**Note:** Method *ImageInterface::getUploadDir()* must return the path where image will be moved after entity is saved to DB (executed in postFlush event).

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

**Note:** If you want to validate the image data use the standard symfony validators (Image mimetype is validated on upload and it is done by the bundle).

##### Image version uses optimistic lock  [more info](http://docs.doctrine-project.org/en/2.0.x/reference/transactions-and-concurrency.html#optimistic-locking):
By default versioning is disabled. You can enable it

``` yml
#app/config.config.yml

neutron_form:   
    plupload: 
		enable_version: true
```

When you flush the entity you have to use try/catch statement.

``` php
try{
	$em->flush();
} catch(OptimisticLockException $e){
	return 'some message';
} catch (\Neutron\FormBundle\Exception\ImageHashException $e) {
	// this exception is thrown when temporary image hash is different than the image you try to save.
	// this can happen if someone else has changed the image in the temp directory.
	return 'some message';
}
```

##### Rendering the image. 
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

That's it.
 
[back to index](index.md)