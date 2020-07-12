<?php
namespace Grav\Plugin;

use Grav\Common\Data\ValidationException;
use Grav\Common\Debugger;
use Grav\Common\Filesystem\Folder;
use Grav\Common\Grav;
use Grav\Common\Page\Interfaces\PageInterface;
use Grav\Common\Page\Pages;
use Grav\Common\Page\Types;
use Grav\Common\Plugin;
use Grav\Common\Twig\Twig;
use Grav\Common\Utils;
use Grav\Common\Uri;
use Grav\Common\Yaml;
use Grav\Framework\Form\Interfaces\FormInterface;
use Grav\Framework\Route\Route;
use Grav\Plugin\Form\Form;
use Grav\Plugin\Form\Forms;
use Grav\Plugin\YellDigital\Fio\Account;
use Grav\Plugin\YellDigital\Fio\AccountsCollection;
use h4kuna\Fio\Utils\FioFactory;
use RocketTheme\Toolbox\File\JsonFile;
use RocketTheme\Toolbox\File\YamlFile;
use RocketTheme\Toolbox\File\File;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class FormPlugin
 * @package Grav\Plugin
 */
class FioPlugin extends Plugin
{
    const CACHE_PREFIX = 'ydfio';

    const CACHE_TIMEOUT = 36000;

    /** @var array */
    public $features = [
        'blueprints' => 1000
    ];

    protected $cache;

    protected $fio;

    protected $collection;


    /**
     * @return bool
     */
    public static function checkRequirements(): bool
    {
        return version_compare(GRAV_VERSION, '1.6', '>');
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        if (!static::checkRequirements()) {
            return [];
        }

        return [
            'onPluginsInitialized' => [
                ['autoload', 100000],
                ['setup', 99999],
                ['setupFio', 20],
                ['accountsLoad', 10],
            ],
            'onTwigInitialized' => ['onTwigInitialized', 0]
        ];
    }

    public function setupFio()
    {
        $accountCfg = $this->config->get('plugins.fio.credentials');
        $fioCfg = [];
        foreach ($accountCfg as $cfg) {
            $fioCfg[$cfg['id']] = [
                'account' => $cfg['account'],
                'token' => $cfg['token']
            ];
        }

        $this->fio = new FioFactory($fioCfg);
    }

    public function accountsLoad()
    {
        $accountCfg = $this->config->get('plugins.fio.credentials');

        foreach ($accountCfg as $cfg) {
            $cache = $this->cache->fetch($this->getCacheKey($cfg['id']));

            if ($cache) {
                if ($cache instanceof Account)
                    $this->collection->add($cache);
            } else {
                $fio = $this->fio->createFioRead($cfg['id']);

                $account = Account::create($fio, $cfg['id'], $cfg['title']);
                $this->cache->save($this->getCacheKey($cfg['id']), $account, self::CACHE_TIMEOUT);//var_dump($cac);die;
                $this->collection->add($account);
            }
        }
    }

    public function onTwigInitialized()
    {
        $this->grav['twig']->twig()->addFunction(
            new \Twig_SimpleFunction('FioAccount', [$this, 'getAccount'])
        );

        $this->grav['twig']->twig_vars += [
            'fio' => $this->collection
        ];
    }

    public function autoload()
    {
        return require_once __DIR__ . '/vendor/autoload.php';
    }

    public function setup()
    {
        $this->cache = $this->grav['cache'];
        $this->collection = new AccountsCollection();
//        echo'<pre>';
//        var_dump($this->grav['twig']);echo'</pre>';die;
    }

    public function getAccount($id)
    {
        return $this->collection->get($id);
    }

    protected function getCacheKey($id)
    {
        return self::CACHE_PREFIX . '_' . $id;
    }

}
