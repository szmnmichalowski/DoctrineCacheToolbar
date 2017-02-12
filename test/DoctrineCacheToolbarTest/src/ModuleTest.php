<?php
/**
 *
 * ModuleTest.php
 *
 * @author:     Szymon Michałowski <szmnmichalowski@gmail.com>
 * @data:       2017-02-08 20:37
 */

namespace DoctrineCacheToolbarTest;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use DoctrineCacheToolbar\Module;
use Zend\ModuleManager\ModuleManager;
use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\Cache\CacheConfiguration;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Cache\Logging\StatisticsCacheLogger;

/**
 * Class ModuleTest
 * @package DoctrineCacheToolbarTest
 */
class ModuleTest extends TestCase
{
    /**
     * @var Module
     */
    protected $module;

    /**
     * Init test variables
     */
    public function setUp()
    {
        $this->module = new Module();
    }

    /**
     * @covers DoctrineCacheToolbar\Module::init
     */
    public function testInit()
    {
        $this->assertTrue(method_exists($this->module, 'init'));

        $sharedEventManager = $this->prophesize(SharedEventManager::class);
        $sharedEventManager->attach('DoctrineCacheToolbar',
            MvcEvent::EVENT_DISPATCH,
            [$this->module, 'addCacheLogger'],
            100)
            ->shouldBeCalled();
        $eventManager = $this->prophesize(EventManager::class);
        $eventManager->getSharedManager()
            ->willReturn($sharedEventManager)
            ->shouldBeCalled();
        $manager = $this->prophesize(ModuleManager::class);
        $manager->getEventManager()
            ->willReturn($eventManager)
            ->shouldBeCalled();

        $this->module->init($manager->reveal());
    }

    /**
     * @covers DoctrineCacheToolbar\Module::addCacheLogger
     */
    public function testAddCacheLogger()
    {
        $this->assertTrue(method_exists($this->module, 'addCacheLogger'));

        $cacheLogger = new StatisticsCacheLogger();
        $cacheConfig = $this->prophesize(CacheConfiguration::class);
        $cacheConfig->setCacheLogger($cacheLogger)
            ->shouldBeCalled();
        $config = $this->prophesize(Configuration::class);
        $config->getSecondLevelCacheConfiguration()
            ->willReturn($cacheConfig)
            ->shouldBeCalled();
        $em = $this->prophesize(EntityManager::class);
        $em->getConfiguration()
            ->willReturn($config)
            ->shouldBeCalled();
        $serviceManager = $this->prophesize(ServiceManager::class);
        $serviceManager->get('Doctrine\ORM\EntityManager')
            ->willReturn($em)
            ->shouldBeCalled();
        $application = $this->prophesize(Application::class);
        $application->getServiceManager()
            ->willReturn($serviceManager)
            ->shouldBeCalled();
        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getApplication()
            ->willReturn($application)
            ->shouldBeCalled();

        $this->module->addCacheLogger($mvcEvent->reveal());
    }

    /**
     * @covers DoctrineCacheToolbar\Module::getConfig
     */
    public function testGetConfig()
    {
        $this->assertTrue(is_array($this->module->getConfig()));
    }

    /**
     * @covers DoctrineCacheToolbar\Module::getModuleDependencies
     */
    public function testGetModuleDependencies()
    {
        $this->assertTrue(is_array($this->module->getModuleDependencies()));
        $this->assertEquals(['ZendDeveloperTools'], $this->module->getModuleDependencies());
    }
}