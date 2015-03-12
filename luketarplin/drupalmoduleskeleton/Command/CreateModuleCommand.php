<?php
/**
* @author Luke Alexander Tarplin <luke.tarplin@gmail.com>
* Drupal Module Skeleton Create Module Command Class
*/
namespace luketarplin\drupalmoduleskeleton\Command;

use Composer\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateModuleCommand extends Command
{
    protected function configure()
    {
        $this->setName('drupal-create-module')
             ->setDescription('Create a new Drupal 8 Skeleton Module')
             ->setHelp('<info>This command allows you to create a new Drupal 8 Skeleton Module</info>');
    }
	
	/**
	* @param Symfony\Component\Console\Input\InputInterface $input
	* @param Symfony\Component\Console\Input\OutputInterface $output
	*/
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getIO()->write(__METHOD__);
    }
}