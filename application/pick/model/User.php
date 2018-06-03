<?php
namespace app\sample2\model;

use think\Model;

class User extends Model
{
	// 默认主键为id，如果你没有使用id作为主键名，需要在模型中设置属性：
    // protected $pk = 'id';
	
	// 设置当前模型对应的完整数据表名称
    protected $table = 'think_data';

	// 设置当前模型的数据库连接
    // protected $connection = 'db_config';	
	
    // 模型初始化
	// init必须是静态方法，并且只在第一次实例化的时候执行
    protected static function init()
    {
        //TODO:初始化内容
    }	
}