<?php
/**
 * Created by PhpStorm.
 * User: Ampaw
 * Date: 2017/1/4
 * Time: 下午8:11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Register extends REST_Controller
{

    public function index_get()
    {
        // 判断是否输入值
        if(empty($_GET['account']) || empty($_GET['password']) || empty($_GET['appkey'])) {
            $this -> response(array(
                'result' => Result::ACCOUNTORPWD_EMPTY ,
                'msg'    => 'empty request param ！',
                'data'   => array()
            ));
        }

        // 取出数据
        $account  = $this -> get('account', TRUE);
        $password = $this -> get('password',TRUE);
        $appkey   = $this -> get('appkey',TRUE);

        // 验证appkey是否正确
        $sql_key = "SELECT * FROM init WHERE appkey = '$appkey'";
        $query_key = $this->db->query($sql_key);
        if ($query_key->row_array())
        {
            // 初始化数据表中有对应的appkey
            // 从用户数据表中查询数据
            $sql = "SELECT * FROM user WHERE account = ? AND appkey = ?";
            $query = $this->db->query($sql,array($account,$appkey));
            foreach ($query->result_array() as $row) {

            }

            if ($appkey == $row['appkey'] && $account == $row['account'])
            {
                // 如果这两个都相等，说明用户数据表中已经存在该用户
                $this->response(array(
                    'result' => Result::ACCOUNT_EXIST,
                    'msg'    => '用户已存在！',
                    'data'   => array()
                ));
            }
            else
            {
                // 包装数据
                $data = array(
                    'account'  => $account,
                    'password' => $password,
                    'appkey'   => $appkey,
                );

                // 添加数据到数据库
                if($this->db->insert('user',$data)) {
                    $this->response(array(
                        'result' => Result::SUCCESS,
                        'msg'    => '新建成功！',
                        'data'   => array()
                    ));
                }else {
                    $this->response(array(
                        'result' => Result::FAIL,
                        'msg'    =>'新建失败！',
                        'data'   => array()
                    ));
                }
            }

            // row_array()函数是查询结果行，返回row OR NULL
//            if ($query->row_array()){
//
//            }else{
//
//            }
        }
        else
        {
            $this->response(array(
                'result' => Result::FAIL,
                'msg'    => 'appkey param error !',
                'data'   => array()
            ));
        }
    }
}