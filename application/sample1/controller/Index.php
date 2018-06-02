<?php
namespace app\sample1\controller;

use think\Controller;

class Index extends Controller
{
    public Function Test1(){
		$data = ['t1name' => $this->request->param('name'), 'status' => '1'];
        return json($data);
    }
    public Function Test2($name){
		$data = ['t2name' => $name, 'status' => '1'];
        return json($data);
    }
}