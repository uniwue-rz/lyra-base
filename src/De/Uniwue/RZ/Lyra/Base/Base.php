<?php
/**
* Lyra Base is the base for most of Lyra programs. This connects and attaches different systems together.
*
* @author Pouyan Azari <pouyan.azari@uni-wuerzburg.de>
* @license MIT
*/
namespace De\Uniwue\RZ\Lyra\Base;

use De\Uniwue\RZ\Lyra\Exceptions\ConfigFormatNotValidException;

use Symfony\Component\Config\FileLocator;

class Base{
    /**
    * Placeholder for the name of the given application
    * @var string
    */ 
    private $name;

    /**
    * Placeholder for the paths for the configuration
    * @var array
    */
    private $paths;

    /**
    * Constructor class for the Base
    *
    * @param string $name   The name of the given application
    * @param array  $paths  The paths to the configurations that should be read
    */
    public function __construct($name, $paths= array()){
        $this->name = $name;
        $this->paths = $paths;
    }

    /**
    * Sets paths for the given Base Config
    *
    * @param array $paths The array including path strings
    */
    public function setPaths($paths = array()){
        $this->paths = $paths;
    }

    /**
    * Returns the paths for the given base
    *
    * @return array
    */
    public function getPaths(){

        return $this->paths;
    }

    /**
    * Add multiple paths for the configurations
    *
    * @param array $paths The paths that should be added to existing ones
    */
    public function addPaths($paths = array()){
        $this->paths = \array_merge($this->paths, $paths);
    }

    /**
    * Adds the path to the existing path
    *
    * @param string $path The path for the configuration
    */
    public function addPath($path){
        $this->path = $this->addPaths(array($path));
    }

    /**
    * Returns the name of the application
    *
    * @return string
    */
    public function getName(){

        return $this->name;
    }

    /**
    * Sets the name of the application
    *
    * @param string $name The name of the given application
    */
    public function setName($name){
        $this->name = $name;
    }

    /**
    * Returns the running environment of the given application
    * Defaults to prod environment.
    *
    * @return string |
    */
    public function getRunningEnv(){
        if(getenv("LYRA_ENV") !== false){

            return getenv("LYRA_ENV");
        }

        return "prod";
    }

    /**
    * Returns the list of supported configuration formats
    *
    * @return array
    */
    public function getSupportedConfigFormats(){

        return array(
                "yml",
                "xml",
                "php",
            );
    }

    /**
    * Locates the configuration in the given and default paths
    * The default paths are /etc/appName and /home/user/.appName or the running directory
    * 
    * @return array
    */
    public function getConfigurationLocator(){
        $configurationDirectories = $this->getAllConfigurationDirectories();
        $locator = new FileLocator($configurationDirectories);

        return $locator;
    }

    /**
    * Returns the list of all Directories that contain the configurations
    *
    * @return array
    */
    public function getAllConfigurationDirectories(){
        $configurationDirectories = array(
            "/etc/".$this->name."/",
            $this->getHome()."/.".$this->name."/",
            __DIR__,

        );
        // merge the paths with the default configuration locations
        $configurationDirectories = array_merge($configurationDirectories, $this->paths);

        return $configurationDirectories;
    }

    /**
    * Parses the configurations and returns the needed loaders
    *
    * @return Container
    */
    public function getConfigurationContainer(){
        $locator = $this->getConfigurationLocator();
        $loader = new ConfigurationLoader($locator);
        $configs = array();

        foreach($this->getSupportedConfigFormats() as $format){
            // List all the available configurations
            foreach($this->getAllConfigurationDirectories() as $dir){
                $configs = array_merge($configs, $this->getAllAvailableConfigFiles($dir, $this->getRunningEnv(), $format));
            }
            // Load them
            foreach($configs as $config){
                $loader->load($config);
            }
        }

        return $loader->getContainer();
    }

    /**
    * Returns the list of available config files in the given path.
    *
    * @param string $path The path to the configuration
    *
    * @return array
    */
    public function getAllAvailableConfigFiles($path, $env, $format){
        $result = array();
        // Return empty when directory does not exists.
        if(\file_exists($path) === false){

            return $result;
        }
        $files = \scandir($path);
        $regex = '/\S+\_'.$env.'\.'.$format.'/';
        foreach($files as $f){
            if(preg_match($regex, $f)){
                \array_push($result, $f);
            }
        }

        return $result;
    }

    /**
    * Returns the home of the user running the PHP command
    *
    * @return string | null
    */
    public function getHome(){
        if(isset($_SERVER["HOME"])){
            
            return $_SERVER["HOME"];
        }

        if(isset($_ENV["HOME"])){

            return $_ENV["HOME"];
        }

        if(getenv("HOME") !== false){

            return getenv("HOME");
        }

        return null;
    }
}