<?php
/**
 *
 * Module.php
 *
 * @author:     Szymon Michałowski <szmnmichalowski@gmail.com>
 * @data:       2017-02-08 19:36
 */

namespace DoctrineCacheToolbar;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;

/**
 * Class Module
 * @package DoctrineCacheToolbar
 */
class Module implements ConfigProviderInterface, DependencyIndicatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return include __DIR__.'/../config/module.config.php';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleDependencies()
    {
        return ['ZendDeveloperTools'];
    }
}