<?php
namespace Main\Controller;
use Think\Controller;

class StudentAdminController extends Controller {

    public function _initialize(){
        if(!D('Index','Service') -> hasLogin(C('STUDENTADMIN'))){
            $this->redirect("Login/index");
        }
        $count = D('StudentAdmin','Service') -> getFeedbackCount(); //反馈数
        $this->assign("user",session(C('USER'))); //用户信息
        $this->assign("msgCount",$count);  //反馈数
    }

    //首页
    public function index(){
        $i_service = D('Index','Service');
        $note = $i_service -> getLoginNote(); //登录日志
        $notice = $i_service -> getNotice(); //通知
        $note['login_ip'] = GetHostByName($_SERVER['SERVER_NAME']); //登录IP
        $this->assign("note",$note);
        $this->assign("notice",$notice);
        $this->display();
    }

    //消息
    public function message(){
        $i_service = D('Index','Service');
        $t_service = D('StudentAdmin', 'Service');
        $t_service -> clearMessageTips();
        $message = $i_service -> getMessage();
        $this->assign("message",$message);
        $this -> display();
    }

    //科目内容
    public function subject(){
        $s_service = D('StudentAdmin','Service');
        $page = I("get.page");
        if(empty($page)){
            $page = 1;
        }

        $record = $s_service -> getVoluntary($page);  //公益劳动
        $this->assign("record",$record["list"]);
        $this->assign("sumCount",$record["sumCount"]);
        $this->assign("pageHtml",$record["pageHtml"]);
        $this->assign("page",$page);
        $this->display();
    }

    //保存科目项
    public function search(){
        $s_service = D('StudentAdmin', 'Service');
        $studentId = I("post.studentId");
        $result = $s_service -> search($studentId);
        $this -> ajaxReturn($result,'JSON');
    }

    //添加科目项
    public function addItem(){
        $this->display();
    }

    //保存科目项
    public function saveItem(){
        $s_service = D('StudentAdmin', 'Service');
        $data = I("post.");
        $result = $s_service -> saveItem($data);
        $this -> ajaxReturn($result,'JSON');
    }

    //上传科目项
    public function uploadItem(){
        $s_service = D('StudentAdmin', 'Service');
        $result = $s_service -> uploadItem();
        $this -> ajaxReturn($result,'JSON');
    }

    //删除科目项
    public function deleteItem(){
        $record = base64_decode(I('post.recordId'));
        $s_service = D('StudentAdmin', 'Service');
        $flag = $s_service->deleteItem($record);
        if ($flag) {
            $result['status'] = true;
            $result['msg'] = '删除成功';
        } else {
            $result['status'] = false;
            $result['msg'] = '删除失败';
        }
        $this -> ajaxReturn($result,'JSON');
    }

    //批量删除
    public function deleteList(){
        $record = I('post.recordId');
        $s_service = D('StudentAdmin', 'Service');
        $result = $s_service->deleteList($record);
        $this -> ajaxReturn($result,'JSON');
    }

    //内容
    public function detail(){
        $recordId = I("get.id");
        $detail = D('StudentAdmin','Service') -> getRecord(1,array(
            "t_record.id" => base64_decode($recordId)
        ));
        for ($i = 0; $i < count($detail); $i++) {
            if ($detail[$i]['status'] == '待导师审批') {
                $detail[$i]['point'] = "0.0";
                $detail[$i]['updated_at'] = "暂无";
                $detail[$i]['updated_by'] = "暂无";
                $detail[$i]['feedback'] = "暂无";
            } else if($detail[$i]['status'] == '待导师审批') {
                $detail[$i]['point'] = "0.0";
            }
        }
        $type = D('StudentAdmin','Service') -> getType(array("id" => $detail[0]['type_id']));
        $this -> assign("types",$type);
        $this -> assign("detail",$detail[0]);
        $this -> display();
    }


    public function download(){
        $filePath = C("DOWNLOAD_LOCAL_SITE") . "test.xls";
        if($file = fopen($filePath, "r")){ //读取二进制文件时，需要将第二个参数设置成'rb'
            header('pragma:no-cache'); //不缓存
            header("Content-Length:" . filesize($filePath)); //设置显示大小
            header('Content-type:application/vnd.ms-excel;charset=utf-8;name="test.xls"'); //数据类型，编码，名称
            header("Content-Disposition:attachment;filename=template.xls");//attachment新窗口打印inline本窗口打印
            $contents = fread($file, filesize($filePath));  //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
            echo $contents;
        }else{
            echo "文件读取失败";
        }
        fclose($file);
    }
}