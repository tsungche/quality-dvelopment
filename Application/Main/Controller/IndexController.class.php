<?php
namespace Main\Controller;
use Think\Controller;

class IndexController extends Controller {

    public function _initialize(){
        if(!D('Index','Service') -> hasLogin()){
            $this->redirect("Login/index");
        }
    }

    public function index(){
        $index = D('Index','Service') -> getIndex();
        if($index){
            $this->redirect($index);
        }else{
            $this->redirect("Login/index");
        }
    }

    /**
     * 更新用户密码
     */
    public function updatePassword(){
        $data = I("post.");
        $result = D('Index','Service') -> updatePassword($data);
        $this -> ajaxReturn($result,'JSON');
    }

    /**
     * 退出系统
     */
    public function exitSystem(){
        session(null);
        $this->redirect("Login/index");
    }

    /**
     * 异步上传文件
     */
    public function ajaxUploadFile(){
        $user = session(C("USER"));
        $type = I("post.type");
        $oldUrl = I("post.oldUrl");
        $userType = I("post.userType");

        //再次上传时，把旧的文件删除
        if(!empty($oldUrl)){
            unlink(C("UPLOAD_LOCAL_SITE_SIMPLE").$oldUrl);
        }

        $savePath =  '/'.$user[C('GRADE')].'/'.$user[C('USERID')].'/'; //默认值

        //判断用户类型
        if(!empty($userType)){
            if($userType == "admin"){
                $savePath = '/admin/pdf/';
            }
        }

        //上传配置
        $config = array(
            'maxSize'    =>    C("UPLOAD_SIZE"),
            'rootPath'   =>    C("UPLOAD_LOCAL_SITE"),
            'savePath'   =>    $savePath,
            'saveName'   =>    time().mt_rand(),
            'exts'       =>    array($type),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        // 上传文件
        $info   =   $upload->uploadOne($_FILES['f-file']);
        if(!$info) {// 上传错误提示错误信息
            $this -> ajaxReturn($upload->getError(),'JSON');
        }else{// 上传成功
            $this -> ajaxReturn($info,'JSON');
        }
    }

}