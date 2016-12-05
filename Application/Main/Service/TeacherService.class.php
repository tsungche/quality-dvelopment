<?php
namespace Main\Service;

class TeacherService{

    /**
     * 获取侧边菜单
     * @return array
     */
    public function getSideMenu(){
        $studentList = $this -> getStudentList();
        $menu = array();
        $grade = $studentList[0]['student_grade'];
        $menu[$grade]['name'] = $grade;
        for ($i = 0; $i < count($studentList); $i++) {
            if($studentList[$i]['student_grade'] != $grade){
                $grade = $studentList[$i]['student_grade'];
                $menu[$grade]['name'] = $grade;
            }
            $studentList[$i]['student_id'] = base64_encode($studentList[$i]['student_id']);
            $studentList[$i]['url'] = U('Teacher/student', array('type' => $studentList[$i]['student_id']));
            $menu[$grade]['student'][] = $studentList[$i];
        }
        return $menu;
    }

    /**
     * 获取学生列表
     * @return mixed
     */
    public function getStudentList(){
        $user = session(C('USER'));
        $students = M('t_student_teacher')
            ->field(array(
                't_student_teacher.id' => 'student_teacher_id',
                't_student.user_id' => 'student_id',
                't_student.name' => 'student_name',
                't_student.grade' => 'student_grade',
                't_student.profession' => 'student_profession',
                't_student.updated_count' => 'student_updated_count',
            ))
            ->join('t_student on t_student_teacher.student_id = t_student.user_id')
            ->order('t_student.grade asc,t_student.user_id asc')
            ->where(array(
                't_student_teacher.teacher_id' => $user[C('USERID')]
            ))
            ->select();
        return $students;
    }

    /**
     * 获取所有当前用户的学生上传记录
     * @param array $where
     * @return mixed
     */
    public function getRecord($where = array()){
        $user = session(C('USER'));
        $where['t_student_teacher.teacher_id'] = $user[C('USERID')];
        $record = M('t_student_teacher')
            ->join('t_student on t_student_teacher.student_id = t_student.user_id')
            ->join("t_record on t_record.student_teacher_id = t_student_teacher.id")
            ->order('t_record.created_at desc')
            ->where($where)
            ->select();
        return $record;
    }

    /**
     * 获取待处理上传记录
     */
    public function getSuspending(){
        $suspending = $this -> getRecord(array(
            "t_record.status" => "待导师审批"
        ));
        for ($i = 0; $i < count($suspending); $i++) {
            $suspending[$i]["student_url"] = U('Teacher/student', array('type' => base64_encode($suspending[$i]['student_id'])));
        }
        return $suspending;
    }

    /**
     * 根据类型ID获取该类已得分数
     * @param $type_id
     * @param $studentId
     * @return int
     */
    public function getSumPoint($type_id,$studentId)
    {
        $sumPoint = M('t_record')
            ->join('t_student_teacher on t_student_teacher.id = t_record.student_teacher_id')
            ->where(array(
                't_student_teacher.student_id' => $studentId,
                't_record.type_id' => $type_id,
                't_record.status' => "已通过",
            ))
            ->sum('t_record.point');
        //如果没有记录则返回0
        $sumPoint = ($sumPoint == null) ? 0 : $sumPoint;
        return $sumPoint;
    }

    /**
     * 获取单个学生信息
     * @param $studentId
     * @return mixed
     */
    public function getStudent($studentId){
        return M("t_student")->where("user_id = ".$studentId)->find();
    }

    /**
     * 获取学分认证报表
     * @param string $studentId
     * @return mixed
     */
    public function getCognizanceReport($studentId){
        $student = $this->getStudent($studentId);
        $typeList = M('t_type_grade_profession')
            ->field(array(
                't_type_grade_profession.id' => 'id',
                't_type_grade_profession.type_id' => 'type_id',
                't_type.name' => 'type',
                't_type_grade_profession.max_point' => 'max_point',
                't_type_grade_profession.min_point' => 'min_point',
                't_type_grade_profession.property' => 'property'
            ))
            ->join('t_type on t_type.id = t_type_grade_profession.type_id')
            ->where(array(
                't_type_grade_profession.grade' => $student["grade"],
                't_type_grade_profession.profession' => $student["profession"]
            ))
            ->select();

        for ($i = 0; $i < count($typeList); $i++) {
            $type_id = $typeList[$i]['type_id'];
            $typeList[$i]['sum_point'] = $this->getSumPoint($type_id,$student['user_id']);
        }
        return $typeList;
    }

    /**根据类型ID获取记录
     * @param $studentId
     * @param $where
     * @return mixed
     */
    public function getSubjectReport($studentId = "",$where = array()){
        if(!empty($studentId)){
            $student = $this->getStudent($studentId);
            $where['t_student_teacher.student_id'] = $student["user_id"];
        }
        $subjectReport = M('t_record')
            ->field(array(
                't_record.id' => 'id',
                't_record.title' => 'title',
                't_record.url' => 'file_url',
                't_record.remark' => 'remark',
                't_record.feedback' => 'feedback',
                't_record.point' => 'point',
                't_record.status' => 'status',
                't_record.created_at' => 'created_at',
                't_record.updated_by' => 'updated_by',
                't_record.updated_at' => 'updated_at',
                't_type.name' => 'type',
                't_student_teacher.student_id' => 'student_id'
            ))
            ->join('t_student_teacher on t_student_teacher.id = t_record.student_teacher_id')
            ->join('t_type on t_type.id = t_record.type_id')
            ->where($where)
            ->order("status desc,updated_at desc")
            ->select();

        for ($i = 0; $i < count($subjectReport); $i++) {
            if ($subjectReport[$i]['status'] == '待导师审批') {
                $subjectReport[$i]['point'] = "0.0";
                $subjectReport[$i]['updated_at'] = "暂无";
                $subjectReport[$i]['updated_by'] = "暂无";
                $subjectReport[$i]['handling_val'] = base64_encode($subjectReport[$i]['id']);
            } else {
                $subjectReport[$i]['updated_at'] = date('Y-m-d',strtotime($subjectReport[$i]['updated_at']));
            }
            $subjectReport[$i]['detail_url'] = U('Teacher/detail', array('record' => base64_encode($subjectReport[$i]['id'])));
            $subjectReport[$i]['created_at'] = date('Y-m-d',strtotime($subjectReport[$i]['created_at']));
            $subjectReport[$i]['file_url'] = C("UPLOAD_STATIC_SITE").$subjectReport[$i]['file_url'];
        }

        return $subjectReport;
    }

    /**
     * 快速审批表
     * @return mixed
     */
    public function getSimpleApproval(){
        $simple = $this -> getSubjectReport(null,array(
            "t_record.status" => "待导师审批"
        ));  //待处理报表

        for ($i = 0; $i < count($simple); $i++) {
            $student = $this -> getStudent($simple[$i]['student_id']);
            $simple[$i]['name'] = $student['name'];
            $simple[$i]["student_url"] = U('Teacher/student', array('type' => base64_encode($simple[$i]['student_id'])));
        }
        return $simple;
    }

    /**
     * 根据类型ID获取类型内容
     * @param $typeId
     * @return mixed
     */
    public function getTypeById($typeId){
        return M("t_type")
            ->where(array("id" => $typeId))
            ->find();
    }

    /**
     * 获取评分标准
     * @param $type_id
     * @param $grade
     * @param $profession
     * @return mixed
     */
    public function getStandard($type_id,$grade,$profession){
        return M('t_type_grade_profession')
            -> where(array(
                'type_id' => $type_id,
                'grade' => $grade,
                'profession' => $profession
            ))
            -> find();
    }

    /**
     * 清除消息红点提示
     */
    public function clearMessageTips(){
        $user = session(C('USER'));
        M('t_teacher')->where('user_id =' . $user[C('USERID')])->save(array('feedback_count' => '0'));
    }

    /**未读消息数量
     * @return mixed
     */
    public function getFeedbackCount(){
        $user = session(C('USER'));
        $student = M('t_teacher')->field('feedback_count')->where('user_id = ' . $user[C('USERID')])->find();
        $feedback = ($student['feedback_count'] == null) ? 0 : $student['feedback_count'];
        return $feedback;
    }

    /**
     * 审批通过
     * @param $data
     * @return mixed
     */
    public function recordGo($data){
        $user = session(C('USER'));
        $record = $this -> getRecord(array(
            "t_record.id" => $data["recordId"]
        )); // 获取记录相关信息
        $record = $record[0];
        if(!empty($record)){
            $standard = $this -> getStandard($record['type_id'],$record['grade'],$record['profession']); //获取评分标准
            $sumPoint = $this -> getSumPoint($record['type_id'],$record['student_id']); //获取该类现有总分
            if($data['point'] >= 0){
                if($sumPoint <= $standard['max_point']){
                    $sum = $sumPoint + $data['point'];
                    if($sum <= $standard['max_point']){
                        $results = M('t_record') //更新记录
                            -> where('id ='.$data["recordId"].' and '."status = '待导师审批'")
                            ->save(array(
                                'point' => $data['point'],
                                'status' => '待学院审批',
                                'feedback'=> '无',
                                'updated_by' => $user[C('NAME')],
                                'updated_at' => date('Y-m-d H:i:s', time())
                            ));
                        if($results){
                            M('t_student') -> where('user_id ='.$record['student_id']) -> setDec('updated_count'); //更新学生动态数
                            M('t_student') -> where('user_id ='.$record['student_id']) -> setInc('feedback_count'); //更新学生反馈数
                            M('t_user_message')
                                -> where('user_id ='.$record['student_id'])
                                -> add(array(
                                    'user_id' => $record['student_id'],
                                    'title' => '导师审批通过',
                                    'type' => '导师消息',
                                    'record_id' => $data["recordId"],
                                    'content' => "你的 <strong style='color:#199dc6;'>" .$record['title']."</strong> 已被 ".$user[C('NAME')]. " 审批完毕，结果为 <strong style='color:#c6c200;'>已通过</strong> ，得分： <strong style='color:#00c645;'>" .$data['point']."</strong> 分。",
                                    'created_by' => $user[C('NAME')],
                                    'created_at' => date('Y-m-d H:i:s', time())
                                ));
                            $result['status'] = true;
                            $result['msg'] = '审批成功';
                        }else{
                            $result['status'] = false;
                            $result['msg'] = '审批失败';
                        }
                    }else{
                        $result['status'] = false;
                        $result['msg'] = '评分不能超过最大上限';
                    }
                }else{
                    $result['status'] = false;
                    $result['msg'] = '该类分数已达到上限';
                }
            }else{
                $result['status'] = false;
                $result['msg'] = '评分不能为负数';
            }
        }else{
            $result['status'] = false;
            $result['msg'] = '审批错误';
        }
        return $result;
    }

    /**
     * 待商议
     * @param $recordId
     * @return mixed
     */
    public function recordPass($recordId){
        $user = session(C('USER'));
        $record = $this -> getRecord(array(
            "t_record.id" => $recordId
        )); // 获取记录相关信息
        $record = $record[0];
        if(!empty($record)){
            $results = M('t_record') //更新记录
            -> where('id ='.$recordId.' and '."status = '待导师审批'")
                ->save(array(
                    'point' => '0',
                    'status' => '待商议',
                    'feedback'=> '无',
                    'updated_by' => $user[C('NAME')],
                    'updated_at' => date('Y-m-d H:i:s', time())
                ));
            if($results){
                M('t_student') -> where('user_id ='.$record['student_id']) -> setDec('updated_count'); //更新学生动态数
                M('t_student') -> where('user_id ='.$record['student_id']) -> setInc('feedback_count'); //更新学生反馈数
                M('t_user_message')
                    -> where('user_id ='.$record['student_id'])
                    -> add(array(
                        'user_id' => $record['student_id'],
                        'title' => '导师审批待商议',
                        'type' => '导师消息',
                        'record_id' => $recordId,
                        'content' => "你的 <strong style='color:#199dc6;'>" .$record['title']."</strong> 已被 ".$user[C('NAME')]. " 审批完毕，结果为 <strong style='color:#fa8f02;'>待商议</strong>  。",
                        'created_by' => $user[C('NAME')],
                        'created_at' => date('Y-m-d H:i:s', time())
                    ));
                $result['status'] = true;
                $result['msg'] = '审批成功';
            }else{
                $result['status'] = false;
                $result['msg'] = '审批失败';
            }
        }else{
            $result['status'] = false;
            $result['msg'] = '审批错误';
        }
        return $result;
    }

    /**
     * 审批不通过
     * @param $data
     * @return mixed
     */
    public function recordNotGo($data){
        $user = session(C('USER'));
        $record = $this -> getRecord(array(
            "t_record.id" => $data['recordId']
        )); // 获取记录相关信息
        $record = $record[0];
        if(!empty($record)){
            $results = M('t_record') //更新记录
            -> where('id ='.$data['recordId'].' and '."status = '待导师审批'")
                ->save(array(
                    'point' => '0',
                    'status' => '不通过',
                    'feedback'=> $data['feedback'],
                    'updated_by' => $user[C('NAME')],
                    'updated_at' => date('Y-m-d H:i:s', time())
                ));
            if($results){
                M('t_student') -> where('user_id ='.$record['student_id']) -> setDec('updated_count'); //更新学生动态数
                M('t_student') -> where('user_id ='.$record['student_id']) -> setInc('feedback_count'); //更新学生反馈数
                M('t_user_message')
                    -> where('user_id ='.$record['student_id'])
                    -> add(array(
                        'user_id' => $record['student_id'],
                        'title' => '导师审批不通过',
                        'type' => '导师消息',
                        'record_id' => $data['recordId'],
                        'content' => "你的 <strong style='color:#199dc6;'>" .$record['title']."</strong> 已被 ".$user[C('NAME')]. " 审批完毕，结果为 <strong style='color:#fa0900;'>不通过</strong>  。<br/>反馈：".$data['feedback'],
                        'created_by' => $user[C('NAME')],
                        'created_at' => date('Y-m-d H:i:s', time())
                    ));
                $result['status'] = true;
                $result['msg'] = '审批成功';
            }else{
                $result['status'] = false;
                $result['msg'] = '审批失败';
            }
        }else{
            $result['status'] = false;
            $result['msg'] = '审批错误';
        }
        return $result;
    }

    /**
     * 获取学生列表
     * @param $where
     * @param $page
     * @return mixed
     */

    public function getStudentListFromPage($where,$page = 1){
        $user = session(C('USER'));
        $where["t_student_teacher.teacher_id"] = $user[C('USERID')];
        return M("t_student")
            ->join("t_student_teacher on t_student_teacher.student_id = t_student.user_id")
            ->where($where)
            ->page($page,C("LIMITITEM"))
            ->select();
    }

    /**
     * 获取学生数量
     * @param $where
     * @return mixed
     */
    public function getStudentCount($where){
        $user = session(C('USER'));
        $where["t_student_teacher.teacher_id"] = $user[C('USERID')];
        return M("t_student")
            ->join("t_student_teacher on t_student_teacher.student_id = t_student.user_id")
            ->where($where)
            ->count();
    }

    /**
     * 获取全部评分标准
     * @param $grade
     * @param $profession
     * @return mixed
     */
    public function getAllStandard($grade,$profession){
        return M('t_type_grade_profession')
            -> join("t_type on t_type.id = t_type_grade_profession.type_id")
            -> where(array(
                'grade' => $grade,
                'profession' => $profession
            ))
            -> select();
    }

    /**
     * 获取学生的各类已修学分
     * @param $studentId
     * @param $createSub
     */
    public function getSumList($studentId,$createSub = null){

        $subQuery = M('t_record as r')            //子查询
        ->field("r.type_id as type_id,r.point as point")
            ->join("t_student_teacher as st on r.student_teacher_id = st.id")
            ->where(array(
                "st.student_id" => $studentId,
                "r.status" => "已通过"
            ))
            ->buildSql();

        $sumList = M("t_type as t")             //各类总分
        ->field("t.id as type_id ,ifnull(SUM(s.point),0) as sum_point")
            ->join($subQuery." as s on s.type_id=t.id","LEFT",true)
            ->group("t.id")
            ->select($createSub);

        return $sumList;
    }

    /**
     * 学生总情况
     * @param array $where:学生筛选条件
     * @param $page
     * @return mixed
     */
    public function report($page,$where = array()){
        $students = $this -> getStudentListFromPage($where,$page);
        $pageHtml = getPageHtml("Teacher/student",$this -> getStudentCount($where),$page,$where);
        for($i = 0;$i < count($students);$i++){
            $subQuery = $this -> getSumList($students[$i]['user_id'],false); //生成SQL语句(学生的各类已修总学分)
            $fail = M('t_type_grade_profession as t')
                -> join("(" . $subQuery . ") as s on s.type_id = t.type_id","INNER",true)
                -> where(array(
                    't.grade' => $students[$i]['grade'],
                    't.profession' => $students[$i]['profession'],
                    't.property' => '必修',
                    't.min_point' => array('gt','s.point')
                ))
                -> count();
            $sum = 0;//学生的已修总学分
            $sumList = M() -> query($subQuery); //执行SQL语句
            foreach($sumList as $item){
                $sum += $item['sum_point'];
            }
            $students[$i]['sum'] = $sum;
            $students[$i]['fail'] = $fail;
            $students[$i]["student_url"] = U('Teacher/student', array('type' => base64_encode($students[$i]['user_id'])));
        }
        $result['students'] = $students;
        $result['pageHtml'] = $pageHtml;
        return $result;
    }

    /**
     * 获取专业
     * @return mixed
     */
    public function getProfession(){
        return M("t_profession")->select();
    }

    /**
     * 获取年级
     * @return mixed
     */
    public function getGrade(){
        return M("t_grade")->select();
    }

    /**
     * 获取班别
     * @param $profession
     * @param $grade
     * @return mixed
     */
    public function getClazz($profession,$grade){
        return M("t_class as c")
            ->field(array(
                "c.id" => "id",
                "c.name" => "name",
            ))
            ->join("t_profession as p on p.id = c.profession_id")
            ->join("t_grade as g on g.id = c.grade_id")
            ->where(array(
                "p.name" => $profession,
                "g.name" => $grade,
            ))
            ->select();
    }


    /**
     * 获取报表数据
     * @return mixed
     */
    public function getReportData(){
        $user = session(C('USER'));
        $subQuery = M('t_student_teacher as st')            //子查询
        ->field(array(
            "st.id" => "id",
            "s.user_id" => "student_id",
            "s.name" => "student",
            "s.profession" => "profession",
            "s.grade" => "grade",
            "s.clazz" => "clazz",
            "t.name" => "teacher",
            "t.user_id" => "teacher_id",
        ))
        ->join("t_student as s on s.user_id = st.student_id")
        ->join("t_teacher as t on t.user_id = st.teacher_id")
        ->buildSql();

        $data = M("t_record as r")             //各类总分
        ->field(array(
            "u.student_id" => "student_id",
            "u.student" => "student",
            "u.profession" => "profession",
            "u.grade" => "grade",
            "u.clazz" => "clazz",
            "r.title" => "title",
            "r.remark" => "remark",
            "r.status" => "status",
            "r.feedback" => "feedback",
            "r.point" => "point",
            "r.created_at" => "created_at",
            "r.updated_at" => "updated_at",
            "r.updated_by" => "updated_by",
            "u.teacher" => "teacher",
        ))
        ->join($subQuery." as u on u.id=r.student_teacher_id","LEFT",true)
        ->order('u.student_id asc,r.created_at asc')
        ->where(array(
            "u.teacher_id" => $user[C("USERID")]
        ))
        ->select();
        return $data;
    }


    /**
     * 导出报表Excel
     * @param $title
     * @param $head
     * @param $data
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function exportExcel($title,$head,$data){
        import("Org.Util.PHPExcel");
        $objPHPExcel = new \PHPExcel();
//        创建人
        $objPHPExcel->getProperties()->setCreator("HYH");
//        修改人
        $objPHPExcel->getProperties()->setLastModifiedBy("HYH");
//        标题
        $objPHPExcel->getProperties()->setTitle("SZTZ Report");
//        题目
        $objPHPExcel->getProperties()->setSubject("SZTZ Report");
//        描述
        $objPHPExcel->getProperties()->setDescription("SZTZ Report By HanYongHao");

        $row = 1;
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
//        设置活动的sheet
        $objPHPExcel->setActiveSheetIndex(0);
//        合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells($cellName[0].$row.':'.$cellName[(count($head)-1)].$row)->setCellValue($cellName[0].$row, $title);
//        设置局中
        $objPHPExcel->getActiveSheet()->getStyle($cellName[0].$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row++;
//        设置表头
        for($i = 0;$i < count($head);$i++){
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i].$row,$head[$i]);
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i].$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimension($cellName[$i])->setWidth(10);
        }

        $row++;
//        设置表内容
        for($i = 0;$i < count($data);$i++){

            $j = 0;
            foreach ($data[$i] as $item){
                $objPHPExcel->getActiveSheet()->setCellValue($cellName[$j].$row,$item);
                $j++;
            }

//            for($j = 0;$j < count($data[$i]);$j++){
//                $objPHPExcel->getActiveSheet()->setCellValue($cellName[$j].$row,$data[$i][$j]);
//            }

            $row++;
        }
        $xlsTitle = iconv('utf-8', 'gb2312', 'test');//文件名称
        $fileName = $title.date('_YmdHis');//or $xlsTitle 文件名

//        设置输出数据类型
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}