<?php
/**
 * TestUtils
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace App;


use Arvici\Heart\Database\Connection;
use Arvici\Heart\Database\Database;
use Arvici\Heart\Http\Http;
use Arvici\Component\Router;

class TestUtils
{
    public static function clear()
    {
        self::clearRoutes();
        self::clearHttp();
        self::clearDatabase();
    }

    public static function clearRoutes()
    {
        Router::getInstance()->clearRoutes();
    }

    public static function clearDatabase()
    {
        Database::clear();
    }

    public static function resetDatabase($connection = 'default')
    {
        self::clearDatabase();

        /** @var Connection $connection */
        $connection = Database::connection($connection);
        $connection->truncate('posts');


        // Add test posts
        $posts = [
            ['id' => 1, 'title' => 'First Post', 'author' => 1, 'content' => 'Content', 'publishdate' => null],
            ['id' => 2, 'title' => 'Second Post', 'author' => 1, 'content' => 'Content', 'publishdate' => null],
            ['id' => 3, 'title' => 'Hello Post', 'author' => 1, 'content' => 'Content', 'publishdate' => null],
            ['id' => 4, 'title' => 'Mister Post', 'author' => 1, 'content' => 'Content', 'publishdate' => null],
            ['id' => 5, 'title' => 'Test Post', 'author' => 1, 'content' => 'Content', 'publishdate' => null],
            ['id' => 6, 'title' => 'Is Post', 'author' => 1, 'content' => 'Content', 'publishdate' => null],
            ['id' => 7, 'title' => 'Here Post', 'author' => 1, 'content' => 'Content', 'publishdate' => date('Y-m-d', time() - 5000)],
        ];
        foreach ($posts as $post) {
            $connection->insert('posts', $post);
        }


        self::clearDatabase();
    }

    public static function clearHttp()
    {
        Http::clearInstance();
    }

    public static function spoofUrl($url, $method, $get = array(), $post = array(), $cookies = array(), $server = array())
    {
        $_SERVER['SCRIPT_NAME'] = 'index.php';
        $_SERVER['REQUEST_URI'] = $url;
        $_SERVER['REQUEST_METHOD'] = strtoupper($method);

        $_GET = $get;
        $_POST = $post;
        $_COOKIE = $cookies;
        $_SERVER = array_merge($_SERVER, $server);
    }
}
