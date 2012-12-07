<?php
/*
 * This file is part of NeutronDataGridBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Neutron\FormBundle\DependencyInjection\Compiler\ImagineCompilerPass;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NeutronFormBundle extends Bundle
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::build()
     */
    public function build (ContainerBuilder $container)
    {
        parent::build($container);
    
        $container->addCompilerPass(new ImagineCompilerPass());
    }
}
