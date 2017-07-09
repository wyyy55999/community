<?php
namespace Wechat\Controller;


use Think\Controller;
use User\Api\UserApi;

class WechatController extends Controller
{
    //登录
    public function login($username = '', $password = '', $verify = ''){
        if(IS_POST){  //登录验证
            /* 检测验证码 */
            if(!check_verify($verify)){
                $this->error('验证码输入错误！');
            }
            /* 调用UC登录接口登录 */
            $user = new UserApi();
            $uid = $user->login($username, $password);
            if(0 < $uid){ //UC登录成功
                /* 登录用户 */
                $Member = D('Member');
                if($Member->login($uid)){ //登录用户
                    //将用户信息存入session
                    session('current_user_id',$uid);
                    $url = session('request_url',$_SERVER['REQUEST_URI']);
                    //跳转到登录前页面
                    if($url==null){
                        $this->success('登录成功！',U('Wechat/Wechat/index'));
                    }else{
                        $this->success('登录成功！',U($url));
                    }
                } else {
                    $this->error($Member->getError());
                }
            } else { //登录失败
                switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }

        }
        $this->display('login');
    }

    //注册
    public function register($username = '', $password = '', $repassword = '', $email = '', $verify = ''){
        if(IS_POST){ //注册用户
            /* 检测验证码 */
            if(!check_verify($verify)){
                $this->error('验证码输入错误！');
            }

            /* 检测密码 */
            if($password != $repassword){
                $this->error('密码和重复密码不一致！');
            }

            /* 调用注册接口注册用户 */
            $User = new UserApi();
            $uid = $User->register($username, $password, $email);
            if(0 < $uid){ //注册成功
                $this->success('注册成功！',U('login'));
            } else { //注册失败，显示错误信息
                $this->error($this->showRegError($uid));
            }

        } else { //显示注册表单
            $this->display('register');
        }
    }

    //验证码，用于登录和注册
    public function verify(){
        $verify = new \Think\Verify();
        $verify->entry(1);
    }

    //退出登录
    public function logout(){
        if(is_login()){
            D('Member')->logout();
            session('current_user_id',null);
            $this->success('退出成功！', U('Wechat/login'));
        } else {
            $this->redirect('Wechat/login');
        }
    }

    //我的
    public function my(){
        if ( !is_login() ) {
            $this->error( '您还没有登陆',U('Wechat/login') );
        }else{
            echo '我的';
        }
    }

    //服务
    public function server(){
        if ( !is_login() ) {
            $this->error( '您还没有登陆',U('Wechat/login') );
        }else{
            $this->display('Server/index');
        }
    }

    //前台显示页面
    public function index(){
        $this->display('index');
    }

    //检测是否登录
    public function check(){
        if ( !is_login() ) {
            $this->error( '您还没有登陆',U('Wechat/login') );
        }
    }

    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0){
        switch ($code) {
            case -1:  $error = '用户名长度必须在16个字符以内！'; break;
            case -2:  $error = '用户名被禁止注册！'; break;
            case -3:  $error = '用户名被占用！'; break;
            case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
            case -5:  $error = '邮箱格式不正确！'; break;
            case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
            case -7:  $error = '邮箱被禁止注册！'; break;
            case -8:  $error = '邮箱被占用！'; break;
            case -9:  $error = '手机格式不正确！'; break;
            case -10: $error = '手机被禁止注册！'; break;
            case -11: $error = '手机号被占用！'; break;
            default:  $error = '未知错误';
        }
        return $error;
    }
}