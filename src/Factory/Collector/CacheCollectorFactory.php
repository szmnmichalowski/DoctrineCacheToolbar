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
 * CacheCollectorFactory.php
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
 * @author: Szymon Michałowski <szmnmichalowski@gmail.com>
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
        $class->setEntityManager($container->get('Doctrine\ORM\EntityManager'));

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