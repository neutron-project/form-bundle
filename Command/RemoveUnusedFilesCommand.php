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
 * Command for cleaning files in temporary directory
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class RemoveUnusedFilesCommand extends ContainerAwareCommand
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        $this
            ->setName('neutron:form:remove-unused-files')
            ->setDescription('Remove unused images and files in temporary directory')
            ->addArgument('maxAge', InputArgument::OPTIONAL, 'Max age in seconds', 7200)
        ;
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        $this->getContainer()->get('neutron_form.manager.image_manager')
            ->removeUnusedImages($input->getArgument('maxAge'));
            
        $output->writeln('Files successfully removed');
    }
}
