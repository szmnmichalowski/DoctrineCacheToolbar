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
        $this->assertEquals('cache.toolbar', $this->collector->getName());
    }

    /**
     * @covers DoctrineCacheToolbar\Collector\CacheCollector::getPriority
     */
    public function testPriorityGetter()
    {
        $this->assertEquals(15, $this->collector->getPriority());
    }
}