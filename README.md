##DoctrineCacheToolbar

DoctrineCacheToolbar is a [Zend Framework 2/3](http://framework.zend.com/) module which is integrated with [Doctrine 2](http://www.doctrine-project.org/).<br/>
It does show statistics for regions while [Second Level Cache](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/second-level-cache.html) is enabled in doctrine's configuration    

![Statistics in DoctrineCacheToolbar](http://i.imgur.com/Vu5trCG.png)

###Installation

You can install this module by cloning this project into your **./vendor/** directory, or using composer, which is more recommended:<br/>
**1.**
Add this project into your composer.json
```
"require": {
    "szmnmichalowski/doctrine-cache-toolbar": "dev-master"
}
```
**2.**
Update your dependencies
```
$ php composer.phar update
```

**3.**
Add module to your **application.config.php**. It requires `DoctrineModule`, `DoctrineORMModule` and `ZendDeveloperTools`.
```
return array(
    'modules' => array(
        'DoctrineModule',
        'DoctrineORMModule',
        'ZendDeveloperTools',
        'DoctrineCacheToolbar' // <- Add this line
    )
);
```

###Usage

This module does NOT need any configuration to work. It will show statistics for cache regions if you have [Second Level Cache](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/second-level-cache.html) enabled.

###What is a Second Level Cache?
Second Level Cache is a Doctrine's feature added in version 2.5. It allows to cache entity with their associations. More information you can find on Doctrine's website [http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/second-level-cache.html](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/second-level-cache.html)

###How to configure Second Level Cache in Zend Framework 2/3?

Add following code to Doctrine's configuration file:

    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'metadata_cache'    => 'filesystem', // name of cache adapter
                'query_cache'       => 'filesystem', // name of cache adapter
                'result_cache'      => 'filesystem', // name of cache adapter
                'hydration_cache'   => 'filesystem', // name of cache adapter
                'second_level_cache' => [
                    'enabled'               => true,
                    'default_lifetime'      => 300,
                    'default_lock_lifetime' => 300,
                    'file_lock_region_directory' => __DIR__.'../../data/cache',
                ]
            ]
        ]
    ]
    
You can also define regions and lifetime for each of them. Add following array inside `second_level_cache` index:

    'regions' => [
        'my_region_name' => [
            'lifetime' => 100,
            'lock_lifetime' => 200
        ],
    ]
    
Then, in your entity add:

    namespace Application\Entity;
        
    use Doctrine\ORM\Mapping AS ORM;
        
    /**
     * @ORM\Entity
     * @ORM\Table(name="app_post")
     * @ORM\Cache("NONSTRICT_READ_WRITE", region="my_region_name")
     */
    class Post
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        private $id;
            
        /**
         * @ORM\Column(type="string", length=40, nullable=false)
         */
        private $title;
    }
    
From now toolbar should show statistica similar to this one on image above.