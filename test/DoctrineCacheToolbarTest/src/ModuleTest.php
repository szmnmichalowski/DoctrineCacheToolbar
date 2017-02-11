<?php
/**
 *
 * ModuleTest.php
 *
 * @author:     Szymon Michałowski <szmnmichalowski@gmail.com>
 * @data:       2017-02-08 20:37
 */

namespace DoctrineCacheToolbarTest;

use PHPUnit\Framework\TestCase;
use DoctrineCacheToolbar\Module;

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