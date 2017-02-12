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
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Doctrine\ORM\Cache\Logging\StatisticsCacheLogger;

/**
 * Class Module
 * @package DoctrineCacheToolbar
 */
class Module implements ConfigProviderInterface, DependencyIndicatorInterface
{
    /**
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $sharedEventManager = $app->getEventManager();
        $sharedEventManager->attach(MvcEvent::EVENT_DISPATCH,
            [$this, 'addCacheLogger'], 100);
    }

    /**
     * @param MvcEvent $event
     * @return MvcEvent
     */
    public function addCacheLogger(MvcEvent $event)
    {
        $app = $event->getApplication();
        $em = $app->getServiceManager()->get('Doctrine\ORM\EntityManager');
        $logger = new StatisticsCacheLogger();
        $config = $em->getConfiguration();
        $config->getSecondLevelCacheConfiguration()
            ->setCacheLogger($logger);

        return $event;
    }

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