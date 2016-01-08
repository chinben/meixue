<?php

//文件上传根目录绝对路径
define("PHOTO_ROOT", "http://192.168.191.1/meixue/Uploads/");

/*************************** api开发辅助函数 **********************/

/**
 * @param null $msg 返回正确的提示信息
 * @param status success CURD 操作成功
 * @param code 200 操作成功
 * @param array $data 具体返回信息
 * Function descript: 返回带参数，标志信息，提示信息的json 数组
 *
 */
function returnApiSuccess($msg = null, $data = array())
{
    $result = array(
        'status' => 'success',
        'code' => 200,
        'msg' => $msg,
        'data' => $data
    );
    echo json_encode($result);
}

/**
 * @param null $msg 返回具体错误的提示信息
 * @param status Error CURD 操作失败
 * @param code 403 操作失败
 * Function descript:返回标志信息 ‘Error'，和提示信息的json 数组
 */
function returnApiError($msg = null)
{
    $result = array(
        'status' => 'Error',
        'code' => 403,
        'msg' => $msg,
    );
    print json_encode($result);
}

/**
 * 返回上传类
 * @return \Think\Upload
 */
function getUpload()
{
    $upload = new \Think\Upload(); // 实例化上传类
    $upload->maxSize = 0; // 设置附件上传大小,0为不限制大小
    $upload->exts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
    $upload->rootPath = './Uploads/'; // 设置附件上传根目录
    return $upload;
}