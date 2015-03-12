<?php
/**
* @author Luke Alexander Tarplin <luke.tarplin@gmail.com>
* Drupal Module Skeleton Plugin Class
*/
namespace luketarplin\drupalmoduleskeleton;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use luketarplin\drupalmoduleskeleton\Installers\DrupalModuleSkeletonInstaller;

class DrupalModuleSkeletonPlugin implements PluginInterface
{
	/**
	 * @param Composer $composer
	 * @param IOInterface $io
	 */
	public function activate(Composer $composer, IOInterface $io) {
		$installer = new DrupalModuleSkeletonInstaller($io, $composer);
		$composer->getInstallationManager()->addInstaller($installer);
	}
}