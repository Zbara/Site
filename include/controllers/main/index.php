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
        return view('/index.tpl', []);
    }
}