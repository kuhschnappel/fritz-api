<?php

namespace Kuhschnappel\FritzApi;

class Api
{

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
     * @param boolean $logging enable logging if needed
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
            'base_uri' => $this->$this->authData['host']
        ]);
    }

    public function getDeviceListInfos()
    {
//        $content = simplexml_load_string(file_get_contents('http://' . $host . '/login_sid.lua?version=2'));

        return $this->curlApiRoute('/webservices/homeautoswitch.lua?switchcmd=getdevicelistinfos');
//        return 'info'.print_r($this->authData);
    }

    private function curlApiRoute($route)
    {
        $sid = $this->getSession();
        return 'apiroute:'.$route.'sid:'.$sid;

    }

    private function getSession()
    {
        if (isset($this->authData['sid']))
            return $this->authData['sid'];

        $route = $this->authData['host'] . '/login_sid.lua?version=2';

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
            var_dump($response->getBody());
        } catch (ClientException $e) {
            var_dump('keinloginmöglich');
            $statusCode = $e->getPresponse()->getStatusCode();
            var_dump($statusCode);
        }
//        var_dump($headers);

        return "xyz";
    }

}