<?php include  TEMPLATE_PATH . "/admin/common/header.php"; ?>
<?php include  TEMPLATE_PATH . "/admin/common/navigation.php"; ?>

<div class="container-fluid">
	<a href="?action=edit" class="btn btn-primary">添加活动</a>

	<?php if ($TEMPLATE['data_list']): ?>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>活动名称</th>
					<th>开始时间</th>
					<th>结束时间</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($TEMPLATE['data_list'] as $key => $data): ?>
					<tr>
						<td><?php echo $data['id']; ?></td>
						<td><?php echo htmlspecialchars($data['title']); ?></td>
						<td><?php echo date("Y-m-d H:i:s", $data['time_begin']) ?></td>
						<td><?php echo date("Y-m-d H:i:s", $data['time_end']) ?> </td>
						<td><?php echo $arr_active_status[$data['sys_status']] ?></td>
						<td>
							<a href="?action=edit&id=<?php echo $data['id'];  ?>">编辑</a>
							<?php if ($data['sys_status'] === '1'): ?>
							| <a href="?action=delete&id=<?php echo $data['id']; ?>">下线</a>
							<?php else: ?>
							| <a href="?action=reset&id=<?php echo $data['id']; ?>">上线</a>
							<?php endif ?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	<?php else: ?>
		<center>
			暂时还没有活动信息，现在就来<a href="?action=edit">添加活动</a>
		</center>
	<?php endif ?>
	
</div>

<iframe name="iframe_inner_post" src="about:blank" style="display:none;"></iframe>
<?php include TEMPLATE_PATH . "/admin/common/footer.php"; ?>
