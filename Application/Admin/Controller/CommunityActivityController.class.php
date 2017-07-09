<?php

namespace Admin\Controller;


use Think\Page;

class CommunityActivityController extends AdminController
{
    //小区活动列表
    public function index(){
        $Model = M('CommunityActivity');
        import('ORG.Util.Page');// 导入分页类
        $count = $Model->count();
        $Page = new Page($count,10);// 实例化分页类 传入总记录数
        $show = $Page->show();// 分页显示输出
        $list = $Model
            ->field('member.nickname,document.title,document.description,document.deadline,community_activity.*')
            ->join('document on document.id = community_activity.act_id')
            ->join('member on member.uid = community_activity.uid')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();
        $this->assign('list',$list);
        $this->assign('page',$show);// 赋值分页输出
        $this->display('index');
    }
    //审核通过
    public function audit(){
        $id = I('get.id');
        $Model = M('CommunityActivity');
        $act = $Model->where(array('id'=>$id))->setField('status',1);
        if($act){
            $this->success('审核已通过',U('CommunityActivity/index'));
        }
    }
    //审核不通过
    public function refuse(){
        $id = I('get.id');
        $Model = M('CommunityActivity');
        $act = $Model->where(array('id'=>$id))->setField('status',-1);
        if($act){
            $this->success('已驳回该审核',U('CommunityActivity/index'));
        }
    }
}