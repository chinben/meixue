<?php
namespace Home\Model;

use Think\Model;

class FollowModel extends Model
{

    //model使用到的数据库表
    const TB_FOLLOW = 'follow'; //关注表
    const TB_USER = 'user'; //用户表

    //粉丝列表
    public function followers($params)
    {
        //获取并格式化必要参数
        $tb_follow = self::TB_FOLLOW;
        $tb_user = self::TB_USER;
        $allRet = array();
        $user_id = $params;
        //拼接数据库语句并查询
        $sql = "select * from {$tb_follow} where followed_user_id = '{$user_id}' order by created_at desc ";
        $ret = $this->query($sql);
        for ($i = 0; $i < count($ret); $i++) {
            $id = $ret[$i]['user_id'];
            $sql = "select * from {$tb_user} where id = '{$id}'";
            $userRet = $this->query($sql);
            $allRet[$i] = $userRet[0];
        }
        return $allRet;
    }

    //关注列表
    public function following($params)
    {
        //获取并格式化必要参数
        $tb_follow = self::TB_FOLLOW;
        $tb_user = self::TB_USER;
        $user_id = $params;
        $allRet = array();
        //拼接数据库语句并查询
        $sql = "select * from {$tb_follow} where user_id = '{$user_id}' order by created_at desc ";
        $ret = $this->query($sql);
        for ($i = 0; $i < count($ret); $i++) {
            $id = $ret[$i]['followed_user_id'];
            $sql = "select * from {$tb_user} where id = '{$id}'";
            $userRet = $this->query($sql);
            $allRet[$i] = $userRet[0];
        }
        return $allRet;
    }

    //关注与取消关注
    public function following_and_cancel($params)
    {
        //获取并格式化必要参数
        $tb_follow = self::TB_FOLLOW;
        $user_id = $params['user_id'];
        $followed_user_id = $params['followed_user_id'];
        $sqlRet = true;

        //拼接数据库语句并查询
        $sql = "select id from {$tb_follow} where user_id = '{$user_id}' and followed_user_id = '{$followed_user_id}' ";
        $ret = $this->query($sql);
        if (count($ret) > 0) { //已关注，取消关注，删除表格该记录，并更新user表数据
            $FollowModel = M('follow');
            $sqlRet = $FollowModel->delete($ret[0]['id']);

            if ($sqlRet != false) {
                $User = M("user"); // 实例化User对象
                $User->where("id='{$user_id}'")->setDec('following_count'); // 用户关注数减1
                $User->where("id='{$followed_user_id}'")->setDec('followers_count'); // 另一用户的粉丝数减1
            }
        } else { //未关注，添加关注，添加表格记录，并更新user表数据
            $FollowModel2 = D('follow');
            $data['user_id'] = $user_id;
            $data['followed_user_id'] = $followed_user_id;
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['updated_at'] = date("Y-m-d H:i:s");
            $sqlRet = $FollowModel2->add($data);

            if ($sqlRet != false) {
                $User = M("user"); // 实例化User对象
                $User->where("id='{$user_id}'")->setInc('following_count'); // 用户关注数加1
                $User->where("id='{$followed_user_id}'")->setInc('followers_count'); // 另一用户的粉丝数加1
            }
        }
        return $sqlRet;
    }
}