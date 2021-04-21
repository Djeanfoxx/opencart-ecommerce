<div class="button_action">
	<div class="btn-group">
		<button aria-expanded="false" data-toggle="dropdown" class="btn btn-warning dropdown-toggle" type="button">
			<i class="fa fa-trash-o"></i> <?php echo $objlang->get('entry_button_delete'); ?> <span class="caret"></span>
		</button>
		<ul role="menu" class="dropdown-menu">
			<li><a onclick="confirm('Are you sure?') ? delete_all() : false;"><?php echo $objlang->get('entry_delete_all'); ?></a></li>
			<li><a onclick="confirm('Are you sure?') ? delete_all_selected() : false;"><?php echo $objlang->get('entry_delete_selected'); ?></a></li>
			<li><a onclick="confirm('Are you sure?') ? delete_all_not_approved() :false;"><?php echo $objlang->get('entry_delete_all_not_approved'); ?></a></li>
		</ul>
	</div>
	<div class="btn-group">
		<button aria-expanded="false" data-toggle="dropdown" class="btn btn btn-primary dropdown-toggle" type="button">
			<i class="fa fa-thumbs-o-up"></i> <?php echo $objlang->get('entry_button_approve'); ?> <span class="caret"></span>
		</button>
		<ul role="menu" class="dropdown-menu">
			<li><a onclick="confirm('Are you sure?') ? approve_all_selected() :false;"><?php echo $objlang->get('entry_approve_selected'); ?></a></li>
			<li><a onclick="confirm('Are you sure?') ? approve_all_not_approved() : false;"><?php echo $objlang->get('entry_approve_all_not_approved'); ?></a></li>
		</ul>
	</div>
	<div class="btn-group">
		<button aria-expanded="false" data-toggle="dropdown" class="btn btn btn-info dropdown-toggle" type="button">
			<i class="fa fa-envelope-o"></i> <?php echo $objlang->get('entry_button_mailing'); ?> <span class="caret"></span>
		</button>
		<ul role="menu" class="dropdown-menu">
			<li><a onclick="confirm('Are you sure?') ? mailing_all() : false;"><?php echo $objlang->get('entry_mailing_send_email_to_all'); ?></a></li>
			<li><a onclick="confirm('Are you sure?') ? mailing_all_selected() : false;"><?php echo $objlang->get('entry_mailing_send_email_to_all_selected'); ?></a></li>
			<li><a onclick="confirm('Are you sure?') ? mailing_all_not_notified() : false;"><?php echo $objlang->get('entry_mailing_send_email_to_all_not_notified'); ?></a></li>
			<li><a onclick="confirm('Are you sure?') ? mailing_all_approved() : false;"><?php echo $objlang->get('entry_mailing_send_email_to_all_approved_only'); ?></a></li>
		</ul>
	</div>
	<div class="btn-group">
		<button aria-expanded="false" data-toggle="dropdown" class="btn btn btn-info dropdown-toggle" type="button" onclick="confirm('Are you sure?') ? revert_yet_send() : false;">
			<i class="fa fa-refresh"></i> <?php echo $objlang->get('entry_revert_yet_send'); ?></span>
		</button>
	</div>
</div>
<form action="" method="post" enctype="multipart/form-data" id="form-product">
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead>
			<tr>
				<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
				<td class="text-left">
					<?php echo $objlang->get('entry_column_email'); ?>
				</td>
				<td class="text-left">
					<?php echo $objlang->get('entry_column_date_added'); ?>
				</td>
				<td class="text-left">
					<?php echo $objlang->get('entry_column_status'); ?>
				</td>
				<td class="text-left">
					<?php echo $objlang->get('entry_confirm_mail'); ?>
				</td>
				<td class="text-center"><?php echo $objlang->get('entry_column_action'); ?></td>
			</tr>
			</thead>
			<tbody>
			<?php if ($newletter_email) { ?>
			<?php foreach ($newletter_email as $item) { ?>
			<tr>
				<td class="text-center">
					<input type="checkbox" name="selected[]" value="<?php echo $item['news_id']; ?>" />
				</td>
				<td class="text-left"><?php echo $item['news_email']; ?></td>
				<td class="text-left"><?php echo $item['news_create_date']; ?></td>
				<td class="text-left">
					<?php if($item['news_status'] == 0) { ?>
					<span class="label label-danger text-uppercase"><?php echo $objlang->get('entry_not_approved'); ?></span>
					<?php } else { ?>
					<span class="label label-success text-uppercase"><?php echo $objlang->get('entry_approved'); ?></span>
					<?php } ?>
				</td>
				<td class="text-left">
					<?php if($item['confirm_mail'] == 0) { ?>
					<span class="label label-danger text-uppercase"><?php echo $objlang->get('entry_yet_send'); ?></span>
					<?php } else { ?>
					<span class="label label-success text-uppercase"><?php echo $objlang->get('entry_did_send'); ?></span>
					<?php } ?>
				</td>
				<td class="text-center">
					<a data-original-title="Delete" class="btn btn-warning" title="" data-toggle="tooltip" onclick="confirm('Are you sure?') ? delete_selected('<?php echo $item['news_id']; ?>') : false;"><i class="fa fa-trash-o"></i></a>
					<a class="btn btn-primary" data-original-title="<?php if($item['news_status'] == 0) { ?> Approve <?php }else { ?>Not Approve <?php } ?>"
					   title="" data-toggle="tooltip" onclick="confirm('Are you sure?') ? approve_selected('<?php echo $item['news_id']; ?>') : false;"><i class="
					<?php if($item['news_status'] == 1) { ?>
					fa fa-thumbs-o-up
					<?php }else { ?>
					fa fa-thumbs-o-down
					<?php } ?>
					"></i></a>
					<a data-original-title="Send email" class="btn btn-info" title="" data-toggle="tooltip" onclick="confirm('Are you sure?') ? mailing_selected('<?php echo $item['news_id']; ?>') : false;"><i class="fa fa-envelope-o"></i></a>
				</td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="8"><?php echo $objlang->get('entry_text_no_results'); ?></td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</form>
<?php die();?>