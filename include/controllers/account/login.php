<?php

/**
 * Class loginController
 * класс для авторизации пользователя в системе
 * и прочая рабата
 */
class loginController extends Controller
{
    /**
     * @return bool|mixed|string
     */
    public function index()
    {
        /** no login */
        if ($this->auth->users['auth']) {
            return $this->response->redirect('/');
        }
        return view('/login.tpl', []);
    }


    /**
     * @return array|string
     */
    public function auth()
    {
        if ($this->auth->users['auth']) {
            return json_encode(['error' => 'ERROR AUTH'], JSON_UNESCAPED_UNICODE);
        }
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            /** @var  $email and @var $password */
            $login = @$this->request->post['login'];
            $password = @$this->request->post['password'];

            /** @var  $get */
            $get = $this->user([$login, $password]);
            
            /** проверка ответа */
            if ($get) {
                return json_encode(['success' => ['message' => 'Успiх', 'code' => 1]], JSON_UNESCAPED_UNICODE);
            } else return json_encode(['error' => ['message' => 'Не вiрний пароль.', 'code' => 1]], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @param $params
     * @return string|null
     */
    private function user($params){
        
        /** @var  $user смотрим в БД */
        $user = $this->db->SQLquery("SELECT `user_id`,`user_status`, `user_password` FROM `moyka_users` WHERE `user_login` = '{$params[0]}'", SQL_RESULT_ITEM);
        
        /** проверка пароля */
        if (password_verify($params[1], $user['user_password'])) {
            return $this->getSession([$user['user_id']]);
        } else return null;
    }

    /**
     * @param $param
     * @return string
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

        setcookie('remixsid', $remixsid, $cookie_lifetime, '/', $this->request->server['SERVER_NAME']);
        
        return true;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    /**
     * @return bool|mixed|string
     * @method получение данных с ВК
     */
    public function vk()
    {
        if ($this->auth->users['auth']) {
            return $this->response->redirect('/index.php?method=/account/main');
        }
        if ($this->request->server['REQUEST_METHOD'] == 'GET') {
            /** @var  $code код от авторизации вк */
            $code = $this->request->get['code'];

            $auth_token = file_get_contents('https://oauth.vk.com/access_token?client_id=' . $this->config['app_id'] . '&client_secret=' . $this->config['key'] . '&code=' . $code . '&redirect_uri=http://' . $this->config['server'] . '/index.php?method=/account/login/vk&response_type=code&v=5.50');
            $auth_token = json_decode($auth_token, true);

            if (!$auth_token['access_token']) {
                return $this->response->redirect('/index.php?method=/account/login&code=access_token');
            }
            $get = $this->SocID($auth_token);

            return $this->response->redirect('/index.php?method=/account/login&code=' . $get);

        } else return $this->response->redirect('/index.php?method=/account/login');
    }

    /**
     * @param $auth_token
     * @return bool|mixed|string
     */
    private function SocID($auth_token)
    {
        /** @var  $vk - получаем данные о юзвере ВК */
        $vk = file_get_contents('https://api.vk.com/method/users.get?&lang=ru&fields=photo_medium&access_token=' . $auth_token['access_token']);
        $api = json_decode($vk, true);

        /**  */
        if (!$api['response']) {
            return $this->response->redirect('/account/login?code=api_error');
        }
        /** @var  $api */
        $api = $api['response'][0];

        /** @var  $user смотрим в БД */
        $user = $this->db->SQLquery("SELECT `user_id`,`user_status`, `user_group` FROM `bot_users` WHERE `soc_id` = '{$api['uid']}'", SQL_RESULT_ITEM);

        /** сли есть впускаем если нет то  регаем*/
        if ($user['user_id']) {
            /** проверка на бан */
            if ($user['user_status'] == 0) {
                return '/account/login?code=banned';
            } elseif ($user['user_group'] == 1) {
                return $this->getSession([$user['user_id']]);
            } else return '/account/?code=ERROR';
        } else return $this->RegUSers([$api, $auth_token, unixTime()]);
    }

    /**
     * @param $params
     * @return string
     */
    private function RegUSers($params)
    {
        /** @var $db */
        $this->db->SQLquery("INSERT INTO `bot_users` SET soc_id = '{$params[0]['uid']}', user_email = '{$params[1]['email']}',  user_first_name = '{$params[0]['first_name']}', user_photo_medium = '{$params[0]['photo_medium']}', user_last_name = '{$params[0]['last_name']}', user_reg_date = '{$params[2]}', user_lastdate = '{$params[2]}', user_ip = '{$this->request->server['REMOTE_ADDR']}', user_ip_reg = '{$this->request->server['REMOTE_ADDR']}'", SQL_RESULT_INSERTED);

        /** @var $session */
        return '/account/reg';
    }
}