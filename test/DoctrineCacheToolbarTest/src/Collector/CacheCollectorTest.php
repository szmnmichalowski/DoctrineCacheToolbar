<?php
/**
 *
 * CacheCollectorTest.php
 *
 * @author:     Szymon Michałowski <szmnmichalowski@gmail.com>
 * @data:       2017-02-09 19:03
 */

namespace DoctrineCacheToolbarTest\Collector;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\Cache\CacheConfiguration;
use Doctrine\ORM\Cache\Logging\StatisticsCacheLogger;
use Zend\Mvc\MvcEvent;
use ZendDeveloperTools\Collector\AbstractCollector;
use DoctrineCacheToolbar\Collector\CacheCollector;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use ZendDeveloperTools\Collector\AutoHideInterface;

/**
 * Class CacheCollectorTest
 * @package DoctrineCacheToolbarTest\Collector
 */
class CacheCollectorTest extends TestCase
{
    /**
     * @var CacheCollector
     */
    protected $collector;

    /**
     * Init test variables
     */
    public function setUp()
    {
        $this->collector = new CacheCollector();
    }

    /**
     * @coversNothing
     */
    public function testClassImplementsProperInterfaces()
    {
        $this->assertInstanceOf(AbstractCollector::class, $this->collector);
        $this->assertInstanceOf(AutoHideInterface::class, $this->collector);
    }

    /**
     * @covers DoctrineCacheToolbar\Collector\CacheCollector::getName
     */
    public function testNameGetter()
    {
        $this->assertTrue(method_exists($this->collector, 'getName'));
        $this->assertEquals('cache.toolbar', $this->collector->getName());
    }

    /**
     * @covers DoctrineCacheToolbar\Collector\CacheCollector::getPriority
     */
    public function testPriorityGetter()
    {
        $this->assertTrue(method_exists($this->collector, 'getPriority'));
        $this->assertEquals(15, $this->collector->getPriority());
    }

    /**
     * @covers DoctrineCacheToolbar\Collector\CacheCollector::setEntityManager
     * @covers DoctrineCacheToolbar\Collector\CacheCollector::getEntityManager
     */
    public function testEntityManagerAccessors()
    {
        $this->assertTrue(method_exists($this->collector, 'setEntityManager'));
        $this->assertTrue(method_exists($this->collector, 'getEntityManager'));

        $em = $this->prophesize(EntityManager::class);
        $this->collector->setEntityManager($em->reveal());

        $this->assertInstanceOf(EntityManager::class, $this->collector->getEntityManager());
    }

    /**
     * @covers DoctrineCacheToolbar\Collector\CacheCollector::getCacheStats
     */
    public function testGetCacheStatsWhenWhenEntityManagerIsNotSet()
    {
        $this->expectException(\LogicException::class);

        $this->collector->getCacheStats();
    }

    /**
     * @covers DoctrineCacheToolbar\Collector\CacheCollector::getCacheStats
     */
    public function testGetCacheStatsWhenEntityManagerIsSet()
    {
        $cacheLogger = $this->prophesize(StatisticsCacheLogger::class);
        $cacheLogger->getPutCount()
            ->willReturn(0)
            ->shouldBeCalled();
        $cacheLogger->getHitCount()
            ->willReturn(0)
            ->shouldBeCalled();
        $cacheLogger->getMissCount()
            ->willReturn(0)
            ->shouldBeCalled();
        $cacheLogger->getRegionsPut()
            ->willReturn(['test_region' => 0])
            ->shouldBeCalled();
        $cacheLogger->getRegionsHit()
            ->willReturn(['test_region' => 0])
            ->shouldBeCalled();
        $cacheLogger->getRegionsMiss()
            ->willReturn(['test_region' => 0])
            ->shouldBeCalled();
        $cacheConfig = $this->prophesize(CacheConfiguration::class);
        $cacheConfig->getCacheLogger()
            ->willReturn($cacheLogger)
            ->shouldBeCalled();
        $config = $this->prophesize(Configuration::class);
        $config->getSecondLevelCacheConfiguration()
            ->willReturn($cacheConfig)
            ->shouldBeCalled();
        $em = $this->prophesize(EntityManager::class);
        $em->getConfiguration()
            ->willReturn($config)
            ->shouldBeCalled();

        $this->collector->setEntityManager($em->reveal());

        $data = $this->collector->getCacheStats();
        $this->assertArrayHasKey('cache-toolbar', $data);
        $this->assertArrayHasKey('total', $data['cache-toolbar']);
        $this->assertArrayHasKey('regions', $data['cache-toolbar']);
        $this->assertArrayHasKey('put', $data['cache-toolbar']['total']);
        $this->assertArrayHasKey('hit', $data['cache-toolbar']['total']);
        $this->assertArrayHasKey('miss', $data['cache-toolbar']['total']);
        $this->assertEquals(0, $data['cache-toolbar']['total']['put']);
        $this->assertEquals(0, $data['cache-toolbar']['total']['hit']);
        $this->assertEquals(0, $data['cache-toolbar']['total']['miss']);
    }

    /**
     * @covers DoctrineCacheToolbar\Collector\CacheCollector::collect
     */
    public function testCollect()
    {
        $this->assertTrue(is_array($this->collector->collect(new MvcEvent())));
        $this->collector->collect(new MvcEvent());
    }

    /**
     * @covers DoctrineCacheToolbar\Collector\CacheCollector::canHide
     */
    public function testCanHide()
    {
        $this->assertTrue(method_exists($this->collector, 'canHide'));

        // Entity manager is not set
        $this->assertTrue($this->collector->canHide());

        // Entity manager is set but second level cache is disabled
        $config = $this->prophesize(Configuration::class);
        $config->isSecondLevelCacheEnabled()
            ->willReturn(false)
            ->shouldBeCalled();
        $em = $this->prophesize(EntityManager::class);
        $em->getConfiguration()
            ->willReturn($config)
            ->shouldBeCalled();
        $this->collector->setEntityManager($em->reveal());

        $this->assertTrue($this->collector->canHide());

        $config = $this->prophesize(Configuration::class);
        $config->isSecondLevelCacheEnabled()
            ->willReturn(true)
            ->shouldBeCalled();
        $em = $this->prophesize(EntityManager::class);
        $em->getConfiguration()
            ->willReturn($config)
            ->shouldBeCalled();

        $reflection = new \ReflectionClass($this->collector);
        $property = $reflection->getProperty('data');
        $property->setAccessible(true);
        $property->setValue($this->collector, ['test']);

        $this->collector->setEntityManager($em->reveal());

        $this->assertFalse($this->collector->canHide());
    }
}