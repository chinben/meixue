<?php
namespace Home\Model;

use Think\Model;

class CollectionModel extends Model
{

    //model使用到的数据库表
    const TB_USER = 'user'; //用户表
    const TB_COLLECTION = 'collection'; //收藏表
    const TB_QUESTION = 'question'; //问题表

    //我的收藏问题列表
    public function myCollects($params)
    {
        //获取参数
        $tb_question = self::TB_QUESTION;
        $tb_collection = self::TB_COLLECTION;
        $page = $params['page'];
        $user_id = $params['user_id'];

        $allRet = array();

        $from = ($page * 20) - 20;
        //拼接数据库语句并查询
        $sql = "select * from {$tb_collection} where user_id = {$user_id} order by created_at desc limit {$from},20";
        $ret = $this->query($sql);
        for ($i = 0; $i < count($ret); $i++) {
            $id = $ret[$i]['question_id'];
            $sql = "select * from {$tb_question} where id = {$id}";
            $questionRet = $this->query($sql);
            $allRet[$i] = $questionRet[0];
        }
        return $allRet;
    }

    //收藏与取消收藏
    public function collectAndDelete($params)
    {
        //获取并格式化必要参数
        $tb_collection = self::TB_COLLECTION;
        $user_id = $params['user_id'];
        $question_id = $params['question_id'];
        $sqlRet = true;

        //拼接数据库语句并查询
        $sql = "select * from {$tb_collection} where user_id = '{$user_id}' and question_id = '{$question_id}' ";
        $ret = $this->query($sql);
        if (count($ret) > 0) { //已收藏，取消收藏，删除表格该记录，并更新user表数据
            $CollectionModel = M('collection');
            $sqlRet = $CollectionModel->delete($ret[0]['id']);

            if ($sqlRet != false) {
                $User = M("user"); // 实例化User对象
                $User->where("id='{$user_id}'")->setDec('collected_count'); // 用户收藏数减1
                $Question = M("question");
                $Question->where("id='{$question_id}'")->setDec('collect_count'); // 问题收藏数减1
            }
        } else { //未收藏，添加收藏，添加表格记录，并更新user表数据
            $CollectionModel2 = D('collection');
            $data['user_id'] = $user_id;
            $data['question_id'] = $question_id;
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['updated_at'] = date("Y-m-d H:i:s");
            $sqlRet = $CollectionModel2->add($data);

            if ($sqlRet != false) {
                $User = M("user"); // 实例化User对象
                $User->where("id='{$user_id}'")->setInc('collected_count'); // 用户收藏数加1
                $Question = M("question");
                $Question->where("id='{$question_id}'")->setInc('collect_count'); // 问题收藏数加1
            }
        }
        //获取参数
        $QuestionModel = M('question');
        //以id查询该问题详情
        $selectQuestionParams['id'] = $question_id;
        $questionRet = $QuestionModel->where($selectQuestionParams)->select();
        //以user_id查询该user
        $UserModel = M('user');
        $selectUserParams['id'] = $questionRet[0]['user_id'];
        $userRet = $UserModel->where($selectUserParams)->select();
        //拼接返回内容
        $questionRet[0]['user'] = $userRet[0];
        return $questionRet[0];
    }
}