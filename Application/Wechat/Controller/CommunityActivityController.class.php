<?php
namespace Wechat\Controller;


use Think\Controller;

class CommunityActivityController extends Controller
{
    //活动详情
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
    //活动列表 和 加载更多
    public function index(){
        //每页显示条数
        $page_data_count = 2;
        //获取所有状态  >-1 通知信息
        $Data = M('Document'); // 实例化Data数据对象  data 是你的表名
        //将时间小于现在的状态改为-1(删除活动)
        $Data->where(array('deadline'=>array('lt',time())))->setField('status',-1);
        if(IS_POST){
            //获取起始值 ajax提交的
            $start = I('post.start');
            //加上field  指定查询的字段，否则会以picture的id为准
            $list = $Data
                ->field('document.*,picture.path')
                ->join("picture on picture.id = document.cover_id")
                ->where(array('document.status'=>array('gt',-1),'document.category_id'=>43,'document.deadline'=>array('gt',time())))
                ->order('document.id')
                ->limit($start,$page_data_count)
                ->select();
            $this->ajaxReturn ($list,'JSON');
        }else{
            $document = array('status'=>array('gt',-1),'category_id'=>43,'deadline'=>array('gt',time()));
            $list = $Data->where($document)->order('id')->limit(0,$page_data_count)->select();
            $this->assign('list',$list);// 赋值数据集
            $this->display('index'); // 输出模板
        }
    }
    //审核
    public function audit(){
        if (session('current_user_id') == null){
            $this->ajaxReturn (array('sta'=>-1,'msg'=>'您还没有登陆，请先登录'),'JSON');
        }else{
            $act_id = I('get.act_id');
            $Model = M('CommunityActivity');
            $act = $Model->where(array('act_id'=>$act_id,'uid'=>session('current_user_id')))->select();
            if($act){
                $this->ajaxReturn (array('sta'=>0,'msg'=>'您已报名过该活动，请勿重复报名'),'JSON');
            }else{
                $activities = [];
                $activities['uid'] = session('current_user_id');
                $activities['act_id'] = $act_id;
                $activities['app_time'] = time();
                $activities['status'] = 0;
                if($Model->add($activities)){
                    $this->ajaxReturn (array('sta'=>1,'msg'=>'报名成功，请等待审核结果'),'JSON');
                }else{
                    $this->ajaxReturn (array('sta'=>0,'msg'=>'报名失败，请重试'),'JSON');
                }
            }
        }
    }
}