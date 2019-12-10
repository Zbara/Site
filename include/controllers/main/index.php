<?php
/**
 * Class indexController
 */
class indexController extends Controller
{
    /**
     * @return mixed
     */
    public function index(){
        /** передаем значение авторизации */
        $this->smarty->assign('auth', $this->auth->users['auth']);

        return $this->smarty->fetch("index.tpl");
    }
}