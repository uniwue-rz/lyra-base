<?php
/**
* The test class for the Base class
*
* @author Pouyan Azari <pouyan.azari@uni-wuerzbrug.de>
* @license MIT
*/

namespace De\Uniwue\RZ\Lyra\Base;

class BaseTest extends \PHPUnit_Framework_TestCase{

    // Sets up the given test
    public function setUp(){
        global $configRoot;
        $this->root = $configRoot;
    }

    /**
    * Test the base init
    *
    */
    public function testBaseInit(){
        $base = new Base("name", array($this->root));
        $this->assertEquals(\get_class($base), "De\Uniwue\RZ\Lyra\Base\Base");
    }

    /**
    * Tests the get name
    *
    */
    public function testGetName(){
        $base = new Base("name", array($this->root));
        $this->assertEquals($base->getName(), "name");
    }

    /**
    * Tests the set name
    */
    public function testSetName(){
        $base = new Base("name", array($this->root));
        $base->setName("hello");
        $this->assertEquals($base->getName(), "hello");
    }

    /**
    * Tests the get path method
    *
    */
    public function testGetPaths(){
        $base =  new Base("name", array($this->root));
        $this->assertEquals($base->getPaths(), array($this->root));
    }

    /**
    * Tests the set path method
    *
    */
    public function testSetPaths(){
        $base = new Base("name");
        $base->setPaths(array($this->root));
        $this->assertEquals($base->getPaths(), array($this->root));
    }

    /**
    * Tests the add single path method
    *
    */
    public function testAddPath(){
        $base = new Base("name");
        $base->addPath($this->root);
        $this->assertEquals($base->getPaths(), array($this->root));
    }
    
    /**
    * Tests the add Paths method
    *
    */
    public function testAddPaths(){
        $base = new Base("name");
        $base->addPaths(array($this->root));
        $this->assertEquals($base->getPaths(), array($this->root));
    }

    /**
    * Test the running env method
    *
    * There is a good documentation about the PUTENV
    *
    * @link http://php.net/manual/en/function.putenv.php
    */
    public function testGetRunningEnv(){
        $base = new Base("name");
        // Default value is always prod
        $this->assertEquals($base->getRunningEnv(), "prod");
        \putenv("LYRA_ENV=test");
        // Check for the value set
        $this->assertEquals($base->getRunningEnv(), "test");
        \putenv("LYRA_ENV");
    }

    /**
    * Test the configuration locator method
    *
    */
    public function testGetConfigurationLocator(){
        $base = new Base("name", array($this->root));
        $this->assertEquals(\get_class($base->getConfigurationLocator()), "Symfony\Component\Config\FileLocator");
    }

    /**
    * Test the configuration container
    *
    */
    public function testGetConfigurationContainer(){
        $base = new Base("name", array($this->root));
        $container  = $base->getConfigurationContainer();
        $this->assertEquals(\get_class($container), "Symfony\Component\DependencyInjection\ContainerBuilder");
    }
}