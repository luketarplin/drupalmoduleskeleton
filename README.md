# drupalmoduleskeleton
Create a Skeleton Drupal 8 Module
This plugin adds Drupal 8 Skeleton module functionality to composer

Add the following script to your composer.json file:
  
  	"scripts": {
        "drupal:create:module": "luketarplin\\drupalmoduleskeleton\\CreateModule::runTask"
    },
    
To use issue the following command and follow the instructions:

  composer drupal:create:module
