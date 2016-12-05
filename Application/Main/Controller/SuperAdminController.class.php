<?php
namespace Main\Controller;
use Think\Controller;

class SuperAdminController extends Controller {

    public function _initialize(){
        $i_service = D('Index','Service');
        if($i_service -> hasLogin(C('SUPERADMIN'))){
            $count = D('TeacherAdmin','Service') -> getFeedbackCount(); //反馈数
            $this->assign("user",session(C('USER'))); //用户信息
            $this->assign("msgCount",$count);  //反馈数
        }else{
            $this->redirect("Login/index");
        }
    }

    public function editNotice(){
        $i_service = D('Index','Service');
        $notice = $i_service -> getNotice(); //通知
        $this->assign("notice",$notice);
        $this->display();
    }

}