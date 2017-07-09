<?php

namespace Admin\Controller;


use Think\Page;

class RentalController extends AdminController
{
    //分页显示列表页
    public function index(){
        //获取所有状态大于-1的租赁信息
        $Data = M('Rental'); // 实例化Data数据对象  date 是你的表名
        import('ORG.Util.Page');// 导入分页类
        $rental = array('status'=>array('gt',-1));
        $count = $Data->where($rental)->count();// 查询满足要求的总记录数 $map表示查询条件
        $Page = new Page($count,5);// 实例化分页类 传入总记录数
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询  // $Page->firstRow 起始条数 $Page->listRows 获取多少条
        $list = $Data->where($rental)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('index'); // 输出模板
    }
    //新增租售信息
    public function add(){
        if(IS_POST){
            $Rental = D('Rental');
            $data = $Rental->create();
            if($data){
                $res = $Rental->add();
                if($res){
                    $this->success('新增成功啦',U('index'));
                }else{
                    $this->error('新增失败');
                }
            }else{
                $this->error($Rental->getError());
            }
        }else{
            $this->assign('info',null);
            $this->display('edit');
        }
    }
    //修改租售信息
    public function edit($id = 0){
        if(IS_POST){
            $Rental = D('Rental');
            $data = $Rental->create();
            if($data){
                if($Rental->save()){
                    $this->success('修改成功啦',U('index'));
                }else{
                    $this->error('修改失败');
                }
            }else{
                $this->error($Rental->getError());
            }
        }else{
            $info = array();
            //获取数据
            $info = M('Rental')->find($id);
            if($info === false){
                $this->error('暂无该信息');
            }
            $this->assign('info',$info);
            $this->display('edit');
        }
    }
    //删除租售信息
    public function del(){
        $id = array_unique((array)I('id',0));
        if(empty($id)){
            $this->error('请选择要操作的数据');
        }
        $map = array('id'=>array('in',$id));
        if(M('Rental')->where($map)->delete()){
            $this->success('删除成功',U('index'));
        }else{
            $this->error('删除失败！');
        }
    }
    //上传图片
    public function uploadify(){
        if (!empty($_FILES)) {
            import("@.Think.UploadFile");
            $upload = new \Think\Upload();
            $upload->rootPath  = './Uploads/Picture/room/';//根路径
            $upload->savePath = date('Y').'/'.date('m').'/'.date('d').'/';//子路径，文件夹自动分级好点，不然文件太多了数量大了以后不好找图片
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');//可以上传的文件类型
            $upload->autoSub = false;
            $upload->saveRule = uniqid; //上传规则，文件名会自动重新获取，这样保证文件不会被覆盖
            $info = $upload->upload();
            if(!$info){
                echo $this->error($upload->getError());//获取失败信息
            } else {
                //成功
                $fileArray = "";
                foreach ($info as $file) {
                    //返回文件在服务器上的路径
                    $fileArray = $upload->rootPath . $file['savepath'] . $file['savename'];
                }
                echo trim(ltrim($fileArray,'.'));
            }
        }
    }
}