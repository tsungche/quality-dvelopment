<?php
namespace Main\Service;

class IndexService
{

    /**
     * 是否已登录
     * @param string $type
     * @return bool
     */
    public function hasLogin($type = "all"){
        $user = session(C('USER'));
        //user是否存在session中
        if(!empty($user)){
            //类型是否所规定的类型
            if($type == "all" || $type == $user[C("USERTYPE")]){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**获取模块首页
     * @return bool|string
     */
    public function getIndex(){
        $userInfo = session(C('USER'));
        switch($userInfo['USERTYPE']){
            case C('STUDENT'):
                return 'Student/index';
            case C('TEACHER'):
                return 'Teacher/index';
            case C('TEACHERADMIN'):
                return 'TeacherAdmin/index';
            case C('STUDENTADMIN'):
                return 'StudentAdmin/index';
            case C('SUPERADMIN'):
                return 'TeacherAdmin/index';
            default:
                return false;
        }
    }

    /**
     * 获取登录日志
     * @return mixed
     */
    public function getLoginNote(){
        $user = session(C("USER"));
        return M("t_user_control")
            -> where(array(
                "user_id" => $user[C('USERID')],
                "name" => "登录系统"
            ))
            -> order("created_at desc")
            -> find();
    }

    /**
     * 获取最新通知
     * @return mixed
     */
    public function getNotice(){
        return M("t_notice")
            -> order("updated_at desc")
            -> find();
    }

    /**
     * 更新用户密码
     * @param $data
     * @return mixed
     */
    public function updatePassword($data){
        if($data['newPassword'] == $data['resetPassword']){
            $user = session(C("USER"));
            $oldUser = M("t_user")
                -> where(array(
                    "id" => $user[C("USERID")]
                ))
                ->find();
            if($oldUser['password'] ==  Md5($data['oldPassword'])){
                $newUser = M("t_user")
                    -> where(array(
                        "id" => $user[C("USERID")]
                    ))
                    ->save(array(
                        "password" => Md5($data['newPassword']),
                    ));
                $result['status'] = true;
                $result['msg'] = $newUser;
            }else{
                $result['status'] = false;
                $result['msg'] = "原密码输入不正确";
            }
        }else{
            $result['status'] = false;
            $result['msg'] = "两次输入的密码不一致";
        }
        return $result;
    }

    /**获取用户消息
     * @return mixed
     */
    public function getMessage(){
        $user = session(C('USER'));
        $message = M('t_user_message')->where('user_id =' . $user[C('USERID')])->order('created_at desc')->select();
        return $message;
    }
}