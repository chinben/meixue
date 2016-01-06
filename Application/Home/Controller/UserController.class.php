<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    public function index(){

 		$Data     = M('user');// 实例化user数据模型
        $result     = $Data->find(1);
        
        // var_dump($result);
    	// showUser();
    	returnApiSuccess("成功",$result);
    }
}