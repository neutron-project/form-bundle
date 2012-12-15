FileUpload
==========

FileUpload provides the following functionalities:

- upload a single file
- add file metadata (title, caption and description)
- enable/disable the file
- remove the file
- easy and transparent integration with doctrine orm

*Note:* All configurations are almost the same as [neutron_image_upload](image_upload.md). Follow the same steps as described there.

### Usage :

##### In your form do the following:

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

##### in the twig template include the following assets.

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
        'jquery/plugins/plupload/js/plupload.js'                    
        'jquery/plugins/plupload/js/plupload.html5.js'                    
        'jquery/plugins/plupload/js/plupload.flash.js'                                                                                                                                                                           
        'bundles/neutronform/js/file-upload.js'                                                                                                                                  
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

*Note:* If you want to validate the file data use the standard symfony validators (File mimetype is validated on upload and it is done by the bundle).

### Security - In *FileController* file is validated by symfony file validator. Your job is to secure the urls.
There is only one url:
- upload url (/_neutron_form/file-upload)

[symfony security docs](http://symfony.com/doc/master/book/security.html)

That's it.
