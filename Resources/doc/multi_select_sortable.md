MultiSelectSortable
===================

MultiSelectSortable is based on jQueryUI and [jqgrid](http://trirand.com/blog/jqgrid/jqgrid.html). 
It provides the following functionalities:

- search in big sets
- drag and drop rows in sortable container
- sort rows
- remove rows
- easy and transparent integration with doctrine orm

Imagine that you have a showcase page in your website and you want to add some projects to it.
But you have to be able not only to add projects but sort them. The solution is *MultiSelectSortable*.

### Usage:

Let's create the model. You will need three entities *ShowCase*, *ProjectReference* and *Project*.

##### Let's create *ShowCase* entity

``` php
<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ShowCase 
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
     * @ORM\Column(type="string", name="title", length=255, nullable=true, unique=false)
     */
    protected $title;
    
    /**
     * @ORM\OneToMany(targetEntity="ProjectReference", mappedBy="showCase", cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $projectReferences;
        
    public function __construct()
    {
        $this->projectReferences = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getProjectReferences()
    {
        return $this->projectReferences;
    }
    
    public function addProjectReference(ProjectReference $projectReference)
    { 
        if (!$this->projectReferences->contains($projectReference)){ 
            $this->projectReferences->add($projectReference);
            $projectReference->setShowCase($this);
        }
    }
    
    
    public function removeProjectReference(ProjectReference $projectReference)
    {
        if ($this->projectReferences->contains($projectReference)){
            $this->projectReferences->removeElement($projectReference);
        }
    }
}
```

As you see we have one-to-many bi-directional association with *ProjectReference* entity.

##### Let's create *ProjectReference* entity

``` php
<?php
namespace AppBundle\Entity;

use Neutron\FormBundle\Model\MultiSelectSortableReferenceInterface;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ProjectReference implements MultiSelectSortableReferenceInterface
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
     * @var integer
     *
     * @ORM\Column(type="integer", name="position", length=10, nullable=false, unique=false)
     */
    protected $position = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="ShowCase", inversedBy="projectReferences")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $showCase;
    
    /**
     * @ORM\ManyToOne(targetEntity="Project", fetch="EAGER")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $project;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getLabel()
    {
        return $this->project->getTitle();
    }
    
    public function setPosition($position)
    {   
        $this->position = (int) $position;
    }
    
    public function getPosition()
    {
        return $this->position;
    }
    
    public function getShowCase ()
    {
        return $this->showCase;
    }
    
    public function setShowCase (ShowCase $showCase)
    {
        $this->showCase = $showCase;
    }
    
    public function getProject ()
    {
        return $this->project;
    }
    
    public function setProject (Project $project)
    {
        $this->project = $project;
    }
}
```

In *ProjectReference* we have many-to-one bi-directional association with *ShowCase* and many-to-one unidirectional association with *Project*.
You should implement *MultiSelectSortableReferenceInterface*. 
*MultiSelectSortableReferenceInterface::getLabel* is a proxy method and must return the label that will be shown on sortable row.

##### Let's create *Project* entity

``` php
<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 */
class Project 
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
     * @ORM\Column(type="string", name="title", length=255, nullable=true, unique=false)
     */
    protected $title;
    
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
}
```
**Note:** Update your database running the following command:

``` bash
$ php app/console doctrine:schema:update --force
```

Model is ready. Let's create the datagrid. Make sure you have installed [NeutronDataGridBundle](https://github.com/neutron-project/datagrid-bundle)

``` php
<?php
namespace AppBundle\DataGrid;

use Doctrine\Common\Persistence\ObjectManager;

use Neutron\DataGridBundle\DataGrid\DataGridFactoryInterface;

class ProjectMultiSelectSortableBuilder
{

    const IDENTIFIER = 'project_multi_select_sortable';
    
    protected $factory;
    
    protected $om;

    public function __construct (DataGridFactoryInterface $factory, ObjectManager $om)
    {
        $this->factory = $factory;
        $this->om = $om;
    }

    public function build ()
    {
        
        $dataGrid = $this->factory->createDataGrid(self::IDENTIFIER);
        $dataGrid
            ->setCaption('grid.project_management.title')
            ->setHideGrid(true)
            ->setHiddenGrid(true)
            ->setColNames(array(
                'grid.project_management.title',
            ))
            ->setColModel(array(
                array(
                    'name' => 'title', 'index' => 'p.title', 'width' => 200, 
                    'align' => 'left', 'sortable' => true, 'search' => true
              ), 
                    
            ))
            ->setQueryBuilder($this->getQueryBuilder())
            ->setSortName('p.title')
            ->setSortOrder('asc')
            ->enablePager(true)
            ->enableViewRecords(true)
            ->enableSearchButton(true)
            ->enableMultiSelectSortable(true)
            ->setMultiSelectSortableColumn('title')
       ;

        return $dataGrid;
    }

    protected function getQueryBuilder()
    {
        $qb = $this->om->getRepository('AppBundle:Project')->createQueryBuilder('p');
        $qb
            ->select('p.id, p.title, p')
        ;
    
        return $qb;
    }

}
```

You have to register the grid in service container [more info](https://github.com/neutron-project/datagrid-bundle/blob/master/Resources/doc/index.md#step-3-register-datagrid-in-the-service-container)

##### Let's build the form.

``` php
<?php
// ...
	protected $grid;
    
    public function setGrid(DataGridInterface $grid)
    {
        $this->grid = $grid;
    }
    
// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        // .....
        ->add('projectReferences', 'neutron_multi_select_sortable_collection', array(
            'label' => 'form.multi_select_sortable',
            'grid' => $this->grid, 
            'options' => array(
                'data_class' => 'AppBundle\Entity\ProjectReference',
                'inversed_class' => 'AppBundle\Entity\Project',
                'inversed_property' => 'project',
            )
        )) 
		// .....
    ;
}
```
It's a standard symfony form.

##### Let's create the view.

``` jinja
	{% block stylesheets %}
                
		{% stylesheets
			'jquery/css/smoothness/jquery-ui.css' 
            'jquery/plugins/jqgrid/css/ui.jqgrid.css' 
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
		'jquery/plugins/jqgrid/js/i18n/grid.locale-en.js'
        'jquery/plugins/jqgrid/js/jquery.jqGrid.src.js'
        'bundles/neutrondatagrid/js/init-datagrid.js'                                                                                                                                                                         
        'bundles/neutronform/js/multi-select-sortable.js'                                                                                                                                  
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

Use standard symfony validators to validate the form.

We're done. All you need is to drag and drop rows inside the sortable container. The rest is done by the bundle.

[back to index](index.md)