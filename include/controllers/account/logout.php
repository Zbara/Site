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
        /** удаляем куки */
        setcookie('remixsid', null, null, '/', $this->request->server['SERVER_NAME']);

        /** редирект на главную */
        return $this->response->redirect('/');
    }
}