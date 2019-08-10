<?php
/**
 * Created by PhpStorm.
 * User: hehongbo
 * Date: 2018/2/23
 * Time: 下午9:23
 */

namespace  model;

class Question extends \Mysql\Crud
{
    protected  $table = 'ms_question';
    protected  $pk = 'id';

    /**
     * 分页查询， 不需要条件
     *
     * @param int $start 分页开始偏移位置
     * @param int $end 分页结束偏移位置
     * @return mixed 列表
     */
    public  function  getList($start = 0, $end = 50)
    {
        $start = max(0, $start);
        $end   = min(50, $end);
        $sql = "SELECT * FROM `"  .$this->table . "` ORDER BY `$this->pk` DESC LIMIT $start, $end";
        return $this->getDb()->query($sql);
    }


    public  function  getActiveQuestion($aid)
    {
        $sql = "SELECT * FROM `"  . $this->table. "` WHERE active_id='" . $aid."' AND sys_status=0 LIMIT 1";
        return $this->getDb()->row($sql);
    }
}