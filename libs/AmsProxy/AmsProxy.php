<?php
include 'curl.php';
include 'functions/__base__.php';

class AmsProxy {
    /**
     * @var string
     */
    public $sid;

    /**
     * @var string
     */
    public $pwd;

    /**
     * @var Request
     */
    public $curl;

    /**
     * @var array
     */
    public $baseUrl = 'http://ams.gxun.edu.cn/';

    /**
     * @param string $session
     */
    public function __construct($session=null) {
        $this->curl = new curl_request;
        $this->curl->setTimeout(8);
        if ($session) $this->setSession($session);
    }

    /**
     * @param string $captcha
     * @return string error message
     */
    public function login($sid, $pwd, $captcha) {
        $responseText = $this->POST(
            '_data/Index_LOGIN.aspx',
            array(
                'Sel_Type' => 'STU',
                'UserID'   => $sid,
                'PassWord' => $pwd,
                'cCode'    => $captcha,
            )
        );

        if (!strpos($responseText, '正在加载权限数据')) {
            preg_match(
                '/<font color="Red">(.*?)</', $responseText, $matches);
            return $matches[1];
        } else {
            $this->sid = $sid;
        }
    }

    /**
     * @param string $url
     * @param array $params url params
     * @param string $referer header referer
     * @return string
     */
    public function GET($url, $params=null, $referer=null) {
        return $this->request('get', $url, $params, null, $referer);
    }

    /**
     * @param string $url
     * @param array $data post data
     * @param array $params url params
     * @param string $referer header referer
     * @return string
     */
    public function POST($url, $data, $params=null, $referer=null) {
        return $this->request('post', $url, $params, $data, $referer);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $params url params
     * @param array $data post data
     * @param string $referer header referer
     * @return string
     */
    public function request(
        $method, $url, $params=null, $data=null, $referer=null) {

        if (!$referer)
            $referer = $this->baseUrl . $url;

        return iconv(
            'gb18030', 'utf-8//ignore',
            $this->curl->request(
                array(
                    'method'  => $method,
                    'url'     => $this->baseUrl . $url,
                    'params'  => $params,
                    'data'    => $data,
                    'headers' => array(
                        'Referer' => $referer,
                    ),
                )
            )->body
        );
    }

    /**
     * @return string
     */
    public function getSession() {
        if (!isset($this->curl->cookies['ASP.NET_SessionId']))
            $this->GET('');

        return $this->curl->cookies['ASP.NET_SessionId'];
    }

    /**
     * @return string
     */
    public function getCaptcha() {
        return $this->curl->request(
            array(
                'method' => 'get',
                'url'    => $this->baseUrl . 'sys/ValidateCode.aspx',
            )
        )->body;
    }

    /**
     * @param string $session
     */
    public function setSession($session) {
        $this->curl->setCookies(array('ASP.NET_SessionId' => $session));
    }

    /**
     * @param string $function
     * @param mixed $args
     */
    public function invoke($functionName, $args=null) {
        if (!class_exists($functionName, false))
            include 'functions/' . $functionName . '.php';

        $function = new $functionName($this, $args);
        return $function->run();
    }
}
