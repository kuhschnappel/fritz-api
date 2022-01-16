<?php

namespace Kuhschnappel\FritzApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Kuhschnappel\FritzApi\Utility\Helper;

class Api
{

    CONST ROUTE_LOGIN = '/login_sid.lua?version=2';
    CONST ROUTE_SWITCH = '/webservices/homeautoswitch.lua?switchcmd=';

    //TODO: move into device
    private static $onoff = [
        'off' => 0,
        'on' => 1,
        'toggle' => 2
    ];

    /**
     * @var array $authData authentification array
     * - user: (string) fritz box username with rights to use Smart Home
     * - password: (string) fritz box usernames password
     * - sid: (string) sid after successful login
     */
    protected $authData = [];

    /**
     * @var boolean $enableLogging enable logging into log array
     */
    public $enableLogging;

    /**
     * @var object $httpClient guzzle object for client
     */
    public $httpClient = null;

    /**
     * @param string $host fritz box hostname e.g. http://192.168.178.1, http://fritz.box
     * @param string $user fritz box username with rights to use Smart Home
     * @param string $password fritz box usernames password
     */
    public function __construct($user = false, $password = false, $host = 'http://192.168.178.1')
    {

        if (!$user || !$password) {
//            throw kein user bzw password
        }

        $this->authData['host'] = $host;
        $this->authData['user'] = $user;
        $this->authData['password'] = $password;

        $this->httpClient = new Client([
            'base_uri' => $this->authData['host']
        ]);
    }

    public function loadDevices() {
        echo "Geräte laden!!";
    }

    public function getDeviceListInfos()
    {
        $response = $this->curlApiRoute(API::ROUTE_SWITCH . 'getdevicelistinfos&sid='.$this->getSession());
				return simplexml_load_string($response);
    }

    //steckdosen
    public function getSwitchList()
    {
        $response = $this->curlApiRoute(API::ROUTE_SWITCH . 'getswitchlist&sid='.$this->getSession());
        return $response;
    }

    //TODO: move into device Model
    public function setDevicePower($ain, $switch) //on / off / toggle
    {
        $response = $this->curlApiRoute(API::ROUTE_SWITCH . 'setsimpleonoff&onoff=' . API::$onoff[$switch] . '&ain=' . $ain . '&sid=' . $this->getSession());
        return $response;
    }

    private function curlApiRoute($route)
    {
        $headers = [
            'User-Agent' => 'fritz-api',
            'Content-Type' => 'text/xml',
            'Origin' => $_SERVER['SERVER_NAME']
        ];
        try {
            $response = $this->httpClient->request(
                'GET',
                $route,
                [
                    'headers' => $headers
                ]
            );
            return $response->getBody();
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            var_dump($statusCode);
            throw new \Exception('Fritz!Box communication error');
        }
    }

    private function getSession()
    {
        if (isset($this->authData['sid']))
            return $this->authData['sid'];

        $response = $this->curlApiRoute(API::ROUTE_LOGIN);
        $responseXml = simplexml_load_string($response);

        if (!$responseXml->Challenge)
            throw new \Exception('No response challange string');

        $challange = explode('$', $responseXml->Challenge);

        $hash1 = Helper::hash_pbkdf2_sha256($this->authData['password'], $challange[2], $challange[1]);
        $hash2 = Helper::hash_pbkdf2_sha256(Helper::unhexlify($hash1), $challange[4], $challange[3]);

        $response = $challange[4] . '$' . $hash2;

        $route = API::ROUTE_LOGIN . '&username=' . $this->authData['user'] . '&response=' . $response;
        $response = $this->curlApiRoute($route);
        $responseXml = simplexml_load_string($response);

        if ($responseXml->SID == '0000000000000000')
            throw new \Exception('Invalid Login / No sid');

        $this->authData['sid'] = $responseXml->SID;

        return $this->authData['sid'];

    }



}