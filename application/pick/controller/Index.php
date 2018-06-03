<?php
namespace app\pick\controller;



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
	//     - 响应
	//         + err
	//         + token
    public Function CheckLogin($uname, $upwd){
		$data = ['err' => 0];

		$user = User::where('name', $uname)->select();
		if(sizeof($user)==0){
			$data->err = 1;
			return json($data);
		}

		if($upwd != $user[0]->pwd){
			$data["err"] = 1;
			return json($data);
		}

		$data["token"] = uniqid();

        UserToken::create([
            'user_id'  =>  4,
            'token' =>  $data["token"]
        ], ['user_id','token'], true);
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

		$user_batch_list = UserBatch::where("user_id", $user_id)->select();

		$batch_id_list = [];
		foreach($user_batch_list as $key=>$user_batch){
		    $batch_id_list[] = $user_batch->batch_id;
		}

		$data["user_batch_list"] = $user_batch_list;

		$batch_list = Batch::where("id", "in", $batch_id_list)->select();
		$data["batch_list"] = $batch_list;
        return json($data);
    }

	// * 房子数据 GetBuilding
	//     - 请求: token / batch_id
	//     - 响应
	//         + err
	//         + user_data
	//             * building_list
	//                 - 房子编号 id，批次编号 batch_id，名字 name，是否车位 is_parking，面积 area，几房几厅几卫描述 desc，单价 unit_price，总价 total_price，订购者编号 book_user_id，收藏计数 favorite_count
    public Function GetBuilding($token, $batch_id){
    	
		$data = ['err' => 0];
		$user_id = $this->_GetUserID($token);
		if($user_id == 0) {
			$data["err"] = 1;
			return json($data);
		}

		$building_list = Building::where("batch_id", $batch_id)->select();
		$data["building_list"] = $building_list;
        return json($data);
    }

	// * 收藏数据 GetFavorite
	//     - 请求: token
	//     - 响应
	//         + err
	//         + user_data
	//             * favorite_building_list
	//                 - 房子编号 id，批次编号 batch_id，名字 name，是否车位 is_parking，面积 area，几房几厅几卫描述 desc，单价 unit_price，总价 total_price，订购者编号 book_user_id，收藏计数 favorite_count
    public Function GetFavorite($token){
    	
		$data = ['err' => 0];
		$user_id = $this->_GetUserID($token);
		if($user_id == 0) {
			$data["err"] = 1;
			return json($data);
		}

		$favorite_building_list = Building::where("user_id", $user_id)->select();
		$data["favorite_building_list"] = $favorite_building_list;
        return json($data);
    }

	// * 下单数据 GetBookData
	//     - 请求: token
	//     - 响应
	//         + err
	//         + user_data
	//             * book_building_list
	//                 - 房子编号 id，批次编号 batch_id，名字 name，是否车位 is_parking，面积 area，几房几厅几卫描述 desc，单价 unit_price，总价 total_price，订购者编号 book_user_id，收藏计数 favorite_count
    public Function GetBookData($token){
    	
		$data = ['err' => 0];
		$user_id = $this->_GetUserID($token);
		if($user_id == 0) {
			$data["err"] = 1;
			return json($data);
		}

		$book_building_list = Building::where("user_id", $user_id)->select();
		$data["favorite_building_list"] = $book_building_list;
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
        Favorite::create([
            'user_id'  =>  $user_id,
            'building_id' =>  $building_id
        ], ['user_id','building_id'], true);
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
        Order::create([
            'user_id'  =>  $user_id,
            'building_id' =>  $building_id
        ], ['user_id','building_id'], true);
        return json($data);
    }


}
