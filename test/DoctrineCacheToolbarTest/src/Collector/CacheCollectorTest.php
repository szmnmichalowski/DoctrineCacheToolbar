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
     * @covers DoctrineCacheToolbar\Collector\CacheCollector::setCacheLogger
     * @covers DoctrineCacheToolbar\Collector\CacheCollector::getCacheLogger
     */
    public function testCacheLoggerAccessors()
    {
        $this->assertTrue(method_exists($this->collector, 'setCacheLogger'));
        $this->assertTrue(method_exists($this->collector, 'getCacheLogger'));

        $cacheLogger = 'test';
        $this->collector->setCacheLogger($cacheLogger);

        $this->assertEquals($cacheLogger, $this->collector->getCacheLogger());
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
        $cacheConfig = $this->prophesize(CacheConfiguration::class);
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
        $this->assertArrayHasKey('put', $data['cache-toolbar']['total']);
        $this->assertArrayHasKey('hit', $data['cache-toolbar']['total']);
        $this->assertArrayHasKey('miss', $data['cache-toolbar']['total']);
    }

    /**
     * @covers DoctrineCacheToolbar\Collector\CacheCollector::getCacheStats
     */
    public function testGetCacheStatsDefaultValues()
    {
        $cacheConfig = $this->prophesize(CacheConfiguration::class);
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
        $this->assertEquals(0, $data['cache-toolbar']['total']['put']);
        $this->assertEquals(0, $data['cache-toolbar']['total']['hit']);
        $this->assertEquals(0, $data['cache-toolbar']['total']['miss']);
    }

    /**
     * @covers  DoctrineCacheToolbar\Collector\CacheCollector::collect
     */
    public function testCollect()
    {
        $this->assertTrue(is_array($this->collector->collect(new MvcEvent())));
    }
}