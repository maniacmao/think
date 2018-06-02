<?php
namespace app\sample2\controller;

use think\Controller;
use think\Db;
use app\sample2\model\User;

class Index extends Controller
{
    public function Index()
    {
        return 'Hello,World！---';
    }

    public function Test1()
    {
        $data = Db::name('data')->find();
        return json($data);
    }    
    public function Test2()
    {

    	// $user_model = new UserModel();    // 实例化用户模型
        // $data = $user_model->getAllUserDatas();     // 获取数据
        $data = User::all();
        return json($data);
    }    

    public function Replace()
    {
        $user = User::create([
            'id'  =>  4,
            'data' =>  'thinkphp@qq.com'
        ], ['id','data'], true);
        return json(User::all());
        /*
        $user = new User;
        $user->id = 4;
        $user->data = 'thinkphp@qq.com';
        // $user->save();
        $user->replace()->save();
               
        $user = new User;
        $user->save([
            'id'  =>  4,
            'data' =>  'thinkphp@qq.com'
        ]);  

        $user = new User([
            'id'  =>  4,
            'data' =>  'thinkphp@qq.com'
        ]);
        $user->save();

        $user = new User;
        $list = [
            ['id'=>'thinkphp','data'=>'thinkphp@qq.com'],
            ['id'=>'onethink','data'=>'onethink@qq.com']
        ];
        $user->saveAll($list);

        $user = User::create([
            'name'  =>  'thinkphp',
            'email' =>  'thinkphp@qq.com'
        ]);

        // V5.1.14+版本开始，支持replace操作，使用下面的方法：
        $user = User::create([
            'name'  =>  'thinkphp',
            'email' =>  'thinkphp@qq.com'
        ], ['name','email'], true);   

        // 最佳实践
        // 新增数据的最佳实践原则：使用create方法新增数据，使用saveAll批量新增数据。             
        */     
    }   

     public function Delete(){

        User::destroy([5, 6]);
        // 支持批量删除多个数据
        // User::destroy('1,2,3');
        // 或者
        // User::destroy([1,2,3]);
        return json(User::all());
     } 

     public function Query(){

        // 根据主键获取多个数据
        // $list = User::all('1,2,3');
        // 或者使用数组
        // $list = User::all([1,2,3]);
        $list = User::all();
        // 对数据集进行遍历操作
        foreach($list as $key=>$user){
            echo $user->data;
        }
        return "";
     }
}
