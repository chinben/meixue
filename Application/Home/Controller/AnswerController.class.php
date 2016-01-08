<?php
namespace Home\Controller;

use Think\Controller;

class AnswerController extends Controller
{
    private $mQuestionModel; //实际上是$mAnswerModel，但TP貌似有BUG

    public function __construct()
    {
        $this->mQuestionModel = new \Home\Model\AnswerModel();
    }

    //问题答案列表
    public function answerList()
    {
        //1.获取传参
        $params = array(
            'question_id' => I('get.question_id'), //问题id
            'page' => I('get.page', 1, 'int'), //页数
        );
        //2.检测传参是否合法

        //3.通过model进行数据库操作
        $mModelRet = $this->mQuestionModel->answerList($params);
//        if ($mModelRet != false) {
//            //4.对model获取的数据进行格式化并返回
        returnApiSuccess("查询成功", $mModelRet);
//        } else {
//            returnApiError("查询失败");
//        }
    }

}