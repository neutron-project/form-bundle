<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for removing images in cache directory
 *
 * @author NNikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class RemoveCachedImagesCommand extends ContainerAwareCommand
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        $this
            ->setName('neutron:form:remove-cached-images')
            ->setDescription('Removes images in cache directory')
            ->addArgument('filter', InputArgument::OPTIONAL, 'avalanche filter')
        ;
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        $this->getContainer()->get('neutron_form.manager.image_manager')
            ->removeImagesFromCacheDirectory($input->getArgument('filter'));
        
        $output->writeln('Images in cached directory are removed.');
    }
}
