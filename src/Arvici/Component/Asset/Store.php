<?php
/**
 * Asset Store
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */

namespace Arvici\Component\Asset;


use Arvici\Exception\NotFoundException;
use Arvici\Heart\App\AppManager;
use Arvici\Heart\Config\Configuration;

class Store
{
    private static $templateStore = null;

    public static function template($appName = null)
    {
        if (self::$templateStore == null) {
            // Base url + path
            $url = Configuration::get('app.url') . '/assets/';
            $appPath = null;
            if ($appName === null) {
                $appPath = APPPATH;
            } else {
                foreach (AppManager::getInstance()->getApps() as $app) {
                    $clazz = new \ReflectionClass($app);
                    if ($clazz->getShortName() === $appName) {
                        $appPath = $app->getAppDirectory();
                    }
                }
            }
            if (! $appPath) {
                throw new NotFoundException('App not found: ' . $appName);
            }

            $path = $appPath . DS . 'assets' . DS;

            self::$templateStore = new self($url, $path);
        }
        return self::$templateStore;
    }


    private $baseUrl;
    private $basePath;

    public function __construct($baseUrl, $basePath)
    {
        $this->baseUrl = $baseUrl;
        $this->basePath = $basePath;
    }

    /**
     * Get absolute path for store assets.
     *
     * @param string $relativeFile
     *
     * @return string
     */
    public function getPath($relativeFile)
    {
        return $this->basePath . $relativeFile;
    }

    /**
     * Get full url of relative file.
     *
     * @param string $relativeFile
     *
     * @return string
     */
    public function getUrl($relativeFile)
    {
        if ($this->isAbsoluteUrl($relativeFile)) return $relativeFile;
        return $this->baseUrl . $relativeFile;
    }

    /**
     * @param string $url
     * @return bool
     */
    private function isAbsoluteUrl($url)
    {
        return strpos($url, '://') !== false || substr($url, 0, 2) === '//';
    }
}
