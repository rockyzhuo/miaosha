<?php

/**
 * 活动信息管理页
 * 
 */
include 'init.php';
$refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/admin';
$TEMPLATE['refer'] = $refer;
$TEMPLATE['type']  = 'active';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

$active_model = new \model\Active();
if('list' == $action) 
{
	$page = getReqInt('page','get', 1);
	$size = 20;
	$offset = ($page -1) * $size;
	$data_list = $active_model->getList($offset, $size);
	$TEMPLATE['data_list'] = $data_list;
	$TEMPLATE['pageTitle']  = '活动管理';
	include TEMPLATE_PATH . '/admin/active_list.php';
} 
else if('edit' == $action)
{
	// 新增与编辑
	$id = getReqInt('id','get',0);
	if($id)
	{
		$data = $active_model->get($id);
		$data['time_begin'] = date("Y-m-d H:i:s", $data['time_begin']);
		$data['time_end'] = date("Y-m-d H:i:s", $data['time_end']);
	}
	else
	{
		$data = [
			'id' => 0,
			'title' => '',
			'time_begin' => '',
			'time_end' => '',
		];
	}
	$TEMPLATE['data'] = $data;
	$TEMPLATE['pageTitle'] = '编辑活动信息-活动管理';
	include TEMPLATE_PATH . '/admin/active_edit.php';
} 
else if ('save' == $action)
{
	$info = $_POST['info'];
	$info['title']      = addslashes($info['title']);
	$info['time_begin'] = strtotime($info['time_begin']);
	$info['time_end']   = strtotime($info['time_end']);
	foreach ($info as $k => $v) 
	{
		$active_model->$k =$v;
	}
	if($info['id'])
	{
		$active_model->sys_lastmodify = time();
		$ok = $active_model->save();
        $id = $info['id'];
	}
	else
	{
		$active_model->sys_lastmodify = $active_model->sys_dateline  = time();
		$active_model->sys_ip = getClientIp();
        $ok = $active_model->create();
        $id = $ok;
	}
	if($ok)
	{
        // 增加与修改的时候需要将活动信息保存的Redis中

        $now = time();
        if($info['time_end'] > $now)
        {
            $redis_obj =  \common\Datasource::getRedis('instance1');
            // 设置Redis key  : miaosha:string:st_a_ +  活动ID
            $remain_time =  $info['time_end'] - $now;
            $redis_key = 'miaosha:string:st_a_'. $id;
            $info = json_encode($info);
            $redis_obj->set($redis_key, $info, $remain_time);
        }
        redirect('active.php');
	}
	else
	{
		echo '<script>alert("数据保存失败");history.go(-1);</script>';
	}
}
else if ('delete' === $action) // 下线
{
	$id = getReqInt('id','get',0);
    $ok = false;
    if($id)
    {
        $active_model->id = $id;
        $active_model->sys_status = 2;
        $active_model->sys_lastmodify = time();
        $ok = $active_model->save($id);
    }
    if($ok)
    {
        $redis_obj =  \common\Datasource::getRedis('instance1');
        // 设置Redis key  : miaosha:string:info_g_+  互动ID
        $redis_key = 'miaosha:string:st_a_'. $id;
        $info = $redis_obj->get($redis_key);
        $info = json_decode($info,1);
        $info['sys_status'] = 2;
        $info['sys_lastmodify'] = time();
        $info = json_encode($info);
        $redis_obj->set($redis_key, $info);

        redirect($refer);
    }
    else
    {
        show_result("下线时候出现错误", $refer);
    }
}
else if ('reset' == $action) // 上线
{
    $id  = getReqInt('id','get',0);
    $ok = false;
    if($id)
    {
        $active_model->id = $id;
        $active_model->sys_status = 1;
        $active_model->sys_lastmodify = time();
        $ok = $active_model->save($id);
    }
    if($ok)
    {
        $data = $active_model->get($id);
        $redis_obj =  \common\Datasource::getRedis('instance1');
        // 设置Redis key  : miaosha:string:info_g_+  互动ID
        $redis_key = 'miaosha:string:st_a_'. $id;
        $info = $redis_obj->get($redis_key);
        $info = json_decode($info,1);
        $info['sys_status'] = 1;
        $info['time_begin'] = $data['time_begin'];
        $info['time_end'] = $data['time_end'];
        $info['sys_lastmodify'] = time();
        $info = json_encode($info);
        $redis_obj->set($redis_key, $info);
        redirect($refer);
    }
    else
    {
        show_result("上线时候出现错误", $refer);
    }
}
else
{
    echo 'error active action';
}





