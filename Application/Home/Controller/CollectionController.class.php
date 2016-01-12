<?php

namespace Home\Controller;

use Think\Controller;

class CollectionController extends Controller
{
    private $mCollectionModel;

    public function __construct()
    {
        $this->mCollectionModel = new \Home\Model\CollectionModel();
    }

    //我的收藏问题列表接口
    public function myCollects()
    {
        //1、获取传参
        $params = array(
            'user_id' => I('get.user_id'), //用户账号
            'page' => I('get.page'), //用户密码
        );
        //2、检测传参是否合法

        //3、通过model进行数据库操作
        $mModelRet = $this->mCollectionModel->myCollects($params);
//        if (count($mModelRet) > 0) {
        //4、对model获取的数据进行格式化并返回
        returnApiSuccess("成功", $mModelRet);
//        } else {
//            returnApiError("用户名或密码错误");
//        }
    }

    //收藏与取消收藏
    public function collectAndDelete()
    {
        //1、获取传参
        $params = array(
            'user_id' => I('post.user_id'), //用户id
            'question_id' => I('post.question_id') //问题id
        );

        //2、检测传参是否合法

        //3、通过model进行数据库操作
        $mModelRet = $this->mCollectionModel->collectAndDelete($params);
        if ($mModelRet != false) {
            //  4、对model获取的数据进行格式化并返回
            returnApiSuccess("成功", $mModelRet);
        } else {
            returnApiError("传参错误");
        }
    }
}