<?php

/**
 * Class indexController
 */
class indexController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        /** передаем значение авторизации */
        $this->smarty->assign('auth', $this->auth->users['auth']);

        return $this->smarty->fetch("index.tpl");
    }

    /**
     * @return array|false|string
     */
    public function setSender()
    {
        /** @var  $email */
        $email = @$this->request->post['email'];

        if ($email) {
            /** @var  $email */
            $emailCheck = $this->db->SQLquery("SELECT `id` FROM `blog_sender` WHERE `email` = '{$email}'", SQL_RESULT_ITEM);

            if ($emailCheck['id']) {
                return json_encode(['data' => ['message' => 'Пользователь с таким E-Mail адресом уже подписан.', 'code' => 0]]);
            }
            /** пишем в БД */
            $this->db->SQLquery("INSERT INTO `blog_sender` SET `email` = '{$email}', date = UNIX_TIMESTAMP()", SQL_RESULT_INSERTED);

            return json_encode(['data' => ['message' => 'Вы успешно подписались на рассылку!']]);

        } else return json_encode(['data' => ['message' => 'Введите Email!', 'code' => 0]]);
    }
}