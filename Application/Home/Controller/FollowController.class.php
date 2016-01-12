<?php

/*
 * @desc 用户接口，关于用户一些行为操作的controller类
 * @author chenbin
 * @date 2015-1-7
 * */

namespace Home\Controller;

use Think\Controller;

class FollowController extends Controller
{
    private $mFollowModel;

    public function __construct()
    {
        $this->mFollowModel = new \Home\Model\FollowModel();
    }

    //粉丝列表接口
    public function followers()
    {
        //1、获取传参
        $params = I('get.user_id'); //用户id

        //2、检测传参是否合法

        //3、通过model进行数据库操作
        $mModelRet = $this->mFollowModel->followers($params);
//        if (count($mModelRet) > 0) {
        //4、对model获取的数据进行格式化并返回
        returnApiSuccess("获取成功", $mModelRet);
//        } else {
//            returnApiError("传参错误");
//        }
    }

    //关注列表接口
    public function following()
    {
        //1、获取传参
        $params = I('get.user_id'); //用户id

        //2、检测传参是否合法

        //3、通过model进行数据库操作
        $mModelRet = $this->mFollowModel->following($params);
//        if (count($mModelRet) > 0) {
        //4、对model获取的数据进行格式化并返回
        returnApiSuccess("获取成功", $mModelRet);
//        } else {
//            returnApiError("传参错误");
//        }
    }

    //关注与取消关注接口
    public function following_and_cancel()
    {
        //1、获取传参
        $params = array(
            'user_id' => I('post.user_id'),
            'followed_user_id' => I('post.followed_user_id')
        ); //用户id

        //2、检测传参是否合法

        //3、通过model进行数据库操作
        $mModelRet = $this->mFollowModel->following_and_cancel($params);
        if ($mModelRet != false) {
            //  4、对model获取的数据进行格式化并返回
            returnApiSuccess("成功", $mModelRet);
        } else {
            returnApiError("传参错误");
        }
    }
}