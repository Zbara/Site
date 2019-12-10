<?php
/**
 * Class logoutController
 * класс для выхода из системы
 */
class logoutController extends Controller
{
    /**
     * @return mixed
     * @page выход из системы
     */
    public function index()
    {
        
        if (!$this->auth->users['auth']) {
            return $this->response->redirect('/index.php?method=/account/login');
        }
        /** @var  $hash */
        $hash = $this->request->get['hash'];
        $session = $this->db->SQLquery("SELECT `session_hash` FROM `moyka_session_auth` WHERE session_hash = '{$hash}' AND session_status = '1'", SQL_RESULT_ITEM);
        if ($session['session_hash']) {
            setcookie('remixsid', null, null, '/', $this->request->server['SERVER_NAME']);
            $this->db->SQLquery("UPDATE `moyka_session_auth` SET `session_status` = '0' WHERE `session_hash` = '{$hash}'", SQL_RESULT_AFFECTED);
            return $this->response->redirect('/index.php?method=/account/login&code=logout');
        } else return $this->response->redirect('/index.php?method=/account/');
    }
}