<?php

namespace Wechat\Controller;

use Think\Controller;

class RentalController extends Controller
{
    //显示出租/售列表页
    public function rentals(){
        $Rental = M('Rental');  //实例化Data数据对象
        $condition1 = array('status'=>0);
        $list1 = $Rental->where($condition1)->select();
        $condition2 = array('status'=>1);
        $list2 = $Rental->where($condition2)->select();
        $this->assign('rental',$list1);
        $this->assign('sale',$list2);
        $this->display('list');
    }
    //租售详情
    public function detail($id = 0){
        $detail = array();
        $Rental = M('Rental');
        $detail = $Rental->find($id);
        if($detail === false){
            $this->error('暂无该信息');
        }
        $this->assign('detail',$detail);
        $this->display('detail');
    }
}