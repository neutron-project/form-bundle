MultiImageUpload
================

MultiImageUpload is almost the same as [ImageUpload](image_upload.md) the only difference is that you can upload and manage more then one image and items are sortable.

Before you start see [plupload doc](plupload.md)

### Usage :

##### In your form do the following:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('images', 'neutron_multi_image_upload_collection', array(
		    'options' => array(
		        'data_class' => 'AppBundle\Entity\MultiImage'
		    ),
		    'configs' => array(
		        'minWidth' => 300,
		        'minHeight' => 100,
		        'extensions' => 'jpeg,jpg',
		        'maxSize' => '2M',
		        'runtimes' => 'html5,flash'
		    )
		)) 
		// .....
    ;
}
```

### Doctrine ORM integration

Create *MultiImage* entity by extending  *AbstractMultiImage* class.

``` php
<?php
namespace AppBundle\Entity;

use Neutron\FormBundle\Entity\AbstractMultiImage;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MultiImage extends AbstractMultiImage
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
        return '/media/images/product';
    }
}
```

**Note:** Method *ImageInterface::getUploadDir()* must return the path where image will be moved after entity is saved to DB (executed in postFlush event).

##### Create *Product* entity:

``` php
<?php
namespace AppBundle\Entity;

use Neutron\FormBundle\Model\MultiImageInterface;

use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\ManyToMany(targetEntity="MultiImage", cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\JoinTable(
     *   inverseJoinColumns={@ORM\JoinColumn(unique=true,  onDelete="CASCADE")}
     * )
     */
    protected $images;

    
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }
    
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
    
    public function addImage(MultiImageInterface $image)
    {
        if (!$this->images->contains($image)){
            $this->images->add($image);
        }
    }
    
    public function getImages()
    {
        return $this->images;
    }
    
    public function removeImage(MultiImageInterface $image)
    {
        if ($this->images->contains($image)){
            $this->images->removeElement($image);
        }
    }
}
```

**Note:** Update your database schema running the following command:

``` bash
$ php app/console doctrine:schema:update --force
```

As you see we have a product that has one-to-many unidirectional association with images.
When you do flush all images will be moved to permenent directory.

The form is a standard symfony class.

**Note:** If you want to validate the images data use the standard symfony validators (Image mimetype is validated on upload and it is done by the bundle).

##### Rendering images. 
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
{% for image in entity.images %}
	{{ neutron_image(image, 'Image_thumb') }} 
{% endfor %}
```

That's it.

[back to index](index.md)
