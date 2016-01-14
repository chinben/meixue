<?php
namespace Home\Model;

use Think\Model;

class AnswerModel extends Model
{

    //model使用到的数据库表
    const TB_ANSWER = 'answer'; //答案表
    const TB_QUESTION = 'question'; //问题表

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

    //回答问题
    public function answers($params)
    {
        //获取参数
        $tb_answer = self::TB_ANSWER;
        $user_id = $params['user_id'];
        $question_id = $params['question_id'];
        $content = $params['content'];

        //拼接数据库语句并查询floor值
        $sql = "select * from {$tb_answer} where question_id = {$question_id} order by created_at desc limit 0,1";
        $ret = $this->query($sql);
        if (count($ret) > 0) {
            $floor = $ret[0]['floor'] + 1;
        } else {
            $floor = 1;
        }
        $AnswerModel = D('answer');
        $data['user_id'] = $user_id;
        $data['question_id'] = $question_id;
        $data['content'] = $content;
        $data['is_true'] = 0;
        $data['to_user_id'] = $user_id;
        $data['floor'] = $floor;
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");

        $sqlRet = $AnswerModel->add($data);
        if ($sqlRet != false) {
            //该问题的answer_count加一
            $questionModel = M("question");
            $questionModel->where("id='{$question_id}'")->setInc('answer_count'); // 用户收藏数加1
            return true;
        } else {
            return false;
        }
    }

    //我的答列表
    public function myAnswers($params)
    {
        //获取参数
        $tb_answer = self::TB_ANSWER;
        $tb_question = self::TB_QUESTION;
        $page = $params['page'];
        $user_id = $params['user_id'];
        $allRet = array();

        $from = ($page * 20) - 20;
        //拼接数据库语句并查询
        $sql = "select * from {$tb_answer} where user_id = {$user_id} order by created_at desc limit {$from},20";
        $ret = $this->query($sql);

        for ($i = 0; $i < count($ret); $i++) {
            $question_id = $ret[$i]['question_id'];
            $sql = "select * from {$tb_question} where id = {$question_id}";
            $questionRet = $this->query($sql);
            $allRet[$i] = $ret[$i];
            $allRet[$i]['question'] = $questionRet[0];
        }
        return $allRet;
    }
}