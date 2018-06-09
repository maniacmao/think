<?php
namespace app\pick\controller;

header('Access-Control-Allow-Origin:*');

use think\Controller;
use think\Db;

use app\pick\model\User;
use app\pick\model\UserBatch;
use app\pick\model\UserToken;
use app\pick\model\Batch;
use app\pick\model\Building;
use app\pick\model\Favorite;
use app\pick\model\Order;

class Index extends Controller
{
    public function Index()
    {
        return 'Hello,World！---';
    }
 
	// * 登录验证 CheckLogin
	//     - 请求: name/pwd
    public Function CheckLoginTest($name, $pwd){

		$data = ['err' => 0];

		// var_dump(Building::where("id",1)->find());
		$user = User::where('name', $name)->find();
		if(!$user){
			$data["err"] = 1;
			return json($data);
		}

		if(md5($pwd) != $user->pwd){
			$data["err"] = 1;
			return json($data);
		}

		$data["token"] = "5b18ac6ebbba1";

        UserToken::create([
            'user_id'  =>  $user->id,
            'token' =>  $data["token"]
        ], ['user_id','token'], true);

		$favorite_building_list = Favorite::where("user_id", $user->id)->select();
		$data["favorite_building_list"] = $favorite_building_list;
		
		$order_building_list = Order::where("user_id", $user->id)->select();
		$data["order_building_list"] = $order_building_list;

		$user_batch_list = UserBatch::where("user_id", $user->id)->select();
		$data["user_batch_list"] = $user_batch_list;

		$batch_id_list = [];
		foreach($user_batch_list as $key=>$user_batch){
		    $batch_id_list[] = $user_batch->batch_id;
		}

		$batch_list = Batch::where("id", "in", $batch_id_list)->select();
		$data["batch_list"] = $batch_list;		

		$building_list = Building::where("batch_id", "in", $batch_id_list)->select();
		$data["building_list"] = $building_list;

        return json($data);
    }

	// * 登录验证 CheckLogin
	//     - 请求: name/pwd
    public Function CheckLogin($name, $pwd){

		$data = ['err' => 0];

		// var_dump(Building::where("id",1)->find());
		$user = User::where('name', $name)->find();
		if(!$user){
			$data["err"] = 1;
			return json($data);
		}


		if($pwd != $user->pwd){
			$data["err"] = 1;
			return json($data);
		}

		$data["token"] = uniqid();

        UserToken::create([
            'user_id'  =>  $user->id,
            'token' =>  $data["token"]
        ], ['user_id','token'], true);

		$favorite_building_list = Favorite::where("user_id", $user->id)->select();
		$data["favorite_building_list"] = $favorite_building_list;
		
		$order_building_list = Order::where("user_id", $user->id)->select();
		$data["order_building_list"] = $order_building_list;

		$user_batch_list = UserBatch::where("user_id", $user->id)->select();
		$data["user_batch_list"] = $user_batch_list;

		$batch_id_list = [];
		foreach($user_batch_list as $key=>$user_batch){
		    $batch_id_list[] = $user_batch->batch_id;
		}

		$batch_list = Batch::where("id", "in", $batch_id_list)->select();
		$data["batch_list"] = $batch_list;

		$building_list = Building::where("batch_id", "in", $batch_id_list)->select();
		$data["building_list"] = $building_list;

        return json($data);
    }

    private Function _GetUserID($token){

    	$user_tokens = UserToken::where('token', $token)->select();
		if(sizeof($user_tokens)==0){
			return 0;
		}    	
		return $user_tokens[0]->user_id;
    }

	// * 用户数据 GetUser
	//     - 请求: token
	//     - 响应
	//         + err
	//         + user_data
	//             * batch_list
	//                 - 批次编号 id, 名字 name，特权开选时间 preference_time，正式开选时间 begin_time，结束时间 end_time, 是否特权 is_preference
    public Function GetUser($token){

		$data = ['err' => 0];
		$user_id = $this->_GetUserID($token);
		if($user_id == 0) {
			$data["err"] = 1;
			return json($data);
		}

		$favorite_building_list = Favorite::where("user_id", $user_id)->select();
		$data["favorite_building_list"] = $favorite_building_list;
		
		$order_building_list = Order::where("user_id", $user_id)->select();
		$data["order_building_list"] = $order_building_list;

		$user_batch_list = UserBatch::where("user_id", $user_id)->select();
		$data["user_batch_list"] = $user_batch_list;

		$batch_id_list = [];
		foreach($user_batch_list as $key=>$user_batch){
		    $batch_id_list[] = $user_batch->batch_id;
		}

		$batch_list = Batch::where("id", "in", $batch_id_list)->select();
		$data["batch_list"] = $batch_list;

		$building_list = Building::where("batch_id", "in", $batch_id_list)->select();
		$data["building_list"] = $building_list;
        return json($data);
    }

	// * 房子收藏 AddFavorite
	//     - 请求: token / building_id
	//     - 响应
	//         + err
	//         + user_data
	//             * favorite_building_list
	//                 - 房子编号 id，批次编号 batch_id，名字 name，是否车位 is_parking，面积 area，几房几厅几卫描述 desc，单价 unit_price，总价 total_price，订购者编号 book_user_id，收藏计数 favorite_count
    public Function AddFavorite($token, $building_id){

		$data = ['err' => 0];
		$user_id = $this->_GetUserID($token);
		if($user_id == 0) {
			$data["err"] = 1;
			return json($data);
		}

		$building = Building::where("id", $building_id)->find();
		if(!$building){
			$data["err"] = 1;
			return json($data);
		}

		$favorite = Favorite::where("user_id", $user_id)->where("building_id", $building_id)->find();
		if($favorite){
			$data["err"] = 1;
			return json($data);
		}		

        Favorite::create([
            'user_id'  =>  $user_id,
            'building_id' =>  $building_id
        ], ['user_id','building_id'], true);

		$building->favorite = ['inc', 1];
		$building->save();

		$favorite_building_list = Favorite::where("user_id", $user_id)->select();
		$data["favorite_building_list"] = $favorite_building_list;   

		$user_batch_list = UserBatch::where("user_id", $user_id)->select();
		$data["user_batch_list"] = $user_batch_list;

		$batch_id_list = [];
		foreach($user_batch_list as $key=>$user_batch){
		    $batch_id_list[] = $user_batch->batch_id;
		}

		$batch_list = Batch::where("id", "in", $batch_id_list)->select();
		$data["batch_list"] = $batch_list;	  

		$building_list = Building::where("batch_id", "in", $batch_id_list)->select();
		$data["building_list"] = $building_list;  
        return json($data);
    }

    public Function RemoveFavorite($token, $building_id){

		$data = ['err' => 0];
		$user_id = $this->_GetUserID($token);
		if($user_id == 0) {
			$data["err"] = 1;
			return json($data);
		}
		$building = Building::where("id", $building_id)->find();
		if(!$building){
			$data["err"] = 2;
			return json($data);
		}

		$favorite = Favorite::where("user_id", $user_id)->where("building_id", $building_id)->find();
		if(!$favorite){
			$data["err"] = 3;
			return json($data);
		}	

		Favorite::where("user_id", $user_id)->where("building_id", $building_id)->delete();

		$building->favorite = ['dec', 1];
		$building->save();

		$favorite_building_list = Favorite::where("user_id", $user_id)->select();
		$data["favorite_building_list"] = $favorite_building_list;

		$user_batch_list = UserBatch::where("user_id", $user_id)->select();
		$data["user_batch_list"] = $user_batch_list;

		$batch_id_list = [];
		foreach($user_batch_list as $key=>$user_batch){
		    $batch_id_list[] = $user_batch->batch_id;
		}

		$batch_list = Batch::where("id", "in", $batch_id_list)->select();
		$data["batch_list"] = $batch_list;

		$building_list = Building::where("batch_id", "in", $batch_id_list)->select();
		$data["building_list"] = $building_list;
        return json($data);
    }

	// * 房子下单 BookBuilding
	//     - 请求: token / building_id
	//     - 响应
	//         + err
	//         + user_data
	//             * book_building_list
	//                 - 房子编号 id，批次编号 batch_id，名字 name，是否车位 is_parking，面积 area，几房几厅几卫描述 desc，单价 unit_price，总价 total_price，订购者编号 book_user_id，收藏计数 favorite_count
    public Function AddOrder($token, $building_id){
    	
		$data = ['err' => 0];
		$user_id = $this->_GetUserID($token);
		if($user_id == 0) {
			$data["err"] = 1;
			return json($data);
		}

		// 看看房子存在不
		$building = Building::where("id", $building_id)->find();
		if(!$building){
			$data["err"] = 2;
			return json($data);
		}

		// 每期只能一个订单
		$order = Order::where("user_id", $user_id)->where("batch_id", $building->batch_id)->find();
		if($order){
			$data["err"] = 3;
			return json($data);
		}	

		$user_batch = UserBatch::where("user_id", $user_id)->where("batch_id", $building->batch_id)->find();	
		if(!$user_batch){
			$data["err"] = 4;
			return json($data);
		}	

		$batch = Batch::where("id", $building->batch_id)->find();	
		if(!$batch){
			$data["err"] = 5;
			return json($data);
		}	

		// 时间判定
		$begin_time = $batch->begin_time;
		$end_time = $batch->end_time;
		if($user_batch->is_preference==1){
			$begin_time = $batch->preference_time;
		}

		$begin_time = strtotime($begin_time);
		$end_time = strtotime($end_time);
		$now = time();
		if($now < $begin_time){
			$data["err"] = 6;
			return json($data);
		}

		if($now > $end_time){
			$data["err"] = 7;
			return json($data);
		}

		// 添加订单
        Order::create([
            'user_id'  =>  $user_id,
            'building_id' =>  $building_id,
            'batch_id' =>  $building->batch_id
        ], ['user_id','building_id', 'batch_id'], true);

        $building->user_id = $user_id;
        $building->save();

 		$order_building_list = Order::where("user_id", $user_id)->select();
		$data["order_building_list"] = $order_building_list;   

		$user_batch_list = UserBatch::where("user_id", $user_id)->select();
		$data["user_batch_list"] = $user_batch_list;

		$batch_id_list = [];
		foreach($user_batch_list as $key=>$user_batch){
		    $batch_id_list[] = $user_batch->batch_id;
		}

		$batch_list = Batch::where("id", "in", $batch_id_list)->select();
		$data["batch_list"] = $batch_list;    

		$building_list = Building::where("batch_id", "in", $batch_id_list)->select();
		$data["building_list"] = $building_list;
        return json($data);
    }

	// * 收藏数据 GetFavorite
	//     - 请求: token
    public Function GetFavorite($token){
    	
		$data = ['err' => 0];
		$user_id = $this->_GetUserID($token);
		if($user_id == 0) {
			$data["err"] = 1;
			return json($data);
		}

		$favorite_building_list = Favorite::where("user_id", $user_id)->select();
		$data["favorite_building_list"] = $favorite_building_list;

		$user_batch_list = UserBatch::where("user_id", $user_id)->select();
		$data["user_batch_list"] = $user_batch_list;

		$batch_id_list = [];
		foreach($user_batch_list as $key=>$user_batch){
		    $batch_id_list[] = $user_batch->batch_id;
		}

		$batch_list = Batch::where("id", "in", $batch_id_list)->select();
		$data["batch_list"] = $batch_list;		

		$building_list = Building::where("batch_id", "in", $batch_id_list)->select();
		$data["building_list"] = $building_list;
        return json($data);
    }

	// * 下单数据 GetOrder
	//     - 请求: token
    public Function GetOrder($token){
    	
		$data = ['err' => 0];
		$user_id = $this->_GetUserID($token);
		if($user_id == 0) {
			$data["err"] = 1;
			return json($data);
		}

		$order_building_list = Order::where("user_id", $user_id)->select();
		$data["order_building_list"] = $order_building_list;

		$user_batch_list = UserBatch::where("user_id", $user_id)->select();
		$data["user_batch_list"] = $user_batch_list;

		$batch_id_list = [];
		foreach($user_batch_list as $key=>$user_batch){
		    $batch_id_list[] = $user_batch->batch_id;
		}

		$batch_list = Batch::where("id", "in", $batch_id_list)->select();
		$data["batch_list"] = $batch_list;		

		$building_list = Building::where("batch_id", "in", $batch_id_list)->select();
		$data["building_list"] = $building_list;
        return json($data);
    }
}
