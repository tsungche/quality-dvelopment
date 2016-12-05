<?php
namespace Main\Controller;
use Think\Controller;

class StudentController extends Controller {

    public function _initialize(){
        if(!D('Index','Service') -> hasLogin(C('STUDENT'))){
            $this->redirect("Login/index");
        }
        $side = D('Student','Service') -> getTypeList();  //侧边栏
        $count = D('Student','Service') -> getFeedbackCount(); //反馈数
        $this->assign("user",session(C('USER'))); //用户信息
        $this->assign("side",$side);  //侧边栏
        $this->assign("msgCount",$count);  //反馈数
    }

    //首页
    public function index(){
        $i_service = D('Index','Service');
        $s_service = D('Student','Service');
        $note = $i_service -> getLoginNote(); //登录日志
        $notice = $i_service -> getNotice(); //通知
        $cognizanceReport = $s_service -> getCognizanceReport();  //学分认定表
        $note['login_ip'] = GetHostByName($_SERVER['SERVER_NAME']); //登录IP
        $this->assign("note",$note);
        $this->assign("notice",$notice);
        $this->assign("cognizanceReport",$cognizanceReport);
        $this->display();
    }

    //科目内容
    public function subject(){
        $s_service = D('Student','Service');
        $subjectType = I("get.type");
        if(!empty($subjectType)){  //单科目项报表
            $cognizanceReport = $s_service -> getCognizanceReport(base64_decode($subjectType));  //学分认定表
            $this->assign("detail",$cognizanceReport[0]);
            $this->assign("type",$subjectType);
            $subjectReport = $s_service -> getSubjectReport(base64_decode($subjectType));  //科目报表
            $this->assign("subjectReport",$subjectReport);
            $this->display();
        }else{    //统计报表
            $cognizanceReport = $s_service -> getCognizanceReport();  //学分认定表
            $subjectReport = $s_service -> getSubjectReport();  //科目报表
            $this->assign("subjectReport",$subjectReport);
            $this->assign("cognizanceReport",$cognizanceReport);
            $this->display("Student/report");
        }
    }

    //删除科目项
    public function deleteItem(){
        $record = base64_decode(I('post.recordId'));
        $s_service = D('Student', 'Service');
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

    //添加科目项
    public function addItem(){
        $this->display();
    }

    //保存科目项
    public function saveItem(){
        $s_service = D('Student', 'Service');
        $data = I("post.");
        $data['type_id'] = base64_decode($data['type_id']);
        $cognizanceReport = $s_service -> getCognizanceReport($data['type_id']);  //学分认定表
        $type = $cognizanceReport[0];
        if($type['sum_point'] >= $type['max_point']){
            $result['status'] = false;
            $result['msg'] = '该类分数已达到上限，无法再添加。';
        }else{
            if(!empty($data['url']) && !empty($data['title'])){
                $flag = $s_service->saveItem($data);
                if ($flag) {
                    $result['status'] = true;
                    $result['msg'] = '添加成功';
                } else {
                    $result['status'] = false;
                    $result['msg'] = '添加失败';
                }
            }else{
                $result['status'] = false;
                $result['msg'] = '填写信息不完整，请上传附件。';
            }
        }
        $this -> ajaxReturn($result,'JSON');
    }

    //消息
    public function message(){
        $i_service = D('Index','Service');
        $s_service = D('Student', 'Service');
        $s_service -> clearMessageTips();
        $message = $i_service -> getMessage();
        $this->assign("message",$message);
        $this -> display();
    }

    //统计分析
    public function report(){
        $s_service = D('Student','Service');
        $cognizanceReport = $s_service -> getCognizanceReport();  //学分认定表
        $subjectReport = $s_service -> getSubjectReport();  //科目报表
        $this->assign("subjectReport",$subjectReport);
        $this->assign("cognizanceReport",$cognizanceReport);
        $this->display();
    }

    //内容
    public function detail(){
        $recordId = I("get.id");
        $detail = D('Student','Service') -> getRecord(array(
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
        $type = D('Student','Service') -> getTypeById($detail[0]['type_id']);
        $this->assign("type",base64_encode($detail[0]['student_id']));
        $this -> assign("types",$type);
        $this -> assign("detail",$detail[0]);
        $this -> display();
    }
}