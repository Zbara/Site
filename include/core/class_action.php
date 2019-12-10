<?php

/**
 * Class Action
 */
class Action {
    /** @var $registry, $folder, $controller, $method, $args */
    private $system;
    private $folder;
    //private $controller;
    private $method;
    private $args;
    public $controller;
    

    /**
     * Action constructor.
     * @param $system
     */
    public function __construct($system)
    {
        $this->system = $system;
    }

    /**
     * @param $action
     * @return bool
     */
    public function make($action) {
        $this->folder = null;
        $this->controller = null;
        $this->method = null;
        $this->args = null;

        $action = preg_replace("/[^\w\d\s\/]/", '', $action);
        $parts = explode('/', $action);
        $parts = array_filter($parts);

 
        foreach($parts as $item) {
            $fullpath = include_dir . '/controllers' . $this->folder . '/' . $item;
            if(is_dir($fullpath)) {
                $this->folder .= '/' . $item;
                array_shift($parts);
                continue;
            }
            elseif(is_file($fullpath . '.php')) {
                $this->controller = $item;
                array_shift($parts);
                break;
            } else break;
        }
        if(empty($this->folder)) {
            $this->folder = 'main';
        }
        if(empty($this->controller)) {
            $this->controller = 'index';
        }
        if($c = array_shift($parts)) {
            $this->method = $c;
        } else {
            $this->method = 'index';
        }
        if(isset($parts[0])) {
            $this->args = $parts;
        }

        $controllerFile = include_dir . '/controllers' . $this->folder . '/' . $this->controller . '.php';

        return (is_readable($controllerFile)) ? true : false;
    }

    /**
     * @param bool $commonEnable
     * @return array|mixed
     */
    public function go($commonEnable = false) {
        
        $controllerFile = include_dir . '/controllers' . $this->folder . '/' . $this->controller . '.php';
        $controllerClass = $this->controller . 'Controller';

          if(is_readable($controllerFile)) {
                require_once($controllerFile);

                $controller = new $controllerClass($this->system);

                if(is_callable(array($controller, $this->method))) {
                    $this->method = $this->method;
                } else {
                    $this->method = 'index';
                }
                if(empty($this->args)) {
                    return call_user_func(array($controller, $this->method));
                } else {
                    return call_user_func_array(array($controller, $this->method), $this->args);
                }
            }
            
           die(view('system/error_page.tpl', ['msg' => 'Запитувана сторінка не знайдена на нашому сервері.', 'title' => '404 Не знайдено']));
    }
}