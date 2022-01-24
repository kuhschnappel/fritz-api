<?php

namespace Kuhschnappel\FritzApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Kuhschnappel\FritzApi\Utility\Helper;
use \Monolog\Logger;
use \Monolog\Handler\RotatingFileHandler;

class Api
{

    const ROUTE_LOGIN = '/login_sid.lua?version=2';
    const ROUTE_SWITCH = '/webservices/homeautoswitch.lua?switchcmd=';


    /**
     * @var object $logger monolog object for logs
     */
    public static $logger = null;


    /**
     * @var array $authData authentification array
     * - user: (string) fritz box username with rights to use Smart Home
     * - password: (string) fritz box usernames password
     * - sid: (string) sid after successful login
     */
    protected static $authData = [];

    /**
     * @var boolean $enableLogging enable logging into log array
     */
    public $enableLogging;

    /**
     * @var object $httpClient guzzle object for client
     */
    public static $httpClient = null;

    /**
     * @param string $host fritz box hostname e.g. http://192.168.178.1, http://fritz.box
     * @param string $user fritz box username with rights to use Smart Home
     * @param string $password fritz box usernames password
     */
    public static function init($user = false, $password = false, $host = 'http://192.168.178.1')
    {


        if (!$user || !$password) {
//            throw kein user bzw password
        }

        self::$authData['host'] = $host;
        self::$authData['user'] = $user;
        self::$authData['password'] = $password;

        self::$httpClient = new Client([
            'base_uri' => self::$authData['host']
        ]);

        self::initLogging();

    }

    public static function loadDevices()
    {
        echo "Geräte laden!!";
    }

    public static function switchCmd($cmd, $params = null)
    {
        $paramsUrl = '';
        if ($params)
            foreach ($params as $var => $value)
                $paramsUrl .= '&' . $var . '=' . $value;

        $route = API::ROUTE_SWITCH . $cmd . '&sid=' . self::getSession() . $paramsUrl;

        $response = self::curlApiRoute($route);
        return trim($response);
    }

    private static function curlApiRoute($route)
    {
        $headers = [
            'User-Agent' => 'fritz-api',
            'Content-Type' => 'text/xml',
            'Origin' => $_SERVER['SERVER_NAME']
        ];
        try {
            $response = self::$httpClient->request(
                'GET',
                $route,
                [
                    'headers' => $headers
                ]
            );
            self::$logger->debug('FritzBoxRequest', [
                // 'Status' => $response->getStatusCode(),
                'Route' => $route,
                'Response' => serialize($response->getBody())
            ]);
            return $response->getBody();
        } catch (ClientException $e) {
            self::$logger->error('FritzBoxRequest', [
                'Status' => $e->getResponse()->getStatusCode(),
                'Route' => $route,
                'Response' => ($response && method_exists('response', 'getBody')) ? json_encode($response->getBody()) : null
            ]);
            // throw new \Exception('Fritz!Box communication error');
        }
    }

    private static function getSession()
    {
        if (isset(self::$authData['sid']))
            return self::$authData['sid'];

        $response = self::curlApiRoute(API::ROUTE_LOGIN);
        $responseXml = simplexml_load_string($response);

        if (!$responseXml->Challenge)
            throw new \Exception('No response challange string');

        $challange = explode('$', $responseXml->Challenge);

        $hash1 = Helper::hash_pbkdf2_sha256(self::$authData['password'], $challange[2], $challange[1]);
        $hash2 = Helper::hash_pbkdf2_sha256(Helper::unhexlify($hash1), $challange[4], $challange[3]);

        $response = $challange[4] . '$' . $hash2;

        $route = API::ROUTE_LOGIN . '&username=' . self::$authData['user'] . '&response=' . $response;
        $response = self::curlApiRoute($route);
        $responseXml = simplexml_load_string($response);

        if ($responseXml->SID == '0000000000000000')
            throw new \Exception('Invalid Login / No sid');

        self::$authData['sid'] = $responseXml->SID;

        return self::$authData['sid'];

    }


    private static function initLogging()
    {

        $logDir = defined('FRITZ_API_LOG_DIR') ? FRITZ_API_LOG_DIR : $_SERVER['DOCUMENT_ROOT'] . '/logs';

        if (!is_dir($logDir))
            mkdir($logDir, 0777, true);

        $htaccess = $logDir . '/.htaccess';
        if (!is_file($htaccess)) {
            $content = 'Deny from all';
            file_put_contents($htaccess, $content);
        }

        // $loglevel = defined('FRITZ_API_LOG_LEVEL') ? Logger::FRITZ_API_LOG_LEVEL : Logger::ERROR;
        $loglevel = defined('FRITZ_API_LOG_LEVEL') ? Logger::FRITZ_API_LOG_LEVEL : Logger::DEBUG;

        self::$logger = new Logger('fritzApi');
        self::$logger->pushHandler(new RotatingFileHandler($logDir . '/fritz-api-connector.log', 30, $loglevel));


        // 		if (defined('VINOU_DEBUG') && VINOU_DEBUG)
        // 			$loglevel = Logger::DEBUG;


        // //$loglevel = Logger::DEBUG;

        // $this->logger = new Logger('api');
        // 		$this->logger->pushHandler(new RotatingFileHandler($logDir.'api-connector.log', 30, $loglevel));

    }

}