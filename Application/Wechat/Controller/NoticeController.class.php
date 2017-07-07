<?php
namespace Wechat\Controller;


use Think\Controller;

class NoticeController extends Controller
{
    //通知列表
    public function index(){
        $Document = M('Document');
        $condition = array('category_id'=>40);
        $list = $Document->where($condition)->select();
        $this->assign('list',$list);
        $this->display('index');
    }
    //通知详情
    public function detail($id = 0){
        $Detail = M('DocumentArticle');
        $Info = M('Document');
        $User = M('UcenterMember');
        $content = $Detail->find($id);
        $info_content = $Info->find($id);
        $name = $User->find('id='.$Info->uid)->username;
        $this->assign('content',$content);
        $this->assign('info',$info_content);
        $this->assign('name',$name);
        $this->display('detail');
    }
}