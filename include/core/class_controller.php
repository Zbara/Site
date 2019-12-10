<?php

/**
 * Class Controller
 */
abstract class Controller
{
    /**
     * @var
     */
    private $registry;
    protected $data = [];

    /**
     * Controller constructor.
     * @param $registry
     */
    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->registry->$key;
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->registry->$key = $value;
    }

}
