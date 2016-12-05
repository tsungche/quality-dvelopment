<?php
namespace Main\Service;

use Think\Exception;

class StudentAdminService{

    /**
     * 获取所有当前用户的学生上传记录
     * @param $page
     * @param array $where
     * @return mixed
     */
    public function getRecord($page,$where = array()){
        $user = session(C('USER'));
        $where['t_record.updated_by'] = $user[C('NAME')];
        $record = M('t_student_teacher')
            ->join('t_student on t_student_teacher.student_id = t_student.user_id')
            ->join("t_record on t_record.student_teacher_id = t_student_teacher.id")
            ->order('t_record.created_at desc')
            ->where($where)
            ->page($page,C("LIMITITEM"))
            ->select();
        return $record;
    }

    /**
     * 获取公益劳动记录
     * @param $page
     * @return mixed
     */
    public function getVoluntary($page){
        $user = session(C('USER'));
        $type = M("t_type")->where(array("name" => "公益劳动"))->find();
        $voluntary = $this -> getRecord($page,array(
            "t_record.type_id" => $type['id']
        ));
        for ($i = 0; $i < count($voluntary); $i++) {
            $voluntary[$i]["type"] = "公益劳动";
            $voluntary[$i]["file_url"] = C("UPLOAD_STATIC_SITE").$voluntary[$i]["url"];
            $voluntary[$i]["delete_val"] = base64_encode($voluntary[$i]['id']);
            $voluntary[$i]["detail_url"] = U('StudentAdmin/detail', array('id' => base64_encode($voluntary[$i]['id'])));
        }
        $data['list'] = $voluntary;

        //分页
        $data['sumCount'] = M('t_student_teacher')
            ->join('t_student on t_student_teacher.student_id = t_student.user_id')
            ->join("t_record on t_record.student_teacher_id = t_student_teacher.id")
            ->where(array(
                "t_record.type_id" => $type['id'],
                "t_record.updated_by" => $user[C('NAME')]
            ))
            ->count();

        $data["pageHtml"] = getPageHtml('StudentAdmin/subject',$data['sumCount'],$page);
        return $data;
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
                't_record.type_id' => $type_id
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
     * 获取公益劳动学分报表
     * @param string $studentId
     * @return mixed
     */
    public function getVoluntaryReport($studentId){
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
                't_type.name' => '公益劳动',
                't_type_grade_profession.grade' => $student["grade"],
                't_type_grade_profession.profession' => $student["profession"]
            ))
            ->find();
        $typeList['sum_point'] = $this->getSumPoint($typeList['type_id'],$student['user_id']);
        return $typeList;
    }

    /**
     * 保存材料项
     * @param $data
     * @return mixed
     */
    public function saveItem($data){
        if(!empty($data['url']) && !empty($data['title']) && !empty($data['user_id'])){
            if($data['point'] >= 0) {
                $student = $this->getStudent($data['user_id']);
                if (!empty($student)) {
                    $voluntary = $this->getVoluntaryReport($student['user_id']);  //学分认定表
                    if ($voluntary['sum_point'] < $voluntary['max_point']) {
                        $sum = $voluntary['sum_point'] + $data['point'];
                        if ($sum <= $voluntary['max_point']) {
                            $user = session(C('USER'));
                            $studentTeacher = M("t_student_teacher")->where(array("student_id" => $student['user_id']))->find();
                            $recordId = M('t_record')//插入记录
                            ->add(array(
                                'student_teacher_id' => $studentTeacher['id'],
                                'type_id' => $voluntary['type_id'],
                                'title' => $data['title'],
                                'url' => $data['url'],
                                'point' => $data['point'],
                                'status' => '已通过',
                                'feedback' => '无',
                                'remark' => $data['remark'],
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'updated_by' => $user[C('NAME')],
                                'updated_at' => date('Y-m-d H:i:s', time())
                            ));
                            if ($recordId) {
                                M('t_student')->where('user_id =' . $student['user_id'])->setInc('feedback_count'); //更新学生反馈数
                                M('t_user_message')
                                    ->add(array(
                                        'user_id' => $student['user_id'],
                                        'title' => '学生工作处审批通过',
                                        'type' => '学院信息',
                                        'record_id' => $recordId,
                                        'content' => "你的 <strong style='color:#199dc6;'>公益劳动</strong> 已被 " . $user[C('NAME')] . " 审批完毕，结果为 <strong style='color:#c6c200;'>已通过</strong> ，得分： <strong style='color:#00c645;'>" . $data['point'] . "</strong> 分。",
                                        'created_by' => $user[C('NAME')],
                                        'created_at' => date('Y-m-d H:i:s', time())
                                    ));
                                $result['status'] = true;
                                $result['msg'] = '添加成功';
                            } else {
                                $result['status'] = false;
                                $result['msg'] = '添加失败';
                            }
                        } else {
                            $result['status'] = false;
                            $result['msg'] = '评分不能超过最大上限';
                        }
                    } else {
                        $result['status'] = false;
                        $result['msg'] = '该类分数已达到上限，无法再添加';
                    }
                } else {
                    $result['status'] = false;
                    $result['msg'] = '该学生不存在';
                }
            }else{
                $result['status'] = false;
                $result['msg'] = '填写正确分数';
            }
        }else{
            $result['status'] = false;
            $result['msg'] = '填写信息不完整，请检查附件';
        }
        return $result;
    }

    public function uploadItem(){
        //上传配置
        $config = array(
            'maxSize'    =>    C("UPLOAD_SIZE"),
            'rootPath'   =>    C("UPLOAD_LOCAL_SITE"),
            'savePath'   =>    '/admin/xls/',
            'saveName'   =>    time().mt_rand(),
            'exts'       =>    array("xls","xlsx"),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        // 上传文件
        $info   =   $upload->uploadOne($_FILES['u-xls']);
        if(!$info) {// 上传错误提示错误信息
            $result['status'] = false;
            $result['msg'] = $upload->getError();
        }else{// 上传成功
            $path = C("UPLOAD_LOCAL_SITE_SIMPLE").$info['savepath'].$info['savename'];
            try{
                import("Org.Util.PHPExcel");
                //要导入的xls文件，位于根目录下的Public文件夹
                $filename = $path;
                //创建PHPExcel对象，注意，不能少了\
                $PHPExcel=new \PHPExcel();

                if($info['ext'] == "xls"){
                    //如果excel文件后缀名为.xls，导入这个类
                    import("Org.Util.PHPExcel.Reader.Excel5");
                    $PHPReader=new \PHPExcel_Reader_Excel5();
                }else if($info['ext'] == "xlsx"){
                    //如果excel文件后缀名为.xlsx，导入这下类
                    import("Org.Util.PHPExcel.Reader.Excel2007");
                    $PHPReader=new \PHPExcel_Reader_Excel2007();
                }else{
                    $result['status'] = false;
                    $result['msg'] = "文件格式不符";
                    return $result;
                }

                //载入文件
                $PHPExcel=$PHPReader->load($filename);
                //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
                $currentSheet=$PHPExcel->getSheet(0);
                //获取总列数
                $allColumn=$currentSheet->getHighestColumn();
                //获取总行数
                $allRow=$currentSheet->getHighestRow();
                $result['allColumn'] = $allColumn;
                $result['allRow'] = $allRow;
                $arr = array();
                $recordList = array();
                for($currentRow = 2;$currentRow <= $allRow;$currentRow++){ //跳过第一行标题
                    //从哪列开始，A表示第一列
                    $recordItem = array();
                    $recordItem['url'] = "/test/none.pdf";
                    for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
                        //数据坐标
                        $address=$currentColumn.$currentRow;
                        //读取到的数据，保存到数组$arr中
                        $arr[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
                        switch ($currentColumn) {
                            case "A":
                                $recordItem['user_id'] = $arr[$currentRow][$currentColumn];
                                break;
                            case "B":
                                $recordItem['name'] = $arr[$currentRow][$currentColumn];
                                break;
                            case "C":
                                $recordItem['title'] = $arr[$currentRow][$currentColumn];
                                break;
                            case "D":
                                $recordItem['remark'] = $arr[$currentRow][$currentColumn];
                                break;
                            case "E":
                                $recordItem['point'] = $arr[$currentRow][$currentColumn];
                                break;
                        }
                    }
                    $recordItem['result'] = $this -> saveItem($recordItem);
                    $recordList[] = $recordItem;
                }
                $result['arr'] = $arr;
                $result['list'] = $recordList;
                $result['status'] = true;
                $result['msg'] = "上传成功";
            }catch(Exception $e){
                $result['status'] = false;
                $result['msg'] = "读取Excel失败";
            }

        }
        return $result;
    }

    /**
     * 测试展示单个Excel
     * @param $path
     * @return mixed
     * @throws \PHPExcel_Exception
     */
    public function showExcel($path){

//        $path = "./Uploads/admin/xls/20160410/14602787901735267983.xls";

        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        //要导入的xls文件，位于根目录下的Public文件夹
        $filename = $path;
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel=new \PHPExcel();

        //如果excel文件后缀名为.xls，导入这个类
        import("Org.Util.PHPExcel.Reader.Excel5");
        $PHPReader=new \PHPExcel_Reader_Excel5();

        //如果excel文件后缀名为.xlsx，导入这下类
        //import("Org.Util.PHPExcel.Reader.Excel2007");
        //$PHPReader=new \PHPExcel_Reader_Excel2007();

        //载入文件
        $PHPExcel=$PHPReader->load($filename);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet=$PHPExcel->getSheet(0);
        //获取总列数
        $allColumn=$currentSheet->getHighestColumn();
        //获取总行数
        $allRow=$currentSheet->getHighestRow();
        $result['allColumn'] = $allColumn;
        $result['allRow'] = $allRow;
        $arr = array();
        for($currentRow=1;$currentRow<=$allRow;$currentRow++){
            //从哪列开始，A表示第一列
            for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
                //数据坐标
                $address=$currentColumn.$currentRow;
                //读取到的数据，保存到数组$arr中
                $arr[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
            }
        }
        $result['arr'] = $arr;
        return $result;
    }

    /**
     * 删除科目项
     * @param $id
     * @return bool
     */
    public function deleteItem($id){
        $user = session(C('USER'));
        $file = M('t_record') -> where('id ='.$id) -> find();
        $result = M('t_record')
            ->where(array(
                'id' => $id,
                'updated_by' => $user[C('NAME')],
            ))
            ->delete();
        if (!empty($result)) {
            $student_teacher = M("t_student_teacher")->where('id = ' . $file['student_teacher_id'])->find();
            M('t_student')->where(array('user_id' => $student_teacher['student_id']))->setDec('feedback_count');
            M('t_user_message')->where(array('record_id' => $id))->delete();
            return true;
        } else {
            return false;
        }
    }

    /**
     * 批量删除
     * @param $record
     * @return array
     */
    public function deleteList($record){
        $i = 1;
        $result = array();
        foreach($record as $item){
            $flag = $this->deleteItem($item);
            if($flag){
                $result['status'] = true;
                $result['msg'] = "操作成功";
            }else{
                $result['status'] = false;
                $result['msg'] = "操作失败 " . $i++ . " 个";
            }
        }
        return $result;
    }

    /**
     * 获取类型内容
     * @param $where
     * @return mixed
     */
    public function getType($where){
        return M("t_type")
            ->where($where)
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
        M('t_administrator')->where('user_id =' . $user[C('USERID')])->save(array('feedback_count' => '0'));
    }

    /**未读消息数量
     * @return mixed
     */
    public function getFeedbackCount(){
        $user = session(C('USER'));
        $student = M('t_administrator')->field('feedback_count')->where('user_id = ' . $user[C('USERID')])->find();
        $feedback = ($student['feedback_count'] == null) ? 0 : $student['feedback_count'];
        return $feedback;
    }

    public function search($studentId){
        $report = $this -> getVoluntaryReport($studentId);
        if(!empty($report['id'])){
            $date['status'] = true;
            $date['msg'] = "目前得分：" . $report['sum_point'] . "分 ; 至少得分：" . $report['min_point'] . "分 ; 最大得分：" . $report['max_point'] ."分";
        }else{
            $date['status'] = false;
            $date['msg'] = "查询失败";
        }
        return $date;
    }

}