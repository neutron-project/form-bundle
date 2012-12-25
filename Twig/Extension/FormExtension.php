<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Twig\Extension;

use Neutron\FormBundle\Model\ImageInterface;

use Symfony\Component\DependencyInjection\Container;

/**
 * Twig extension
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class FormExtension extends \Twig_Extension
{

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * Construct
     * 
     * @param Container $container
     */
    public function __construct(Container $container)
    {
       $this->container = $container;
    }

    /**
     * @param ImageInterface $entity
     */
    public function outputImage(ImageInterface $image, $filter, array $options = array())
    {
        return
           $this->container->get('templating')
                ->render('NeutronFormBundle:Form:image.html.twig',
                   array('image' => $image, 'filter' => $filter, 'options' => $options)
                );
    }
    
    /**
     * Converts bytes
     *
     * @param integer $bytes
     * @return string
     */
    public function fileSize($bytes)
    {
        $bytes = (int) $bytes;
        $suffix = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        return $bytes ? round($bytes/pow(1024, ($i = floor(log($bytes, 1024)))), 2) . $suffix[$i] : '0 Bytes';
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            'neutron_image' => new \Twig_Function_Method($this, 'outputImage', array('is_safe' => array('html'))),
            'neutron_filesize' => new \Twig_Function_Method($this, 'fileSize')
        );
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'neutron_form';
    }

}
