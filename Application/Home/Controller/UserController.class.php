<?php

/*
 * @desc 用户接口，关于用户一些行为操作的controller类
 * @author chenbin
 * @date 2015-1-7
 * */

namespace Home\Controller;

use Think\Controller;

class UserController extends Controller
{
    private $mUserModel;

    public function __construct()
    {
        $this->mUserModel = new \Home\Model\UserModel();
    }

    //用户登陆接口
    public function login()
    {
        //1、获取传参
        $params = array(
            'email' => I('post.email'), //用户账号
            'password' => I('post.password'), //用户密码
        );
        //2、检测传参是否合法

        //3、通过model进行数据库操作
        $mModelRet = $this->mUserModel->login($params);
        if (count($mModelRet) > 0) {
            //4、对model获取的数据进行格式化并返回
            returnApiSuccess("登录成功", $mModelRet);
        } else {
            returnApiError("用户名或密码错误");
        }
    }

    //修改密码接口
    public function modifyPassword()
    {
        //1、获取传参
        $params = array(
            'user_id' => I('post.user_id'), //用户Id
            'password' => I('post.password'), //用户账号
            'new_password' => I('post.new_password'), //用户密码
        );
        //2、检测传参是否合法

        //3、通过model进行数据库操作
        $mModelRet = $this->mUserModel->modifyPassword($params);
        if ($mModelRet >= 0) {
            //4、对model获取的数据进行格式化并返回
            returnApiSuccess("修改成功");
        } else {
            returnApiError("密码错误");
        }
    }

    //修改个人资料
    public function modifyProfile()
    {
        //上传图片
        $upload = getUpload();
        // 上传文件
        $info = $upload->uploadOne($_FILES['avatar']);
        if (!$info) { // 上传错误提示错误信息
            returnApiError($upload->getError());

        } else { // 上传成功
            //1、获取传参
            $params = array(
                'user_id' => I('post.user_id'), //用户Id
                'name' => I('post.name'), //用户密码
                'profile' => I('post.profile'), //用户Id
                'birthday' => I('post.birthday'), //用户账号
                'sex' => I('post.sex'), //用户密码
                'uploadUrl' => PHOTO_ROOT . $info['savepath'] . $info['savename'], //获取上传文件绝对路径
            );
            //2、检测传参是否合法

            //3、通过model进行数据库操作
            $mModelRet = $this->mUserModel->modifyProfile($params);
            if ($mModelRet >= 0) {
                //4、对model获取的数据进行格式化并返回
                returnApiSuccess("修改成功", $mModelRet);
            } else {
                returnApiError("失败");
            }
        }
    }
}


