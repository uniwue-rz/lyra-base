<?php
/**
* This class can be used to load different kind of configurations. It supports YAML,PHP and XML at the moment.
*
* @author Pouyan Azari <pouyan.azari@uni-wuerzbrug.de>
* @license MIT
*/
namespace De\Uniwue\RZ\Lyra\Base;

use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\FileLoader;

use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigurationLoader{
    /**
    * Placeholder for the loader resolver
    * @var LoaderResolver
    */
    private $loaderResolver;

    /**
    * Placeholder for the delegating Loader
    * @var DelegatingLoader
    */
    private $delegatingLoader;

    /**
    * Placeholder for the container which has the configuration
    * @var Container
    */
    private $container;

    /**
    * Constructor
    *
    * @param Locator $locator The locator which finds the given config files
    */
    public function __construct($locator){
        $this->locator = $locator;
        $this->delegatingLoader = new DelegatingLoader($this->createLoaderResolver());
    }

    /**
    * Returns the container that holds the configuration
    *
    * @return Container
    */
    public function getContainer(){

        return $this->container;
    }

    /**
    * Sets the container for the given configuration
    *
    * @param Container $container 
    */
    public function setContainer($container){
        $this->container = $container;
    }

    /**
    * Returns the loader resolver
    *
    * @return LoaderResolver
    */
    public function getLoaderResolver(){

        return $this->loaderResolver;
    }

    /**
    * Returns the loader
    *
    * @return Loader
    */
    public function getLoader(){

        return $this->delegatingLoader;
    }

    /**
    * Returns the loader resolver for the given configuration locator. At the moment 
    * resolves XML, YAML and PHP files
    *
    * @param FileLocator $locator The file locator for the given configuration
    *
    * @return LoaderResolver
    */
    public function createLoaderResolver(){
        $this->container = new ContainerBuilder();
        $loaderResolver = new LoaderResolver(array(
                new YamlFileLoader($this->container, $this->locator),
                new XmlFileLoader($this->container, $this->locator),
                new PhpFileLoader($this->container, $this->locator)
            ));
        
        return $loaderResolver;
    }

    /**
    * Loads the given configurations
    *
    * @param string $fileName The name of the given file
    * @param Locator $locator The locator object that should be used
    *
    * @return Loader
    */
    public function load($fileName){
        $this->delegatingLoader->load($fileName);
    }
}