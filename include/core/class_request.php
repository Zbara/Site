<?php

/**
 * Class Request
 */
class Request
{
    /**
     * @var array|string
     */
    public $get = [];
    public $post = [];
    public $cookie = [];
    public $files = [];
    public $server = [];

    /**
     * Request constructor.
     */

    public function __construct()
    {
        $_GET = $this->clean($_GET);
        $_POST = $this->clean($_POST);
        $_REQUEST = $this->clean($_REQUEST);
        $_COOKIE = $this->clean($_COOKIE);
        $_FILES = $this->clean($_FILES);
        $_SERVER = $this->clean($_SERVER);

        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
        $this->server = $_SERVER;

    }

    /**
     * @param $data
     * @return array|string
     */
    private function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);
                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_COMPAT);
        }
        return $data;
    }

}
