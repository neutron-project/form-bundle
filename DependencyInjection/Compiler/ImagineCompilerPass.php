<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;


/**
 * Default implementation of CompilerPassInterface
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class ImagineCompilerPass implements CompilerPassInterface
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface::process()
     */
    public function process (ContainerBuilder $container)
    {
        if(!$container->hasAlias('imagine') && !$container->hasDefinition('imagine')) {
            if($container->hasDefinition('liip_imagine.gd')) {
                $container->setAlias('imagine', 'liip_imagine.gd');
            } else {
                $definition = new Definition("Imagine\\Gd\\Imagine");
                $container->setDefinition('imagine', $defination);
            }
        }
    }
}
