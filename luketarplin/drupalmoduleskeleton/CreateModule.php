<?php
/**
* @author Luke Alexander Tarplin <luke.tarplin@gmail.com>
* Drupal Module Skeleton Create Module Class
*/
namespace luketarplin\drupalmoduleskeleton;

use Composer\Script\Event;
use Composer\IO\ConsoleIO;
use Symfony\Component\Yaml\Dumper;

class CreateModule
{
	const INLINE_LEVEL = 1;
	
	const BUILD_TAG    = 'Built using the Drupal 8 Module Skeleton - https://github.com/luketarplin/drupalmoduleskeleton';

	/**
	* Composer\IO\ConsoleIO $IO Input / Output Object
	*/
	protected $IO;
	
	protected $shortName;
	
	protected $longName;
	
	protected $package;
	
	protected $description;
	
	protected $modulePath 	= './modules';
	
	/**
	* Array $defaults Module Defaults
	*/
	protected $defaults   	= array(
		'type'				=> 'module',
		'core'				=> '8.x',
		'dependencies' 		=>	null,
		'routing' 			=> true,
		'install'			=> true,
	);

	/**
	* String $logo Composer Plugin Logo
	*/
	private static $logo 	= <<<EOT
    ___    _____   _   _  _____   __     _
   |# _ \ |# _  | |#| | ||# _ *| /# \   |*|     MODULE
   |#| |*||#|_| | |#| | ||#|_|_|/#/\ \  |*|     SKELETON
   |#|_|*||#| \ \ |#|_| ||#|   /#/__\ \ |*|___  BY LUKE TARPLIN
   |____/ |_|  \_\|_____||_|  /_/    \_\|_____|
   
EOT;

	public function __construct(ConsoleIO $IO)
	{
		$this->IO = $IO;
	}

	/**
	* @param Composer\Script\Event $event
	* @param $args arguments
	* Run Task defined in composer.json
	*/
	public static function runTask(Event $event, $args = null)
	{
		//Get the IO Object
		$IO 	= $event->getIO();
		
		//Get an instance of this class
		$class 	= new self($IO);
		$class->outputLogo()
		      ->getModulesPath()
		      ->askModuleName()
			  ->askModulePackage()
			  ->askModuleDependencies()
			  ->askModuleDescription()
			  ->askAddRouting()
			  ->askInstall()
			  ->build()
			  ->finish();
		
		//Debugging Output
		/*$reflection	= new \ReflectionClass($class);
			  
		foreach($reflection->getProperties() as $properties){
			if($properties->getName() !== 'IO'){
				$properties->setAccessible(true);
				var_dump($properties->getName(),$properties->getValue($class));
			}
		}*/
		//End of Debugging Output
	}
	
	/**
	* Output the Logo
	* @return $this
	*/
	public function outputLogo()
	{
		//Write the Logo to output
		$this->IO->write(self::$logo);
		
		return $this;
	}
	
	/**
	* Get the path of the modules directory
	* @return $this
	*/
	public function getModulesPath()
	{
		$modulePath 			= $this->IO->ask('Enter path to Drupal 8 (default ./modules): ');
	
		if( ! empty($modulePath))
			$this->modulePath 	= $modulePath;
			
		return $this;
	}
	
	/**
	* Get the module short / long names
	* @return $this
	*/
	public function askModuleName()
	{
		$modulePath 	 = $this->modulePath;
	
		$this->shortName = $this->IO->askAndValidate('Enter a module short name (eg smtp): ',
							function($answer) use($modulePath){
								if(empty($answer))
									throw new \RuntimeException('Module short name can not be empty!');
								elseif(is_dir("{$modulePath}/{$answer}"))
									throw new \RuntimeException('A module with that short code already exists!');
								return strtolower($answer);
							});
		$this->longName  = $this->IO->askAndValidate('Enter a module long name (eg SMTP Authentication Support): ',
							function($answer){
								if(empty($answer))
									throw new \RuntimeException('Module short name can not be empty!');
								return $answer;
							});
		return $this;
	}
	
	/**
	* Get the module package
	* @return $this
	*/
	public function askModulePackage()
	{
		$this->package 		= $this->IO->ask('Enter a module package: ');
		
		return $this;
	}	
	
	/**
	* Get the module description
	* @return $this
	*/
	public function askModuleDescription()
	{
		$this->description 	= $this->IO->ask('Enter a module description: ');
		
		return $this;
	}
	
	/**
	* Get the module dependencies
	* @return $this
	*/
	public function askModuleDependencies()
	{
		$this->defaults['dependencies'] 	= $this->IO->ask('Enter a list of module dependencies (eg rest,serialization): ');
		
		return $this;
	}
	
	/**
	* Get the module routing preferences
	* @return $this
	*/
	public function askAddRouting()
	{
		$this->defaults['routing'] 			= 'Y' === $this->IO->askAndValidate('Do you want a routing.yml file (Y / N): ',
													  function($answer){
														if($answer !== 'N' && $answer !== 'Y')
															throw new \RuntimeException('Please choose Y or N!');
															
														return $answer;
													  });
		
		return $this;
	}
	
	public function askInstall()
	{
		$this->defaults['install'] 			= 'Y' === $this->IO->askAndValidate('Do you want an install file (Y / N): ',
													  function($answer){
														if($answer !== 'N' && $answer !== 'Y')
															throw new \RuntimeException('Please choose Y or N!');
															
														return $answer;
													  });
		
		return $this;
	}
	
	/**
	* Build the module based on options gathered above
	*/
	public function build()
	{
		//Create a new module directory
		$dir 					= mkdir("{$this->modulePath}/{$this->shortName}", 0700);
		
		//Build the Yaml data
		$yaml					= new Dumper();
		$infoYaml				= $yaml->dump(array(
			'name' 				=> $this->longName,
			'description' 		=> $this->description,
			'package'			=> $this->package,
			'type'				=> $this->defaults['type'],
			'core'				=> $this->defaults['core'],
		), self::INLINE_LEVEL);

		//Check whether we have any dependencies
		if( ! is_null($this->defaults['dependencies'])){
			//Add Dependencies
			$deps				= explode(',',$this->defaults['dependencies']);
			
			if(is_array($deps)){
				$infoYaml			.= 'dependencies:'.\PHP_EOL;
				
				foreach($deps as $dep){
					$infoYaml		.= '  - '.$dep.\PHP_EOL;
				}
			}
		}
		
		//Add Build Comments
		$infoYaml				.= '#generator: '.self::BUILD_TAG;
		
		//Write the *.info.yml file
		file_put_contents("{$this->modulePath}/{$this->shortName}/{$this->shortName}.info.yml",$infoYaml);
	
		//Build *.routing.yml file if required
		if($this->defaults['routing'] === true){
			$upperShortName 	= strtoupper($this->shortName);
		
			$routingYaml		= <<<EOT
#{$this->shortName}.config:
#  path: '/admin/config/system/{$this->shortName}'
#  defaults:
#    _title: '{$this->longName}'
#    _form: 'Drupal\\{$this->shortName}\Form\\{$upperShortName}ConfigForm'
#  requirements:
#    _permission: 'administer {$this->shortName} module'
EOT;
		
			//Write the *.routing.yml file
			file_put_contents("{$this->modulePath}/{$this->shortName}/{$this->shortName}.routing.yml",$routingYaml);
		}
		
		//Build *.install file if required
		if($this->defaults['routing'] === true){
			$install			= <<<EOT
<?php

/**
 * @file
 * The installation instructions for {$this->longName}.
 */

/**
 * Implements hook_install().
 */
function {$this->shortName}_install() {
	//Setting default config vars.
	//\Drupal::service('config.factory')->getEditable('{$this->shortName}.settings')
	//	->set('', 1)
	//	->save();
}

/**
 * Implements hook_uninstall().
 */
function {$this->shortName}_uninstall() {
	//Setting default config vars.
	//\Drupal::service('config.factory')->getEditable('{$this->shortName}.settings')
	//	->delete();
}
EOT;
		
			//Write the *.install file
			file_put_contents("{$this->modulePath}/{$this->shortName}/{$this->shortName}.install",$install);
		}
		
		//Build *.module file		
		$module			= <<<EOT
<?php

/**
 * @file
 * {$this->longName}.
 */
 
 /**
 * Implements hook_help().
 */
function {$this->shortName}_help(\$path, \$arg) {
  switch (\$path) {
    case 'admin/help#{$this->shortName}':
      return t('Help Text here...');
  }
}
EOT;

		//Write the *.module file
		file_put_contents("{$this->modulePath}/{$this->shortName}/{$this->shortName}.module",$module);
		
		//Build the src dir
		mkdir("{$this->modulePath}/{$this->shortName}/src", 0700);
		
		return $this;
	}
	
	/**
	* Finish up and output the files created
	*/
	public function finish()
	{
		$this->IO->write(PHP_EOL.'Built the following files & directories: ');
		$this->IO->write(str_repeat('=',40));
	
		$files 	= new \FilesystemIterator("{$this->modulePath}/{$this->shortName}", \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS);
		foreach ($files as $fileinfo) {
			$this->IO->write($fileinfo->getFilename());
		}
		
		return;
	}	
}