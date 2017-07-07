<?php

namespace Admin\Model;


use Think\Model;

class RentalModel extends Model
{
    protected $_validate = array(
        array('publisher', 'require', '发布者不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('tel', 'require', '发布者电话不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('type', 'require', '类型不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('price', 'require', '价格不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('tel', '/^1[34578]\d{9}$/' , '请输入正确的号码'),
    );

    protected $_auto = array(
        array('create_at', NOW_TIME, self::MODEL_INSERT),
        array('update_at', NOW_TIME, self::MODEL_BOTH),
        array('status', '1', self::MODEL_INSERT),
    );

}