<?php
/**
 *
 * CacheCollectorFactory.php
 *
 * @author:     Szymon Michałowski <szmnmichalowski@gmail.com>
 * @data:       2017-02-08 21:22
 */

namespace DoctrineCacheToolbar\Factory\Collector;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineCacheToolbar\Collector\CacheCollector;

/**
 * Class CacheCollectorFactory
 * @package DoctrineCacheToolbar\Factory\Collector
 */
class CacheCollectorFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param null $name
     * @param array|null $options
     * @return mixed|CacheCollector
     */
    public function __invoke(ContainerInterface $container, $name = null, array $options = null)
    {
        $name = $name ? $name : CacheCollector::class;
        $class = new $name;

        return $class;
    }

    /**
     * Create and return ControllerManager instance
     *
     * For use with zend-servicemanager v2; proxies to __invoke().
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed|CacheCollector
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, CacheCollector::class);
    }
}