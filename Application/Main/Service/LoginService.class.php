<?php
namespace Main\Service;

class LoginService
{

    /**登陆验证
     * @param $data
     * @return bool|null
     */
    public function checkLogin($data){

        //获取用户信息
        $result = M('t_user')
            ->where(array(
                'id' => $data['userName'],
                'type' => $data['userType']
            ))
            ->find();
        //如果获取不到用户则用户不存在
        if (!empty($result)) {
            //如果用户密码不等于提交密码则密码错误
            if (Md5($data['password']) == $result['password']) {
                $user = null; //定义
                //如果是学生
                if ($result['type'] == C('STUDENT')) {
                    $user = M('t_student_teacher')
                        ->field(array(
                            't_student.user_id' => 'id',
                            't_student.name' => 'name',
                            't_student.clazz' => 'clazz',
                            't_student.grade' => 'grade',
                            't_student.profession' => 'profession',
                            't_student.mobile' => 'mobile',
                            't_student.required_point' => 'required_point',
                            't_teacher.user_id' => 'teacher_id',
                            't_teacher.name' => 'teacher_name',
                            't_teacher.mobile' => 'teacher_mobile'
                        ))
                        ->join('t_student on t_student_teacher.student_id = t_student.user_id')
                        ->join('t_teacher on t_student_teacher.teacher_id = t_teacher.user_id')
                        ->where('t_student.user_id = ' . $result['id'])
                        ->find();
                }//如果是教师
                elseif ($data['userType'] == C('TEACHER')) {
                    $user = M('t_teacher')
                        ->field('user_id as id,name,mobile')
                        ->where('user_id = ' . $result['id'])
                        ->find();
                }//如果是管理员
                elseif ($data['userType'] == C('ADMINISTRATOR')) {
                    $user = M('t_administrator')
                        ->field(array(
                            't_administrator.user_id' => 'id',
                            't_administrator.name' => 'name',
                            't_user_role.role' => 'role',
                        ))
                        ->join('t_user_role on t_user_role.user_id = t_administrator.user_id')
                        ->where('t_administrator.user_id = ' . $result['id'])
                        ->find();
                    $data['userType'] = $user['role'];
                }
                if(!empty($user)){
                    $this->updateSession($user, $data['userType']);
                    $this->writeNote($user);
                    return true;
                } else {
                    //排除未知错误
                    return null;
                }

            } else {
                return false;
            }
        } else {
            return null;
        }

    }

    /**填充session
     * @param $user
     * @param $type
     */
    public function updateSession($user, $type)
    {
        if ($type == C('STUDENT')) {
            $userSession = array(
                C('USERID') => $user['id'],
                C('NAME') => $user['name'],
                C('PROFESSION') => $user['profession'],
                C('CLASS') => $user['clazz'],
                C('GRADE') => $user['grade'],
                C('MOBILE') => $user['mobile'],
                C('USERTYPE') => $type,
                C('TEACHERID') => $user['teacher_id'],
                C('TEACHERNAME') => $user['teacher_name'],
                C('TEACHERMOBILE') => $user['teacher_mobile'],
                C('REQUIREDPOINT') => $user['required_point']
            );
        } else if ($type == C('TEACHER')) {
            $userSession = array(
                C('USERID') => $user['id'],
                C('NAME') => $user['name'],
                C('USERTYPE') => $type,
                C('MOBILE') => $user['mobile']
            );
        } else {
            $userSession = array(
                C('USERID') => $user['id'],
                C('NAME') => $user['name'],
                C('USERTYPE') => $type
            );
        }
        session(C('USER'), $userSession);
    }

    /**
     * 记录登录日记
     * @param $user
     */
    public function writeNote($user)
    {
        $data['user_id'] = $user['id'];
        $data['name'] = '登录系统';
        $data['user_ip'] = GetHostByName($_SERVER['SERVER_NAME']);
        $data['created_by'] = $user['name'];
        $data['created_at'] = date('Y-m-d H:i:s', time());
        M('t_user_control') -> add($data);
    }
}