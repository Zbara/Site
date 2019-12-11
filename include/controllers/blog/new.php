<?php

/**
 * Class newController - создание новости
 */
class newController extends Controller
{
    /**
     * @return bool|mixed|string
     */
    public function index()
    {
        /**
         * если нет авторизация, тогда перекидаем
         */
        if (!$this->auth->users['auth']) {
            return $this->response->redirect('/');
        }

        $this->smarty->assign('login', $this->auth->users['user_info']['user_login']);

        return $this->smarty->fetch("blog/new.tpl");
    }

    public function addNews()
    {
        /**
         * если нет авторизация, тогда перекидаем
         */
        if (!$this->auth->users['auth']) {
            return json_encode(['data' => ['message' => 'Войдите!', 'code' => 0]]);
        }

        /** @var  $email */
        $title = @$this->request->post['title'];
        $text = @$this->request->post['text'];

        if ($title and $text) {

            /** пишем в БД */
            $this->db->SQLquery("INSERT INTO `blog_sender` SET `title` = '{$title}', `text` = '{$text}', date = UNIX_TIMESTAMP()", SQL_RESULT_INSERTED);

            return json_encode(['data' => ['message' => 'Новость создана! ']]);

        } else return json_encode(['data' => ['message' => 'Введите Email!', 'code' => 0]]);
    }
}