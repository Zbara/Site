<?php

/**
 * Class joinController - регистрация
 */
class joinController extends Controller
{
    /**
     * @return bool|mixed|string
     * @page страница входа
     */
    public function index()
    {
        /**
         * если есть авторизация, тогда перекидаем
         */
        if ($this->auth->users['auth']) {
            return $this->response->redirect('/');
        }
        return $this->smarty->fetch("join.tpl");
    }

    public function reg()
    {
        /** @var  $login */
        $login = @$this->request->post['login'];
        $email = @$this->request->post['email'];
        $password = @$this->request->post['password'];

        /** @var  $validate */
        $validate = $this->validate([$login, $email]);

        /** ошибки */
        if ($validate) {
            return $validate;
        }

        /** @var  $password */
        $password = password_hash($password, PASSWORD_DEFAULT);

        /** @var  $user_id */
        $user = $this->db->SQLquery("INSERT INTO `moyka_users` SET `user_login` = '{$login}', `user_email` = '{$email}', `user_password` = '{$password}', user_reg = UNIX_TIMESTAMP(), user_lastdate = UNIX_TIMESTAMP(), user_ip = '{$this->request->server['REMOTE_ADDR']}'", SQL_RESULT_INSERTED);

        return $this->getSession([$user]);
    }

    /**
     * @param $items
     * @return false|string
     */
    private function validate($items)
    {
        /** @var  $email */
        $email = $this->db->SQLquery("SELECT `user_id` FROM `moyka_users` WHERE `user_email` = '{$items[1]}'", SQL_RESULT_ITEM);

        if ($email['user_id'])
            return json_encode(['data' => [], 'error' => ['message' => 'Пользователь с таким E-Mail адресом уже зарегистрирован.', 'code' => 0]]);

        /** проверка на логин */
        if (!preg_match("/^([A-Za-z0-9_.]+)$/", $items[0]))
            return json_encode(['data' => [], 'error' => ['message' => 'Только английские символы', 'code' => 0]]);

        /** @var  $email */
        $login = $this->db->SQLquery("SELECT `user_id` FROM `moyka_users` WHERE `user_login` = '{$items[0]}'", SQL_RESULT_ITEM);

        if ($login['user_id'])
            return json_encode(['data' => [], 'error' => ['message' => 'Пользователь с таким логином уже зарегистрирован.', 'code' => 0]]);
    }

    /**
     * @param $param
     * @return false|string
     */
    private function getSession($param)
    {
        /** @var  $auth_time */
        $auth_time = (int)unixTime();

        /** @var  $remixsid */
        $remixsid = md5($param[0] . '_' . $this->request->server['HTTP_USER_AGENT'] . '_' . $this->request->server['REMOTE_ADDR'] . '_' . $auth_time);

        /** @var  $hash */
        $hash = substr(md5($remixsid), 0, 16);

        /** @var пишем в БД */
        $this->db->SQLquery("INSERT INTO `moyka_session_auth` (session_auth_key, session_auth_user_id, session_auth_ua, session_auth_ip, session_status, session_auth_time, session_hash) VALUES('{$remixsid}', '{$param[0]}', '{$this->request->server['HTTP_USER_AGENT']}', '{$this->request->server[REMOTE_ADDR]}', '1', '{$auth_time}', '{$hash}')", SQL_RESULT_INSERTED);

        /** @var обновляем данные о юзвере */
        $this->db->SQLquery("UPDATE `moyka_users` SET `user_lastdate` = '{$auth_time}', `user_ip` = '{$this->request->server['REMOTE_ADDR']}' WHERE `user_id` = '{$param[0]}'", SQL_RESULT_AFFECTED);

        /** @var  $cookie_lifetime */
        $cookie_lifetime = $auth_time + (60 * 60 * 24 * 30 * 6);

        /** открытие сессии */
        setcookie('remixsid', $remixsid, $cookie_lifetime, '/', $this->request->server['SERVER_NAME']);

        return json_encode(['data' => ['message' => 'Вы успешно зарегистрированные!']]);
    }
}