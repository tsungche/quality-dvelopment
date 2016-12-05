<?php
namespace Main\Controller;
use Think\Controller;

class TeacherController extends Controller {

    public function _initialize(){
        if(!D('Index','Service') -> hasLogin(C('TEACHER'))){
            $this->redirect("Login/index");
        }
        $side = D('Teacher','Service') -> getSideMenu();  //侧边栏
        $count = D('Teacher','Service') -> getFeedbackCount(); //反馈数
        $this->assign("user",session(C('USER'))); //用户信息
        $this->assign("side",$side);  //侧边栏
        $this->assign("msgCount",$count);  //反馈数
    }

    //首页
    public function index(){
        $i_service = D('Index','Service');
        $t_service = D('Teacher','Service');
        $note = $i_service -> getLoginNote(); //登录日志
        $notice = $i_service -> getNotice(); //通知
        $suspending = $t_service -> getSuspending();
        $note['login_ip'] = GetHostByName($_SERVER['SERVER_NAME']); //登录IP
        $this->assign("note",$note);
        $this->assign("notice",$notice);
        $this->assign("suspending",$suspending);
        $this->display();
    }

    //消息
    public function message(){
        $i_service = D('Index','Service');
        $t_service = D('Teacher', 'Service');
        $t_service -> clearMessageTips();
        $message = $i_service -> getMessage();
        $this->assign("message",$message);
        $this -> display();
    }

    //学生
    public function student(){
        $t_service = D('Teacher','Service');
        $studentId = I("get.type");
        if(!empty($studentId)){  //单科目项报表
            $student = $t_service -> getStudent(base64_decode($studentId));  //学生信息
            $cognizanceReport = $t_service -> getCognizanceReport(base64_decode($studentId));  //学分认定表
            $subjectReport = $t_service -> getSubjectReport(base64_decode($studentId));  //科目报表
            $this->assign("subjectReport",$subjectReport);
            $this->assign("cognizanceReport",$cognizanceReport);
            $this->assign("student",$student);
            $this->assign("type",$studentId);
            $this->display();
        }else{    //统计报表
            $profession = I("get.profession"); //当前专业
            $grade = I("get.grade"); //当前年级
            $clazz = I("get.clazz"); //当前班别
            $page = I("get.page"); //当前班别
            $where = array();
            if(!empty($profession) && $profession != "all"){
                $where["profession"] = $profession;
            }
            if(!empty($grade) && $grade != "all"){
                $where["grade"] = $grade;
            }
            if(empty($page)){
                $page = 1;
            }
            $professionList = $t_service -> getProfession($where); //专业列表
            $gradeList = $t_service -> getGrade($where); //年级列表
            if(!empty($profession) && !empty($grade)){
                $clazzList = $t_service -> getClazz($profession,$grade); //班级列表
                $this->assign("clazzList",$clazzList);
                if(empty($clazzList) || empty($clazz) || $clazz == "all"){
                    $clazz = "all";
                }else{
                    foreach($clazzList as $item){
                        if($item['name'] == $clazz){
                            $where["clazz"] = $clazz;
                        }
                    }
                }
            }
            $report = $t_service -> report($page,$where);
            $this->assign("profession",$profession);
            $this->assign("grade",$grade);
            $this->assign("clazz",$clazz);
            $this->assign("professionList",$professionList);
            $this->assign("gradeList",$gradeList);
            $this->assign("report",$report['students']);
            $this->assign("pageHtml",$report['pageHtml']);
            $this->display("Teacher/report");
        }
    }

    //内容
    public function detail(){
        $recordId = I("get.record");
        $detail = D('Teacher','Service') -> getRecord(array(
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
        $type = D('Teacher','Service') -> getTypeById($detail[0]['type_id']);
        $this->assign("type",base64_encode($detail[0]['student_id']));
        $this -> assign("types",$type);
        $this -> assign("detail",$detail[0]);
        $this -> display();
    }

    //审批通过
    public function adopt(){
        $post = I("post.");
        $result = D('Teacher','Service') -> recordGo($post);
        $this -> ajaxReturn($result,'JSON');
    }

    //待商议
    public function handling(){
        $record = base64_decode(I('post.recordId'));
        $result = D('Teacher','Service') -> recordPass($record);
        $this -> ajaxReturn($result,'JSON');
    }

    //审批不通过
    public function failed(){
        $post = I("post.");
        $result = D('Teacher','Service') -> recordNotGo($post);
        $this -> ajaxReturn($result,'JSON');
    }

    //快速审批
    public function simpleApproval(){
        $t_service = D('Teacher','Service');
        $record = $t_service -> getSimpleApproval();  //待处理报表
        $this->assign("record",$record);
        $this -> display();
    }

    //现在报表
    public function downloadReport(){
        $t_service = D('Teacher','Service');
        $data = $t_service->getReportData();
        $title = "素质拓展学分认证明细报表";
        $head = array("学号","名称","专业","年级","班别","标题","简介","状态","反馈","得分","创建时间","审批时间","审批人","导师");
        $t_service -> exportExcel($title,$head,$data);
    }
}