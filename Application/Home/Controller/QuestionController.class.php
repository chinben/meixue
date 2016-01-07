<?php
namespace Home\Controller;

use Think\Controller;

class QuestionController extends Controller
{

    private $mQuestionModel;

    public function __construct()
    {
        $this->mQuestionModel = new \Home\Model\QuestionModel();
    }

    public function selectQuestionList()
    {

        //1.获取传参
        $params = I('get.page', 1, 'int'); //页数

        //2.检测传参是否合法

        //3.通过model进行数据库操作
        $mModelRet = $this->mQuestionModel->selectQuestionList($params);
        if ($mModelRet != false) {
            //4.对model获取的数据进行格式化并返回
            returnApiSuccess("成功", $mModelRet);
        } else {
            returnApiError("查询失败");
        }

    }

}