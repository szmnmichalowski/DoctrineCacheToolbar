<?php
/**
 *
 * CacheCollectorFactoryTest.php
 *
 * @author:     Szymon Michałowski <szmnmichalowski@gmail.com>
 * @data:       2017-02-08 21:16
 */

namespace DoctrineCacheToolbarTest\Factory\Collector;

use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;
use DoctrineCacheToolbar\Factory\Collector\CacheCollectorFactory;
use DoctrineCacheToolbar\Collector\CacheCollector;

/**
 * Class CacheCollectorFactoryTest
 * @package DoctrineCacheToolbarTest\Factory\Collector
 */
class CacheCollectorFactoryTest extends TestCase
{
    /**
     * @var CacheCollectorFactory
     */
    protected $factory;

    /**
     * Init test variables
     */
    public function setUp()
    {
        $this->factory = new CacheCollectorFactory();
    }

    /**
     * @covers DoctrineCacheToolbar\Factory\Collector\CacheCollectorFactory::createService
     */
    public function testCanCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $result = $this->factory->createService($serviceLocator->reveal());

        $this->assertTrue(is_object($result));
        $this->assertInstanceOf(CacheCollector::class, $result);
    }

    /**
     * @covers DoctrineCacheToolbar\Factory\Collector\CacheCollectorFactory::__invoke
     */
    public function testCanInvokeFactory()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $result = $this->factory->__invoke($container->reveal());

        $this->assertTrue(is_object($result));
        $this->assertInstanceOf(CacheCollector::class, $result);
    }
}