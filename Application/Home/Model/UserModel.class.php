<?php
namespace Home\Model;

use Think\Model;

class UserModel extends Model
{

    //model使用到的数据库表
    const TB_USER = 'user'; //用户表
    const TB_FOLLOW = 'follow'; //关注表

    //注册
    public function signUp($params)
    {
        //获取并格式化必要参数
        $tb_user = self::TB_USER;
        $email = $params['email'];
        $password = base64_encode($params['password']); //密码通过base64加密
        $uploadUrl = $params['uploadUrl'];
        //拼接数据库语句并查询
        $sql = "select * from {$tb_user} where email = '{$email}'";
        $ret = $this->query($sql);
        if (count($ret) > 0) {
            return false;
        } else {
            $sql = "select * from {$tb_user} order by created_at desc limit 0,1";
            $ret = $this->query($sql);
            $mx_number = $ret[0]['id'] + 1;
            $date = date("Y-m-d H:i:s");
            $UserModel = D('user');
            $data['password'] = $password;
            $data['mx_number'] = $mx_number; //美号
            $data['email'] = $email;
            $data['mobile'] = "";
            $data['name'] = "用户" . $mx_number;
            $data['level'] = 1;
            $data['score'] = 1;
            $data['avatar'] = $uploadUrl;
            $data['profile'] = "";
            $data['area'] = "";
            $data['birthday'] = $date;
            $data['sex'] = 1;
            $data['created_at'] = $date;
            $data['updated_at'] = $date;
            $data['collected_count'] = 0;
            $data['following_count'] = 0;
            $data['followers_count'] = 0;
            $data['rewards_count'] = 1;
            $data['avatar_url'] = $uploadUrl;
            $data['avatar_thumb_url'] = $uploadUrl;
            $addRet = $UserModel->add($data);
            if ($addRet != false) {
                return true;
            } else {
                return false;
            }
        }
    }

    //登录
    public function login($params)
    {
        //获取并格式化必要参数
        $tb_user = self::TB_USER;
        $email = $params['email'];
        $password = base64_encode($params['password']); //密码通过base64加密
        //拼接数据库语句并查询
        $sql = "select * from {$tb_user} where email = '{$email}' and password = '{$password}'";
        $ret = $this->query($sql);
        return $ret[0];
    }

    //修改密码
    public function modifyPassword($params)
    {
        //获取并格式化必要参数
        $tb_user = self::TB_USER;
        $user_id = $params['user_id'];
        $password = base64_encode($params['password']); //密码通过base64加密
        $new_password = base64_encode($params['new_password']); //新密码通过base64加密
        //拼接数据库语句并查询密码是否错误
        $sql = "select * from {$tb_user} where id = '{$user_id}' and password = '{$password}'";
        $ret = $this->query($sql);
        if ($ret != null) {
            //密码输入正确，修改密码
            $ret['password'] = $new_password;
            // 要修改的数据对象属性赋值
            $ret1 = $this->where("id={$user_id}")->save($ret); // 根据条件更新记录
            if ($ret1 == 0 || $ret1 == true) { //save（）函数，更新成功返回true，更新失败返回false，成功但数据无更新返回0
                return 0;
            } else {
                return -1;
            }
        } else {
            return -1;
        }
    }

    //修改个人资料
    public function modifyProfile($params)
    {
        //获取并格式化必要参数
        $tb_user = self::TB_USER;
        $user_id = $params['user_id'];
        $name = $params['name'];
        $profile = $params['profile'];
        $birthday = $params['birthday'];
        $sex = $params['sex'];
        $uploadUrl = $params['uploadUrl'];

        //根据id查询要修改的用户
        $sql = "select * from {$tb_user} where id = '{$user_id}'";
        $ret = $this->query($sql);
        if ($ret != null) {
            //存在该用户，修改资料
            $ret['name'] = $name;
            $ret['profile'] = $profile;
            $ret['birthday'] = $birthday;
            $ret['sex'] = $sex;
            $ret['avatar'] = $uploadUrl;
            $ret['avatar_url'] = $uploadUrl;
            $ret['avatar_thumb_url'] = $uploadUrl;
            // 要修改的数据对象属性赋值
            $ret1 = $this->where("id={$user_id}")->save($ret); // 根据条件更新记录
            if ($ret1 == 0 || $ret1 == true) { //save（）函数，更新成功返回true，更新失败返回false，成功但数据无更新返回0
                $ret = $this->query($sql);
                return $ret[0];
            } else {
                return -1;
            }
        } else {
            return -1;
        }
    }

    //查看个人资料
    public function personalPage($params)
    {
        //获取并格式化必要参数
        $tb_user = self::TB_USER;
        $tb_follow = self::TB_FOLLOW;
        $user_id = $params['user_id'];
        $followed_user_id = $params['followed_user_id'];
        //拼接数据库语句并查询
        $sql = "select * from {$tb_user} where id = '{$followed_user_id}'";
        $ret = $this->query($sql);
        //查询是否已经关注此用户
        $sql = "select * from {$tb_follow} where user_id = '{$user_id}' and followed_user_id = '{$followed_user_id}'";
        $followRet = $this->query($sql);
        if (count($followRet) > 0) {
            $ret[0]['followed'] = true;
        } else {
            $ret[0]['followed'] = false;
        }
        return $ret[0];
    }
}