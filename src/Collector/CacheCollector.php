<?php
/**
 *
 * CacheCollector.php
 *
 * @author:     Szymon Michałowski <szmnmichalowski@gmail.com>
 * @data:       2017-02-08 21:58
 */

namespace DoctrineCacheToolbar\Collector;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\MvcEvent;
use ZendDeveloperTools\Collector\AbstractCollector;

/**
 * Class CacheCollector
 * @package DoctrineCacheToolbar\Collector
 */
class CacheCollector extends AbstractCollector
{
    /**
     * @var string
     */
    protected $name = 'cache.toolbar';

    /**
     * @var int
     */
    protected $priority = 15;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param MvcEvent $mvcEvent
     * @return array
     */
    public function collect(MvcEvent $mvcEvent)
    {
        if (!isset($this->data)) {
            return $this->data['cache-toolbar'] = [];
        }
    }

    /**
     * Get cache stats
     *
     * @return array
     */
    public function getCacheStats()
    {
        if (!$this->getEntityManager()) {
            throw new \LogicException('Entity Manager must be set.');
        }

        $config = $this->getEntityManager()->getConfiguration();
        $logger = $config->getSecondLevelCacheConfiguration()
            ->getCacheLogger();

        $total = [
            'total' => [
                'put' => $logger->getPutCount(),
                'hit' => $logger->getHitCount(),
                'miss' => $logger->getMissCount(),
            ]
        ];

        $regions = [
            'regions' => [
                'put' => $logger->getRegionsPut(),
                'hit' => $logger->getRegionsHit(),
                'miss' => $logger->getRegionsMiss(),
            ]
        ];

        $this->data['cache-toolbar'] = array_merge($total, $regions);
        return $this->data;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}