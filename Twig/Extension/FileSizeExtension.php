<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Zender <nikolay.georgiev@zend.bg>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\Bundle\FormBundle\Twig\Extension;

use Symfony\Component\Filesystem\Filesystem;

use Neutron\Bundle\FormBundle\Model\ImageInterface;

/**
 * Twig extension
 *
 * @author Nikolay Georgiev <nikolay.georgiev@zend.bg>
 * @since 1.0
 */
class FileSizeExtension extends \Twig_Extension
{


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
            'neutron_filesize' => new \Twig_Function_Method($this, 'fileSize')
        );
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'neutron_filesize';
    }
}
