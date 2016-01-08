<?php
namespace Home\Model;

use Think\Model;

class QuestionModel extends Model
{

    //model使用到的数据库表
    const TB_QUESTION = 'question'; //问题表

    //查询问题列表
    public function selectQuestionList($params)
    {
        //获取参数
        $tb_question = self::TB_QUESTION;
        $page = $params;
        $from = ($page * 20) - 20;
        //拼接数据库语句并查询
        $sql = "select * from {$tb_question} order by created_at desc limit {$from},20";
        $ret = $this->query($sql);
        return $ret;
    }

    //我的问题列表
    public function myQuestionsList($params)
    {
        //获取参数
        $tb_question = self::TB_QUESTION;
        $page = $params['page'];
        $user_id = $params['user_id'];
        $from = ($page * 20) - 20;
        //拼接数据库语句并查询
        $sql = "select * from {$tb_question} where user_id = {$user_id} order by created_at desc limit {$from},20";
        $ret = $this->query($sql);
        return $ret;
    }

    //类别问题列表
    public function typeQuestionsList($params)
    {
        //获取参数
        $tb_question = self::TB_QUESTION;
        $page = $params['page'];
        $type = $params['type'];
        $from = ($page * 20) - 20;
        //拼接数据库语句并查询
        $sql = "select * from {$tb_question} where type = {$type} order by created_at desc limit {$from},20";
        $ret = $this->query($sql);
        return $ret;
    }

    //关键词搜索问题列表
    public function keywordQuestionsList($params)
    {
        //获取参数
        $tb_question = self::TB_QUESTION;
        $page = $params['page'];
        $keyword = $params['keyword'];
        $from = ($page * 20) - 20;
        //拼接数据库语句并查询
        $sql = "select * from {$tb_question} where title like '%{$keyword}%' order by created_at desc limit {$from},20";
        $ret = $this->query($sql);
        return $ret;
    }

    //问题详情
    public function questionsDetails($params)
    {
        //获取参数
        $question_id = $params;
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

    //提问，添加问题
    public function askQuestion($params)
    {
        $QuestionModel = M('question');
        $photoUrl = $params['cover'];

        $params['created_at'] = date("Y-m-d H:i:s");
        $params['updated_at'] = date("Y-m-d H:i:s");
        $params['score'] = 0;
        $params['collected'] = 0; //返回是boolean，别忘了
        $params['cover_url'] = $photoUrl;
        $params['cover_thumb_url'] = $photoUrl;
        $params['collect_count'] = 0;
        $params['answer_count'] = 0;

        $ret = $QuestionModel->add($params);
        if ($ret != false) {
            //以id查询该问题详情
            $selectQuestionParams['id'] = $ret;
            $questionRet = $QuestionModel->where($selectQuestionParams)->select();
            //以user_id查询该user
            $UserModel = M('user');
            $selectUserParams['id'] = $params['user_id'];
            $userRet = $UserModel->where($selectUserParams)->select();

            //拼接返回内容
            $questionRet[0]['user'] = $userRet[0];
            return $questionRet[0];
        } else
            return array();
    }
}