FileUpload
==========

FileUpload provides the following functionalities:

- upload a single file
- add file metadata (title, caption and description)
- enable/disable the file
- remove the file
- easy and transparent integration with doctrine orm

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
        ->add('file', 'neutron_file_upload', array(
		    'label' => 'form.file',
		    'data_class' => 'AppBundle\Entity\File',
		    'configs' => array(
		        'extensions' => 'application/pdf',
		        'maxSize' => '4M',
		        'runtimes' => 'html5,flash'
		    ),
		)) 
		// .....
    ;
}
```

### Doctrine ORM integration

##### Create *File* entity by extending  *AbstractFile* class.

``` php
<?php
namespace AppBundle\Entity;

use Neutron\FormBundle\Entity\AbstractFile;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class File extends AbstractFile
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

*Note:* Method *FileInterface::getUploadDir()* must return the path where file will be moved after entity is saved to DB (executed in postFlush event).

##### Create *Product* entity:

``` php
<?php
namespace AppBundle\Entity;

use Neutron\FormBundle\Model\FileInterface;

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
     * @ORM\OneToOne(targetEntity="File", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $file;
    
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
    
    
    public function setFile(FileInterface $file = null)
    {
        $this->file = $file;
    }
    
    public function getFile()
    {
        return $this->file;
    }
}
```

**Note:** Update your database schema running the following command:

``` bash
$ php app/console doctrine:schema:update --force
```

As you see we have a product that has one-to-one unidirectional association with the file.
When you do flush the file will be moved to permenent directory.

The form is a standard symfony class.

**Note:** If you want to validate the file data use the standard symfony validators (File mimetype is validated on upload and it is done by the bundle).

##### File version uses optimistic lock  [more info](http://docs.doctrine-project.org/en/2.0.x/reference/transactions-and-concurrency.html#optimistic-locking):
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
}
```


That's it.

[back to index](index.md)
