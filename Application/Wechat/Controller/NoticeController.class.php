<?php
namespace Wechat\Controller;


use Think\Controller;

class NoticeController extends Controller
{
    //通知列表
    public function lists(){
        $Document = M('Document');
        $condition = array('category_id'=>40);
        $list = $Document->where($condition)->select();
        $this->assign('list',$list);
        $this->display('index');
    }
    //通知详情
    public function detail($id = 0){
        $Detail = M('DocumentArticle');   //文章内容
        $Info = M('Document');  //文章基本信息
        $User = M('Member');  //用户
        $content = $Detail->find($id);
        $info_content = $Info->find($id);
        $name_info = $User
            ->join("document ON document.uid = member.uid")
            ->find(['member.uid'=>$info_content['uid']]);
        $this->assign('content',$content);
        $this->assign('info',$info_content);
        $this->assign('name_info',$name_info['nickname']);
        $this->display('detail');
    }
    //浏览量
    public function view(){
        if(!$_GET['id']){
            return;
        }
        //修改浏览量
        M('Document')->where("id = '{$_GET['id']}'")->setInc('view');
    }
    //加载更多
    public function index(){
        //每页显示条数
        $page_data_count = 2;
        //获取所有状态  >-1 通知信息
        $Data = M('Document'); // 实例化Data数据对象  data 是你的表名
        //将时间小于现在的状态改为-1(删除通知)
        $Data->where(array('deadline'=>array('lt',time())))->setField('status',-1);
        if(IS_POST){
            //获取起始值 ajax提交的
            $start = I('post.start');
            //加上field  指定查询的字段，否则会以picture的id为准
            $list = $Data
                ->field('document.*,picture.path')
                ->join("picture on picture.id = document.cover_id")
                ->where(array('document.status'=>array('gt',-1),'document.category_id'=>40,'document.deadline'=>array('gt',time())))
                ->order('document.id')
                ->limit($start,$page_data_count)
                ->select();
            $this->ajaxReturn ($list,'JSON');
        }else{
            $document = array('status'=>array('gt',-1),'category_id'=>40,'deadline'=>array('gt',time()));
            $list = $Data->where($document)->order('id')->limit(0,$page_data_count)->select();
            $this->assign('list',$list);// 赋值数据集
            $this->display('index'); // 输出模板
        }
    }
}