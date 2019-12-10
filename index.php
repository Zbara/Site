<?php
/**
 * @file cpu
 * @page - запуск всех скриптов
 */
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
date_default_timezone_set('Europe/Moscow');
define('root', realpath(dirname(__FILE__)));
define('include_dir', dirname(__FILE__) . '/include');

/** конфиг **/
require_once(include_dir . '/inc/config.php');

/** функции **/
require_once(include_dir . '/functions/system.php');

/** Классы для работы **/
require_once(include_dir . '/core/class_controller.php');
require_once(include_dir . '/core/class_system.php');
require_once(include_dir . '/core/class_request.php');
require_once(include_dir . '/inc/mysql.php');
require_once(include_dir . '/core/class_response.php');
require_once(include_dir . '/core/class_action.php');
require_once(include_dir . '/smarty/Smarty.class.php');

/**
 * Запуск классов
 * */
$system = new System();
$system->config = $config;

$request = new Request();
$system->request = $request;

$db = new mysql($config['host'], $config['db_user'], $config['db_pass'], $config['db_name']);
$system->db = $db;

/** @var  $response */
$response = new Response();
$system->response = $response;

/** @var  $action */
$action = new Action($system);
$system->action = $action;


/** @var  tpl */
$system->smarty = new Smarty;
$system->smarty->debugging = true;
$system->smarty->caching = false;
$system->smarty->cache_lifetime = 120;

/** @var  $login  проверка на открытую сессию */
require_once(include_dir . '/login.php');
$login = new login($system);
$system->auth = $login;


/**
 * @return Если есть авторизация
 */
if (!empty($request->get['method'])) {
    $action->make($request->get['method']);
} else {
    $action->make('/main/index');
}
$response->output($action->go());