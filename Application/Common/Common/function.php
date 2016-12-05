<?php

    /**
     * 分页处理
     * @param $control:控制器方法
     * @param $sumCount:总条数
     * @param $page:当前页数
     * @param $parameter:跳转参数
     * @return string:html+css+jquery
     */
    function getPageHtml($control,$sumCount,$page,$parameter = array()){

        $oldParameter = $parameter;
        $maxPage = ceil($sumCount/C("LIMITITEM")); //向上取整
        $ULPage = $page+3;
        $LSLPage = $page-3;
        $UL = $maxPage-1;
        $LSL = 2;

        $pageHtml = "";

        if($page > 1){ //上一页
            $parameter['page'] = ($page-1);
            $pageHtml .=  "<a href='" . U($control,$parameter) ."'><</a>";
        }
        if($maxPage <= 9){ //不需要省略
            for($i = 1;$i <= ($maxPage);$i++){
                $parameter['page'] = $i;
                if($page == $i){ //标记当前页
                    $pageHtml .=  "<a class='active' href='" . U($control,$parameter) ."'>".$i."</a>";
                }else{
                    $pageHtml .=  "<a href='" . U($control,$parameter) ."'>".$i."</a>";
                }
            }
        }else{
            if($LSLPage > $LSL){ //如果当前位置需要省略，则输出该页之前的未省略部分
                $parameter['page'] = 1;
                $pageHtml .=  "<a href='" . U($control,$parameter) ."'>1</a>";
                $pageHtml .=  "<a class='except'>···</a>";
                for($i = $LSLPage;$i < $page;$i++){
                    $parameter['page'] = $i;
                    $pageHtml .=  "<a href='" . U($control,$parameter) ."'>".$i."</a>";
                }
            }elseif($page > 1){ //如果当前位置不需要省略,则全部输出
                for($i = 1;$i < $page;$i++){
                    $parameter['page'] = $i;
                    $pageHtml .=  "<a href='" . U($control,$parameter) ."'>".$i."</a>";
                }
            }
            $parameter['page'] = $page;
            $pageHtml .=  "<a class='active' href='" . U($control,$parameter) ."'>".$page."</a>";  //输出当前页面
            if($ULPage < $UL){ //如果当前位置需要省略，则输出该页之后的未省略部分
                for($i = ($page+1);$i <= $ULPage;$i++){
                    $parameter['page'] = $i;
                    $pageHtml .=  "<a href='" . U($control,$parameter) ."'>".$i."</a>";
                }
                $pageHtml .=  "<a class='except'>···</a>";
                $parameter['page'] = $maxPage;
                $pageHtml .=  "<a href='" . U($control,$parameter) ."'>".$maxPage."</a>";
            }elseif($page < $maxPage){ //如果当前位置不需要省略,则全部输出
                for($i = ($page+1);$i <= $maxPage;$i++){
                    $parameter['page'] = $i;
                    $pageHtml .=  "<a href='" . U($control,$parameter) ."'>".$i."</a>";
                }
            }
        }
        if($page < $maxPage){ //下一页
            $parameter['page'] = ($page+1);
            $pageHtml .=  "<a href='" . U($control,$parameter) ."'>></a>";
        }
        $pageHtml .=  "<span>共".$sumCount."条</span>"; //总条数

        //跳转页码
        $pageHtml .=  "<div class='search'><span>跳至</span><select class='searchPage' name='key'><option value='false'></option>";
        for($i = 1;$i <= $maxPage;$i++){
            $pageHtml .=  "<option value='".$i."'>".$i."</option>";
        }
        $pageHtml .=  "</select><span>页</span></div>";

        //css
        $pageHtml .= "<style>.page{margin-top:20px;text-align:center;cursor:default}.page a{min-width:30px;line-height:28px;display:inline-block;padding:0 5px;border:1px solid #aaa;-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;margin:5px 2px;text-align:center;font-size:14px;color:#7c7c7c}.page a:hover{background:#e0e0e0}.page .except{border:none}.page .active{background:#3199ca;color:#fff;cursor:default;border:1px solid #069eb6}.page .active:hover{background:#3199ca}.page span{line-height:30px;font-size:14px;margin:0 5px;color:#aaa}.page .search{display:inline-block;margin:0 2px;text-align:center;font-size:12px;color:#7c7c7c}.page .searchPage{height:26px}</style>";

        //js
        $pageHtml .=  "<script type='text/javascript'>var subjectUrl = '".U($control,$oldParameter)."';$('.searchPage').change(function(){ if($(this).val() != 'false'){var url = subjectUrl+'/page/'+ $(this).val();window.location.href = url;}});</script>";

        return $pageHtml;
    }

