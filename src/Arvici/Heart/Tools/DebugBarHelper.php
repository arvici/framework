<?php
/**
 * DebugBarHelper.php
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2017 Tom Valk
 */


namespace Arvici\Heart\Tools;

use Arvici\Heart\Config\Configuration;
use Arvici\Heart\Database\Database;
use DebugBar\Bridge\DoctrineCollector;
use DebugBar\Bridge\MonologCollector;
use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DebugBar;
use DebugBar\StandardDebugBar;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\DBAL\Logging\LoggerChain;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DebugBarHelper
{
    private static $mimes = [
        'css' => 'text/css',
        'js' => 'text/javascript',
    ];

    /**
     * @var DebugBar
     */
    private $debugBar;

    /**
     * @var bool
     */
    private $captureAjax = false;

    /**
     * @var DebugStack
     */
    private $debugStack = null;

    /**
     * @var DebugBarHelper
     */
    private static $instance;

    /**
     * @return DebugBarHelper
     */
    public static function getInstance()
    {
        if (! self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct(DebugBar $bar = null)
    {
        if (! $bar) {
            $bar = new StandardDebugBar();
        }
        $this->debugStack = new DebugStack();
        $this->debugBar = $bar;

        // Initiate the collectors.
        try {
            $this->debugBar->addCollector(new DoctrineCollector($this->debugStack));
        } catch (\Exception $exception) {
            \Logger::error('Error with enabling doctrine debugbar collector: ' . $exception->getMessage(), (array) $exception);
        }

        $this->debugBar->addCollector(new MonologCollector(\Logger::getInstance()->getMonologInstance()));
        $this->debugBar->addCollector(new ConfigCollector(Configuration::all()));
    }

    /**
     * Inject the debug bar.
     *
     * @param Response $response
     */
    public function inject(Response &$response)
    {
        $renderer = $this->debugBar->getJavascriptRenderer('/__debug__/');

        if (stripos($response->headers->get('Content-Type'), 'text/html') === 0) {
            $content = $response->getContent();
            $content = self::injectHtml($content, $renderer->renderHead(), '</head>');
            $content = self::injectHtml($content, $renderer->render(), '</body>');
            $response->setContent($content);
        }
    }

    public function processDebugRequest(Request $request)
    {
        $renderer = $this->debugBar->getJavascriptRenderer('/__debug__/');

        $path = $request->getPathInfo();
        $baseUrl = $renderer->getBaseUrl();

        $file = $renderer->getBasePath() . DS . substr($path, strlen($baseUrl));
        if (file_exists($file)) {
            $response = new Response(file_get_contents($file));
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (isset(self::$mimes[$extension])) {
                $response->headers->set('Content-Type', self::$mimes[$extension]);
            }
            return $response;
        }
        return null;
    }

    public function captureAjax($capture = true)
    {
        $this->captureAjax = $capture;

        return $this;
    }


    /**
     * Inject html code before a tag.
     *
     * @param string $html
     * @param string $code
     * @param string $before
     *
     * @return string
     */
    private static function injectHtml($html, $code, $before)
    {
        $pos = strripos($html, $before);
        if ($pos === false) {
            return $html.$code;
        }
        return substr($html, 0, $pos).$code.substr($html, $pos);
    }

    /**
     * @return DebugStack
     */
    public function getDebugStack()
    {
        return $this->debugStack;
    }
}
