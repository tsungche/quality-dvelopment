<?php
namespace Main\Service;

class StudentService{

    /**
     * 获取侧边栏（科目类型）
     * @return mixed
     */
    public function getTypeList(){
        $typeList = M("t_type") -> select();
        for ($i = 0; $i < count($typeList); $i++) {
            $typeList[$i]['id'] = base64_encode($typeList[$i]['id']);
            $typeList[$i]['url'] = U(("Student/subject"),array("type" => $typeList[$i]['id']));
        }
        return $typeList;
    }

    /**根据类型ID获取该类已得分数
     * @param $type_id
     * @return int
     */
    public function getSumPointByTypeID($type_id)
    {
        $user = session(C("USER"));
        $sumPoint = M('t_record')
            ->join('t_student_teacher on t_student_teacher.id = t_record.student_teacher_id')
            ->where(array(
                't_student_teacher.student_id' => $user[C("USERID")],
                't_record.type_id' => $type_id,
                't_record.status' => "已通过",
            ))
            ->sum('t_record.point');
        //如果没有记录则返回0
        $sumPoint = ($sumPoint == null) ? 0 : $sumPoint;
        return $sumPoint;
    }


    /**
     * 获取学分认证报表
     * @param string $typeId
     * @return mixed
     */
    public function getCognizanceReport($typeId = ""){
        $user = session(C("USER"));

        //设置基本查找条件
        $where = array(
            't_type_grade_profession.grade' => $user[C("GRADE")],
            't_type_grade_profession.profession' => $user[C("PROFESSION")]
        );

        //如果是指定类型则添加查找条件
        if(!empty($typeId)){
            $where['t_type_grade_profession.type_id'] = $typeId;
        }

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
            ->where($where)
            ->select();

        for ($i = 0; $i < count($typeList); $i++) {
            $type_id = $typeList[$i]['type_id'];
            $typeList[$i]['sum_point'] = $this->getSumPointByTypeID($type_id);
        }
        return $typeList;
    }

    /**根据类型ID获取记录
     * @param $typeId
     * @return mixed
     */
    public function getSubjectReport($typeId = ""){
        $user = session(C('USER'));
        if(empty($typeId)){
            $where = array(
                't_student_teacher.student_id' => $user[C('USERID')],
            );
        }else{
            $where = array(
                't_student_teacher.student_id' => $user[C('USERID')],
                't_record.type_id' => $typeId
            );
        }
        $subjectReport = M('t_record')
            ->field(array(
                't_record.id' => 'id',
                't_record.title' => 'title',
                't_record.url' => 'file_url',
                't_record.remark' => 'remark',
                't_record.type_id' => 'type_id',
                't_record.feedback' => 'feedback',
                't_record.point' => 'point',
                't_record.status' => 'status',
                't_record.created_at' => 'created_at',
                't_record.updated_by' => 'updated_by',
                't_record.updated_at' => 'updated_at'
            ))
            ->join('t_student_teacher on t_student_teacher.id = t_record.student_teacher_id')
            ->where($where)
            ->order("status desc,updated_at desc")
            ->select();

        for ($i = 0; $i < count($subjectReport); $i++) {
            if ($subjectReport[$i]['status'] == '待导师审批') {
                $subjectReport[$i]['point'] = "0.0";
                $subjectReport[$i]['updated_at'] = "暂无";
                $subjectReport[$i]['updated_by'] = "暂无";
                $subjectReport[$i]['delete_val'] = base64_encode($subjectReport[$i]['id']);
            }else{
                $subjectReport[$i]['delete_val'] = 'false';
                $subjectReport[$i]['updated_at'] = date('Y-m-d',strtotime($subjectReport[$i]['updated_at']));
            }
            $subjectReport[$i]['created_at'] = date('Y-m-d',strtotime($subjectReport[$i]['created_at']));
            $subjectReport[$i]['file_url'] = C("UPLOAD_STATIC_SITE").$subjectReport[$i]['file_url'];
            $subjectReport[$i]['detail_url'] = U('Student/detail', array('id' => base64_encode($subjectReport[$i]['id'])));
            if(empty($typeId)){
                $type = $this -> getTypeById($subjectReport[$i]['type_id']);
                $subjectReport[$i]['type'] = $type['name'];
            }
        }

        return $subjectReport;
    }

    /**
     * 保存科目项
     * @param $data
     * @return bool
     */
    public function saveItem($data){
        $user = session(C('USER'));
        $student_teacher = M("t_student_teacher")->where('student_id = ' . $user[C('USERID')])->find();
        $data['student_teacher_id'] = $student_teacher['id'];
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['status'] = '待导师审批';
        $result = M('t_record')->add($data);
        //刷新学生更新数量
        if (!empty($result)) {
            $count = M('t_record')
                ->where(array(
                    'status' => '待导师审批',
                    'student_teacher_id' => $student_teacher['id']
                ))
                ->count();
            M('t_student')->where(array('user_id' => $user[C('USERID')]))->save(array('updated_count' => $count));
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除科目项
     * @param $id
     * @return bool
     */
    public function deleteItem($id){
        $user = session(C('USER'));
        $file = M('t_record') -> where('id ='.$id) -> find();
        if(!empty($file['url'])){
            unlink(C("UPLOAD_LOCAL_SITE_SIMPLE").$file['url']);
        }
        $student_teacher = M("t_student_teacher")->where('student_id = ' . $user[C('USERID')])->find();
        $result = M('t_record')
            ->where(array(
                'id' => $id,
                'status' => '待导师审批',
                'student_teacher_id' => $student_teacher['id']
            ))
            ->delete();
        if (!empty($result)) {
            M('t_student')->where(array('user_id' => $user[C('USERID')]))->setDec('updated_count');
            return true;
        } else {
            return false;
        }
    }

    /**
     * 清除消息红点提示
     */
    public function clearMessageTips(){
        $user = session(C('USER'));
        M('t_student')->where('user_id =' . $user[C('USERID')])->save(array('feedback_count' => '0'));
    }

    /**未读消息数量
     * @return mixed
     */
    public function getFeedbackCount(){
        $user = session(C('USER'));
        $student = M('t_student')->field('feedback_count')->where('user_id = ' . $user[C('USERID')])->find();
        $feedback = ($student['feedback_count'] == null) ? 0 : $student['feedback_count'];
        return $feedback;
    }

    /**
     * 获取所有当前用户的学生上传记录
     * @param array $where
     * @return mixed
     */
    public function getRecord($where = array()){
        $user = session(C('USER'));
        $where['t_student_teacher.student_id'] = $user[C('USERID')];
        $record = M('t_student_teacher')
            ->join('t_student on t_student_teacher.student_id = t_student.user_id')
            ->join("t_record on t_record.student_teacher_id = t_student_teacher.id")
            ->order('t_record.created_at desc')
            ->where($where)
            ->select();
        return $record;
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
}