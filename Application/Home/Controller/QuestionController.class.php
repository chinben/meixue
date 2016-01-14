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

    //首页问题列表
    public function selectQuestionList()
    {
        //1.获取传参
        $params = I('get.page', 1, 'int'); //页数

        //2.检测传参是否合法

        //3.通过model进行数据库操作
        $mModelRet = $this->mQuestionModel->selectQuestionList($params);
//        if ($mModelRet != false) {
//            //4.对model获取的数据进行格式化并返回
        returnApiSuccess("查询成功", $mModelRet);
//        } else {
//            returnApiError("查询失败");
//        }
    }

    //我的问题列表
    public function myQuestionsList()
    {
        //1.获取传参
        $params = array(
            'user_id' => I('get.user_id'), //用户id
            'page' => I('get.page', 1, 'int'), //页数
        );

        //2.检测传参是否合法

        //3.通过model进行数据库操作
        $mModelRet = $this->mQuestionModel->myQuestionsList($params);
//        if ($mModelRet != false) {
        //4.对model获取的数据进行格式化并返回
        returnApiSuccess("查询成功", $mModelRet);
//        } else {
//            returnApiError("查询失败");
//        }
    }

    //类别问题列表
    public function typeQuestionsList()
    {
        //1.获取传参
        $params = array(
            'type' => I('get.type'), //类别
            'page' => I('get.page', 1, 'int'), //页数
        );

        //2.检测传参是否合法

        //3.通过model进行数据库操作
        $mModelRet = $this->mQuestionModel->typeQuestionsList($params);
//        if ($mModelRet != false) {
        //4.对model获取的数据进行格式化并返回
        returnApiSuccess("查询成功", $mModelRet);
//        } else {
//            returnApiError("查询失败");
//        }
    }

    //关键词搜索问题列表
    public function keywordQuestionsList()
    {
        //1.获取传参
        $params = array(
            'keyword' => I('get.keyword'), //关键词
            'page' => I('get.page', 1, 'int'), //页数
        );

        //2.检测传参是否合法

        //3.通过model进行数据库操作
        $mModelRet = $this->mQuestionModel->keywordQuestionsList($params);
//        if ($mModelRet != false) {
        //4.对model获取的数据进行格式化并返回
        returnApiSuccess("查询成功", $mModelRet);
//        } else {
//            returnApiError("查询失败");
//        }
    }

    //问题详情
    public function questionsDetails()
    {
        //1.获取传参
        $params = I('get.question_id'); //问题id

        //2.检测传参是否合法

        //3.通过model进行数据库操作
        $mModelRet = $this->mQuestionModel->questionsDetails($params);
//        if ($mModelRet != false) {
//            //4.对model获取的数据进行格式化并返回
        returnApiSuccess("查询成功", $mModelRet);
//        } else {
//            returnApiError("查询失败");
//        }
    }

    //提问，添加问题
    public function askQuestion()
    {
        //上传图片
        $upload = getUpload();
        // 上传文件
        $info = $upload->uploadOne($_FILES['cover']);
        if (!$info) { // 上传错误提示错误信息
            returnApiError($upload->getError());

        } else { // 上传成功
            //1.获取传参
            $params = array(
                'user_id' => I('post.user_id'), //用户Id
                'content' => I('post.content'), //问题内容
                'type' => I('post.type'), //问题类型
                'title' => I('post.title'), //问题标题
                'cover' => PHOTO_ROOT . $info['savepath'] . $info['savename'], //获取上传文件绝对路径
            );
            //2、检测传参是否合法

            //3、通过model进行数据库操作
            $mModelRet = $this->mQuestionModel->askQuestion($params);
            if (count($mModelRet) > 0) {
                //4、对model获取的数据进行格式化并返回
                returnApiSuccess("添加成功", $mModelRet);
            } else {
                returnApiError("添加失败");
            }
        }
    }

    //删除问题
    public function questionsDelete()
    {
        //1.获取传参
        $params = I('post.question_id'); //问题id

        //2.检测传参是否合法

        //3.通过model进行数据库操作
        $mModelRet = $this->mQuestionModel->questionsDelete($params);
        if ($mModelRet != false) {
//            //4.对model获取的数据进行格式化并返回
            returnApiSuccess("删除成功");
        } else {
            returnApiError("删除失败");
        }
    }
}