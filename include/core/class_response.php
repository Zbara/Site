<?php
/**
 * Class Response
 */
class Response {
    /** @var array  */
    private $headers = array();

    /**
     * @param $header
     */
    public function addHeader($header) {
        $this->headersarray[] = $header;
    }

    /**
     * @param $url
     */
    public function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    /**
     * @param $content
     */
    public function output($content) {
        if ($content) {
            if (!headers_sent()) {
                foreach($this->headers as $header) {
                    header($header, true);
                }
            }
            echo $content;
        }
    }
    /**
     * @param $params
     * @return mixed
     */
    private function echoJson($params)
    {
        /** @array */
        $array['response'] = $params;
        $array['response']['server'] = ['time' => unixTime()];
        return $array;
    }
    
    /**
     * @param $format
     * @param $data
     * @return mixed
     */
    public function echoApi($data)
    {
        header('Content-type: application/json; charset=utf-8');
        return $this->output(json_encode($this->echoJson($data), JSON_UNESCAPED_UNICODE));
    }
}
