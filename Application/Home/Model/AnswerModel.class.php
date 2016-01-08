<?php
namespace Home\Model;

use Think\Model;

class AnswerModel extends Model
{

    //model使用到的数据库表
    const TB_ANSWER = 'answer'; //问题表

    //问题答案列表
    public function answerList($params)
    {
        //获取参数
        $tb_answer = self::TB_ANSWER;
        $UserModel = M('user');
        $question_id = $params['question_id'];
        $page = $params['page'];
        $from = ($page * 20) - 20;
        //拼接数据库语句并查询
        $sql = "select * from {$tb_answer} where question_id = {$question_id} order by created_at desc limit {$from},20";
        $ret = $this->query($sql);
        for ($i = 0; $i < count($ret); $i++) {
            $allRet[$i] = $ret[$i];
            //以user_id查询回答问题的user
            $selectUserParams['id'] = $ret[$i]['user_id'];
            $userRet = $UserModel->where($selectUserParams)->select();
            //拼接返回内容
            $allRet[$i]['user'] = $userRet[0];
        }
        if ($allRet != null)
            return $allRet;
        else
            return array();
    }
}