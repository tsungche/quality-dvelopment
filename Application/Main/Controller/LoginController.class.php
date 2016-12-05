<?php
namespace Main\Controller;

use Think\Controller;

class LoginController extends Controller
{
    //首页
    public function index()
    {
        if (IS_POST) {
            $post_data = I("post.");
            if ($this -> checkCode($post_data['code'])) {
                if ($post_data['userName']){
                    $result = D('Login', 'Service')->checkLogin($post_data);
                    if ($result === true) {
                        $this->redirect("Index/index");
                    } elseif ($result === null) {
                        $this->assign("userNameError", "用户名不存在");
                    } elseif ($result === false) {
                        $this->assign("passwordError", "密码输入错误");
                    }else{
                        $this->assign("codeError", "登录错误");
                    }
                }else{
                    $this->assign("codeError", "用户名不能为空");
                }
            } else {
                $this->assign("codeError", "验证码错误");
                $this->assign("password", $post_data['password']);
            }
            $this->assign("userName", $post_data['userName']);
            $this->assign("userType", $post_data['userType']);
        }
        $this->display();
    }

    //获取验证码
    function getCode(){
        $config = array(
            'fontSize' => 40,    // 验证码字体大小
            'length' => 4,     // 验证码位数
            'useNoise' => false, // 验证码杂点
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    function checkCode($code, $id = ''){
        $Verify = new \Think\Verify();
        return $Verify->check($code, $id);
    }

}

