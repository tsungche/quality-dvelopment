<?php

/**
 * 连接本地的数据库配置文件
 * 注意，DB_HOST不能够写localhost，得写127.0.0.1
 */
$dbConfig = array(
    'DB_TYPE' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'sztz',
    'DB_USER' => 'root',
    'DB_PWD' => '',
    'DB_PORT' => '3306',
    'DB_PREFIX' => '',
);

/**
 * app默认配置修改
 */
$appConfig = array(
    // 调试页
    'SHOW_PAGE_TRACE' =>false,

    // 默认模块
    'MODULE_ALLOW_LIST' => array('Main'),
    'DEFAULT_MODULE' => 'Main',

    //设置session的过期时间,以及session生效的域名
    'SESSION_OPTIONS' => array(
//        'use_trans_sid' => 1,
//        'use_cookies' => 1,
//        'use_only_cookies'=>0,
        'expire' => 3600, //秒
    ),

    //注：如果空间不支持伪静态，千万不能把URL_MODEL的值设置为2，不然会打不开网站
    'URL_MODEL' => 2,//REWRITE模式

    //如果设置伪静态后缀为空，则可以支持所有的静态后缀，并且会记录当前的伪静态后缀到常量 __EXT__ ，但不会影响正常的页面访问。
    'URL_HTML_SUFFIX'=>'',

    //每页显示的条数
    'LIMITITEM' => 10,

);

/**
 * 文件上传
 */
$fileUpload = array(
    "UPLOAD_LOCAL_SITE_SIMPLE" => './Uploads', //缺少斜杠的根目录
    "UPLOAD_LOCAL_SITE" => './Uploads/', //根目录
    "UPLOAD_STATIC_SITE" => __ROOT__ .'/Uploads', //反射地址
    "UPLOAD_ROOT" => __ROOT__, //根地址
    "UPLOAD_SIZE" => 31457280, //限制大小
);

/**
 * 文件下载
 */
$fileDownload = array(
    "DOWNLOAD_LOCAL_SITE" => "./Template/",
);

/**
 * app用户变量
 */
$appUser = array(
    'USER'           =>    'USER',//用户登录记录session,存储为用户信息array session(C('USER'))
    'USERID'         =>    'USERID',//用户ID
    'NAME'           =>    'NAME',//用户名
    'PROFESSION'     =>    'PROFESSION',//专业
    'CLASS'          =>    'CLASS',//班别
    'GRADE'          =>    'GRADE',//年级
    'MOBILE'          =>    'MOBILE',//联络方式
    'USERTYPE'       =>    'USERTYPE',//用户类型
    'TEACHERID'      =>    'TEACHERID',//导师ID
    'TEACHERNAME'    =>    'TEACHERNAME',//导师名称
    'TEACHERMOBILE'  =>    'TEACHERMOBILE',//导师联系电话
    'REQUIREDPOINT'  =>    'REQUIREDPOINT', //所需学分
    'ACHIEVEPOINT'  =>    'ACHIEVEPOINT', //已修学分

    //用户类型key
    'STUDENT' => 'student', //学生
    'TEACHER' => 'teacher', //教师
    'ADMINISTRATOR' => 'administrator', //管理员
    'STUDENTADMIN' => 'studentAdmin', //学生工作处管理员
    'TEACHERADMIN' => 'teacherAdmin', //学院教指委管理员
    'SUPERADMIN' => 'superAdmin'  //超级管理员
);

return array_merge($dbConfig,$appConfig,$fileUpload,$fileDownload,$appUser);
