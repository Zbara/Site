<?php

/**
 * Class login
 */
class login extends Controller
{
    var $users = null;
    /**
     * login constructor.
     * @param $system
     */
    public function __construct($system )
    {
        /** @var  $time - unix время на сервер and @var  $time - remixs_id сессия */
        $time = unixTime();
        $remixsid = $system->request->cookie['remixsid'];
        
        if(isset($remixsid)) {
            /** @var  $session  информация о сессии */
            $session = $system->db->SQLquery("SELECT * FROM `moyka_session_auth` WHERE session_auth_key = '{$remixsid}' AND session_status = '1'", SQL_RESULT_ITEM);
            /** @var  $remixsid_server - сигнатура сесиии */
            $remixsid_server = md5($session['session_auth_user_id'] . '_' . $system->request->server['HTTP_USER_AGENT'] . '_' . $system->request->server['REMOTE_ADDR'] . '_' . $session['session_auth_time']);

            /** првоерка совпадения, клиента и серверной */
            if ($remixsid == $remixsid_server) {

                /** @var  $query - получаем информацию о своем прифиле */
                $query = $system->db->SQLquery("SELECT * FROM `moyka_users` WHERE user_id = '{$session['session_auth_user_id']}'", SQL_RESULT_ITEM);

                /** проверяем есть ли профиль,, еслит есть то пускаем */
                if ($query['user_id']) {
                    /** обновляем данные на лету */
                    $system->db->SQLquery("UPDATE `moyka_users` SET `user_lastdate` = '{$time}' WHERE `user_id` = '{$query['user_id']}'", SQL_RESULT_AFFECTED);
                    /** @var  users информация о клиенте для дальшего использования, и тут идет проверка для скрипта есть ли сессия */
                    $this->users = [
                        'session' => $session,
                        'user_info' => $query,
                        'auth' => true
                    ]; 
                } else
                    /** @var  users - отдаем ошибку, что нет авторизации */
                    $this->users = [
                        'auth' => null,
                        'remixsid' => setcookie('remixsid_d', null, null, '/', $this->request->server['SERVER_NAME'])
                    ];
            } else
                /** @var  users - отдаем ошибку, что нет авторизации */
                $this->users = [
                    'auth' => null,
                    'remixsid' => setcookie('remixsid_d', null, null, '/', $this->request->server['SERVER_NAME'])
                ];
        }
    }
}
