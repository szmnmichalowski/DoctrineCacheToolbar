<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * CacheCollector.php
 * @data:       2017-02-08 21:58
 */

namespace DoctrineCacheToolbar\Collector;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\MvcEvent;
use ZendDeveloperTools\Collector\AbstractCollector;
use ZendDeveloperTools\Collector\AutoHideInterface;

/**
 * Class CacheCollector
 * @package DoctrineCacheToolbar\Collector
 * @author: Szymon Michałowski <szmnmichalowski@gmail.com>
 */
class CacheCollector extends AbstractCollector implements AutoHideInterface
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
            return $this->data = [];
        }
    }

    /**
     * {@inheritdoc}
     * @return bool
     */
    public function canHide()
    {
        if (!$this->getEntityManager()) {
            return true;
        }

        $isCacheEnabled = $this->getEntityManager()
            ->getConfiguration()
            ->isSecondLevelCacheEnabled();

        if (!$isCacheEnabled) {
            return true;
        }

        return false;
    }

    /**
     * Has cache logger
     *
     * @return bool
     */
    public function hasCacheLogger()
    {
        $config = $this->getEntityManager()->getConfiguration();

        if (! $config->isSecondLevelCacheEnabled()) {
            return false;
        }

        $logger = $config->getSecondLevelCacheConfiguration()
            ->getCacheLogger();

        return $logger !== null;
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

        if (!$config->isSecondLevelCacheEnabled()) {
            return $this->data;
        }

        $logger = $config->getSecondLevelCacheConfiguration()
            ->getCacheLogger();

        if (null === $logger) {
            throw new \LogicException('Cache logger must be set.');
        }

        $info = [
            'info' => [
                'metadata_adapter'    => is_object($config->getMetadataCacheImpl())
                    ? get_class($config->getMetadataCacheImpl())
                    : 'NA',
                'query_adapter'       => is_object($config->getQueryCacheImpl())
                    ? get_class($config->getQueryCacheImpl())
                    : 'NA',
                'result_adapter'      => is_object($config->getResultCacheImpl())
                    ? get_class($config->getResultCacheImpl())
                    : 'NA',
                'hydration_adapter'   => is_object($config->getHydrationCacheImpl())
                    ? get_class($config->getHydrationCacheImpl())
                    : 'NA',
            ]
        ];
        $total = [
            'total' => [
                'put' => $logger->getPutCount(),
                'hit' => $logger->getHitCount(),
                'miss' => $logger->getMissCount(),
            ]
        ];
        $regions = [
            'regions' => [
                'put' => $logger->getRegionsPut() ?: ['None' => null],
                'hit' => $logger->getRegionsHit() ?: ['None' => null],
                'miss' => $logger->getRegionsMiss() ?: ['None' => null],
            ]
        ];

        $this->data = array_merge($info, $total, $regions);
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
