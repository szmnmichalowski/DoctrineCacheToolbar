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
use ZendDeveloperTools\Collector\AbstractCollector;
use DoctrineCacheToolbar\Collector\CacheCollector;
use Doctrine\ORM\EntityManager;

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
        $em = $this->prophesize(EntityManager::class);
        $this->collector->setEntityManager($em->reveal());

        $this->assertTrue(method_exists($this->collector, 'getEntityManager'));
        $this->assertInstanceOf(EntityManager::class, $this->collector->getEntityManager());
    }
}