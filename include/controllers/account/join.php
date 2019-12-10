<?php
class joinController extends Controller
{
    /**
     * @return bool|mixed|string
     * @page страница входа
     */
    public function index()
    {
        /** no login */
        if ($this->auth->users['auth']) {
            return $this->response->redirect('/');
        }
        return view('/join.tpl', []);
    }
}