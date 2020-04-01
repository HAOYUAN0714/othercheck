<?php

namespace App\Providers\Curl;

/**
 * Class Curl
 * 使用 \App::make('名稱') 可以取得到連線物件
 * @package Package\Curl
 */
class Curl
{
    private $curlStart;
    private $ip;
    private $host;
    private $port;
    private $api_key;
    private $sensitive_data;
    private $header = [];
    private $source;
    private $log;
    private $secure;
    private $resolve;
    private $path;
    private $proxy;

    /**
     * 宣告 curl ip, host, port, key, http secure
     *
     * @param            $source
     * @param            $ip
     * @param            $host
     * @param int $port
     * @param null $key
     * @param bool|false $secure
     *
     * @throws \Exception
     */
    public function init($source, $ip, $host, $port = 80, $key = null, $secure = false, $resolve = null, $proxy = null)
    {
        $this->curlStart = microtime(true);

        if (!isset($ip)) {
            throw new \Exception('連線的IP沒有被設定，請檢查環境設定檔');
        } else {
            $this->source = $source;
            $this->ip = $ip;
            $this->host = $host;
            $this->port = $port;
            $this->api_key = $key;
            $this->log = null;
            $this->secure = $secure;
            $this->resolve = (is_null($resolve)) ? [] : [$resolve];
            $this->proxy = (is_null($proxy)) ? null : $proxy;
        }
    }

    /**
     * @param $data
     */
    public function setSensitiveData($data)
    {
        $this->sensitive_data = http_build_query($data);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function setApiKey($key)
    {
        $this->api_key = $key;
        return true;
    }

    /**
     * @param array $header
     *
     * @return bool
     */
    public function setHeader($header = [])
    {
        foreach ($header as $key => $value) {
            $head = "$key: $value";
            array_push($this->header, $head);
        }
        return true;
    }

    /**
     * @param string $lang
     *
     * @return array
     */
    public function getHeader($lang = 'zh-tw')
    {
        $header = $this->header;

        if (!is_null($this->host)) {
            $header[] = 'Host: ' . $this->host;
            $header[] = 'Sensitive-Data: ' . $this->sensitive_data;
        }
        $header[] = 'Api-Key: ' . $this->api_key;
        $header[] = 'Accept-Language: ' . $lang;
        $header[] = 'Content-Type: application/json';   // 針對IMSport端口採用json格式

        return $header;
    }

    /**
     * GET
     *
     * @param null $path
     * @param array $input
     * @param string $lang
     *
     * @return mixed
     */
    public function get($path = null, $input = [], $lang = 'zh-tw')
    {
        $ch = curl_init();

        if (!empty($input)) {
            $inputQuery = '?' . http_build_query($input);
        } else {
            $inputQuery = '';
        }

        $options = [
            CURLOPT_URL => $this->getDomainUrl() . $path . $inputQuery,
            CURLOPT_HTTPHEADER => $this->getHeader($lang),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RESOLVE => $this->resolve,
            CURLOPT_PROXY => $this->proxy,
        ];

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        # 記錄Log
        $this->logReturn('get', $options, $response);

        return $response;
    }

    /**
     * POST
     *
     * @param null $path
     * @param array $input
     * @param string $lang
     *
     * @return mixed
     */
    public function post($path = null, $input = [], $lang = 'zh-tw')
    {
        $ch = curl_init();

        // 針對Post採用json格式 (IMSport端口)
        // $this->setHeader(['Content-Type' => 'application/json']);

        $options = [
            CURLOPT_URL => $this->getDomainUrl() . $path,
            CURLOPT_HTTPHEADER => $this->getHeader($lang),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE => 0,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $input,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RESOLVE => $this->resolve,
            CURLOPT_PROXY => $this->proxy,
        ];

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        curl_close($ch);
        $curlEnd = microtime(true);
        $_ENV['CURL_EXECUTE'] = $curlEnd - $this->curlStart;
        $_ENV['CURL_COUNT'] = isset($_ENV['CURL_COUNT']) ? ++$_ENV['CURL_COUNT'] : 1;

        # 記錄Log
        $this->logReturn('post', $options, $response);
        $_ENV['LOG_EXECUTE'] = microtime(true) - $curlEnd;
        return $response;
    }

    /**
     * put
     *
     * @param null $path
     * @param array $input
     * @param string $lang
     *
     * @return mixed
     */
    public function put($path = null, $input = [], $lang = 'zh-tw')
    {
        $ch = curl_init();

        $options = [
            CURLOPT_URL => $this->getDomainUrl() . $path,
            CURLOPT_HTTPHEADER => $this->getHeader($lang),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_HEADER => true,
            CURLOPT_POSTFIELDS => http_build_query($input),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RESOLVE => $this->resolve,
            CURLOPT_PROXY => $this->proxy,
        ];

        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $response = substr($result, strpos($result, '{"'));
        curl_close($ch);

        # 記錄Log
        $this->logReturn('put', $options, $response);

        return $response;
    }

    /**
     * @param null $path
     * @param array $input
     * @param string $lang
     *
     * @return bool|mixed
     * @throws \Exception
     */
    public function delete($path = null, $input = [], $lang = 'zh-tw')
    {
        $ch = curl_init();

        $options = [
            CURLOPT_URL => $this->getDomainUrl() . $path,
            CURLOPT_HTTPHEADER => $this->getHeader($lang),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_POSTFIELDS => http_build_query($input),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RESOLVE => $this->resolve,
            CURLOPT_PROXY => $this->proxy,
        ];

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        # 記錄Log
        $this->logReturn('delete', $options, $response);

        return $response;
    }

    /**
     * 判斷是否要使用https
     * @return string
     */
    private function getDomainUrl()
    {
        if ($this->secure) {
            // 開啟https
            $domainUrl = 'https://' . $this->ip . ':' . $this->port;
        } else {
            $domainUrl = 'http://' . $this->ip . ':' . $this->port;
        }

        return $domainUrl;
    }

    private function logReturn($requestType, $options, $response)
    {
        try {
            # 紀錄Request參數到日誌
            $logParam['parameters'] = [
                'url' => isset($options[CURLOPT_URL]) ? $options[CURLOPT_URL] : '',
                'httpheader' => isset($options[CURLOPT_HTTPHEADER]) ? $options[CURLOPT_HTTPHEADER] : '',
                'returntransfer' => isset($options[CURLOPT_POSTFIELDS]) ? boolval($options[CURLOPT_RETURNTRANSFER]) : '',
                'customrequest' => isset($options[CURLOPT_CUSTOMREQUEST]) ? $options[CURLOPT_CUSTOMREQUEST] : '',
                'postfields' => isset($options[CURLOPT_POSTFIELDS]) ? $options[CURLOPT_POSTFIELDS] : '',
                'verifyhost' => isset($options[CURLOPT_SSL_VERIFYHOST]) ? boolval($options[CURLOPT_SSL_VERIFYHOST]) : '',
                'verifypeer' => isset($options[CURLOPT_SSL_VERIFYPEER]) ? boolval($options[CURLOPT_SSL_VERIFYPEER]) : '',
                'resolve' => isset($options[CURLOPT_RESOLVE]) ? $options[CURLOPT_RESOLVE] : '',
                'proxy' => isset($options[CURLOPT_PROXY]) ? $options[CURLOPT_PROXY] : '',
            ];

            # 紀錄Response參數到日誌
            $logParam['response'] = $response;

            # 記錄 Log
            \Log::channel('curllogfor' . $this->source)->info(json_encode($logParam));
        } catch (\Exception $exception) {
            // Do Nothing
        }
    }
}
