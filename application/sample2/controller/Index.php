<?php
namespace app\sample2\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function Index()
    {
        return 'Hello,World！---';
    }

    public function Test()
    {
        $data = Db::name('data')->find();
        return json($data);
    }    
}
