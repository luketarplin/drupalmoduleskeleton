<?php
/**
* @author Luke Alexander Tarplin <luke.tarplin@gmail.com>
* Drupal Module Skeleton Installer Class
*/
namespace luketarplin\drupalmoduleskeleton\Installers;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class DrupalModuleSkeletonInstaller extends LibraryInstaller
{
	const DIRECTORY_NAME = 'luketarplin';

	/**
	 * @param Composer\Package\PackageInterface $package
	 * @return String
	 */
	protected function getPackageBasePath(PackageInterface $package) 
	{
		$this->initializeVendorDir();
		
		$path = $this->vendorDir ? $this->vendorDir . \DIRECTORY_SEPARATOR : '';
		
		return $path . self::DIRECTORY_NAME;
	}
	
	/**
	 * @param String $type
	 * @return Boolean
	 */
	public function supports($type) 
	{
		echo $type.\PHP_EOL;
	
		return $type === '';
	}
}