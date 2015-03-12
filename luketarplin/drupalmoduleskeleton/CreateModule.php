<?php
/**
* @author Luke Alexander Tarplin <luke.tarplin@gmail.com>
* Drupal Module Skeleton Create Module Class
*/
namespace luketarplin\drupalmoduleskeleton;

use Composer\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateModule extends Command
{
	public static function runTask()
	{
		$class = new self();
		
		$class->getIO()->write(__METHOD__);
	}
}