MultiFileUpload
===============

MultiFileUpload is almost the same as [FileUpload](file_upload.md) the only difference is that you can upload and manage more then one file and items are sortable.

Before you start see [plupload doc](plupload.md)

### Usage :

In your form do the following:

``` php
<?php
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('files', 'neutron_multi_file_upload_collection', array(
		    'label' => 'form.multi_file',
		    'options' => array(
		        'data_class' => 'AppBundle\Entity\MultiFile'
		    ),
		    'configs' => array(
		        'extensions' => 'application/pdf',
		        'maxSize' => '3M',
		        'runtimes' => 'html5,flash'
		    )
		)) 
		// .....
    ;
}
```
### Doctrine ORM integration

Create *MultiFile* entity by extending  *AbstractMultiFile* class.

``` php
<?php
namespace AppBundle\Entity;

use Neutron\FormBundle\Entity\AbstractMultiFile;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MultiFile extends AbstractMultiFile
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
        return '/media/files/product';
    }
}
```

**Note:** Method *FileInterface::getUploadDir()* must return the path where file will be moved after entity is saved to DB (executed in postFlush event).

Create *Product* entity:

``` php
<?php
namespace AppBundle\Entity;

use Neutron\FormBundle\Model\MultiFileInterface;

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
     * @ORM\ManyToMany(targetEntity="MultiFile", cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\JoinTable(
     *   inverseJoinColumns={@ORM\JoinColumn(unique=true,  onDelete="CASCADE")}
     * )
     */
    protected $files;
    
    public function __construct()
    {
        $this->files = new ArrayCollection();
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
    
    public function addFile(MultiFileInterface $file)
    {
        if (!$this->files->contains($file)){
            $this->files->add($file);
        }
    }
    
    public function getFiles()
    {
        return $this->files;
    }
    
    public function removeFile(MultiFileInterface $file)
    {
        if ($this->files->contains($file)){
            $this->files->removeElement($file);
        }
    }    
}
```

**Note:** Update your database schema running the following command:

``` bash
$ php app/console doctrine:schema:update --force
```

As you see we have a product that has one-to-many unidirectional association with the files.
When you do flush all files will be moved to permenent directory.

The form is a standard symfony class.

**Note:** If you want to validate the files data use the standard symfony validators (File mimetype is validated on upload and it is done by the bundle).

That's it.

[back to index](index.md)
