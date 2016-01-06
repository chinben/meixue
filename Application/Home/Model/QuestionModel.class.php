<?php
namespace Home\Model;
use Think\Model;
class QuestionModel extends Model {

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

 }