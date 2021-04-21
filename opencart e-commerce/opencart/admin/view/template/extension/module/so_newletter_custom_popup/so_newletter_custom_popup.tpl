<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $objlang->get('entry_button_save'); ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $objlang->get('entry_button_save')?></button>
				<a class="btn btn-success" onclick="$('#action').val('save_edit');$('#form-featured').submit();" data-toggle="tooltip" title="<?php echo $objlang->get('entry_button_save_and_edit'); ?>" ><i class="fa fa-pencil-square-o"></i> <?php echo $objlang->get('entry_button_save_and_edit')?></a>
				<a class="btn btn-info" onclick="$('#action').val('save_new');$('#form-featured').submit();" data-toggle="tooltip" title="<?php echo $objlang->get('entry_button_save_and_new'); ?>" ><i class="fa fa-book"></i>  <?php echo $objlang->get('entry_button_save_and_new')?></a>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $objlang->get('entry_button_cancel'); ?>" class="btn btn-danger"><i class="fa fa-reply"></i>  <?php echo $objlang->get('entry_button_cancel')?></a>
			</div>
			<h1><?php echo $objlang->get('heading_title_so'); ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if (isset($error['warning'])) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['warning']; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if (isset($success) && !empty($success)) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_layout; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $subheading; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">
					<div class="row">
						<ul class="nav nav-tabs" role="tablist">
							<li <?php if( $selectedid ==0 ) { ?>class="active" <?php } ?>> <a href="<?php echo $link; ?>"> <span class="fa fa-plus"></span> <?php echo $objlang->get('button_add_module');?></a></li>
							<?php $i=1; foreach( $moduletabs as $key => $module ){ ?>
							<li role="presentation" <?php if( $module['module_id']==$selectedid ) { ?>class="active"<?php } ?>>
							<a href="<?php echo $link; ?>&module_id=<?php echo $module['module_id']?>" aria-controls="bannermodule-<?php echo $key; ?>"  >
								<span class="fa fa-pencil"></span> <?php echo $module['name']?>
							</a>
							</li>
							<?php $i++ ;} ?>
						</ul>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<?php $module_row = 1; ?>
						<?php foreach ($modules as $module) { ?>
							<?php if( $selectedid ){ ?>
							<div class="pull-right">
								<a href="<?php echo $action;?>&delete=1" class="remove btn btn-danger" ><span><i class="fa fa-remove"></i> <?php echo $objlang->get('entry_button_delete');?></span></a>
							</div>
							<?php } ?>
							<div  id="tab-module<?php echo $module_row; ?>" class="col-sm-12">
							<?php //----------------------------------------------------------------------- ?>
								<div class="form-group"> <?php //<!-- Module Name--> ?>
									<input type="hidden" name="action" id="action" value=""/>
									<label class="col-sm-3 control-label" for="input-name"> <b style="font-weight:bold; color:#f00">*</b> <span data-toggle="tooltip" title="<?php echo $objlang->get('entry_name_desc'); ?>"><?php echo $objlang->get('entry_name'); ?> </span></label>
									<div class="col-sm-9">
										<div class="col-sm-5">
											<input type="text" name="name" value="<?php echo $module['name']; ?>" placeholder="<?php echo $objlang->get('entry_name'); ?>" id="input-name" class="form-control" />
										</div>
										<?php if (isset($error['name'])) { ?>
										<div class="text-danger col-sm-12"><?php echo $error['name']; ?></div>
										<?php }?>
									</div>
								</div>
								<div class="form-group"> <?php //<!-- Header title--> ?>
									<label class="col-sm-3 control-label" for="input-head_name"><b style="font-weight:bold; color:#f00">*</b> <span data-toggle="tooltip" title="<?php echo $objlang->get('entry_head_name_desc'); ?>"><?php echo $objlang->get('entry_head_name'); ?> </span></label>
									<div class="col-sm-9">
										<div class="col-sm-5">
											<?php
											$i = 0;
											foreach ($languages as $language) { $i++; ?>
											<input type="text" name="module_description[<?php echo $language['language_id']; ?>][head_name]" placeholder="<?php echo $objlang->get('entry_head_name'); ?>" id="input-head-name-<?php echo $language['language_id']; ?>" value="<?php echo isset($module_description[$language['language_id']]['head_name']) ? $module_description[$language['language_id']]['head_name'] : ''; ?>" class="form-control <?php echo ($i>1) ? ' hide ' : ' first-name'; ?>" />
											<?php
												 if($i == 1){ ?>
											<input type="hidden" class="form-control" id="input-head_name" placeholder="<?php echo $objlang->get('entry_head_name'); ?>" value="<?php echo isset($module_description[$language['language_id']]['head_name']) ? $module_description[$language['language_id']]['head_name'] : ''; ?>" name="head_name">
											<?php }
												 ?>
											<?php } ?>
										</div>
										<div class="col-sm-3">
											<select  class="form-control" id="language">
												<?php foreach ($languages as $language) { ?>
												<option value="<?php echo $language['language_id']; ?>">
													<?php echo $language['name']; ?>
												</option>
												<?php } ?>
											</select>
										</div>
										<?php if (isset($error['head_name'])) { ?>
										<div class="text-danger col-sm-12"><?php echo $error['head_name']; ?></div>
										<?php }?>
									</div>
								</div>
								<div class="form-group"> <?php //<!--Display header title --> ?>
									<label class="col-sm-3 control-label" for="input-disp_title_module"> <span data-toggle="tooltip" title="<?php echo $objlang->get('entry_display_title_module_desc'); ?>"><?php echo $objlang->get('entry_display_title_module'); ?> </span></label>
									<div class="col-sm-9">
										<div class="col-sm-5">
											<select name="disp_title_module" id="input-disp_title_module" class="form-control">
												<?php
											if ($module['disp_title_module']) { ?>
												<option value="1" selected="selected"><?php echo $objlang->get('text_yes'); ?></option>
												<option value="0"><?php echo $objlang->get('text_no'); ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $objlang->get('text_yes'); ?></option>
												<option value="0" selected="selected"><?php echo $objlang->get('text_no'); ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group"> <?php //<!--Status --> ?>
									<label class="col-sm-3 control-label" for="input-status"><span data-toggle="tooltip" title="<?php echo $objlang->get('entry_status_desc'); ?>"><?php echo $objlang->get('entry_status'); ?> </span></label>
									<div class="col-sm-9">
										<div class="col-sm-5">
											<select name="status" id="input-status" class="form-control">
												<?php if ($module['status']) { ?>
												<option value="1" selected="selected"><?php echo $objlang->get('text_enabled'); ?></option>
												<option value="0"><?php echo $objlang->get('text_disabled'); ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $objlang->get('text_enabled'); ?></option>
												<option value="0" selected="selected"><?php echo $objlang->get('text_disabled'); ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							<?php //----------------------------------------------------------------------- ?>
							</div>
							<div class="tab-pane">
								<ul class="nav nav-tabs" id="so_youtech">
									<li>
										<a href="#module" data-toggle="tab">
											<?php echo $objlang->get('entry_module') ?>
										</a>
									</li>
									<li>
										<a href="#content_option" data-toggle="tab">
											<?php echo $objlang->get('entry_content_option') ?>
										</a>
									</li>
									<li>
										<a href="#newsletter_subscribers_option" data-toggle="tab">
											<?php echo $objlang->get('entry_newsletter_subscribers') ?>
										</a>
									</li>
									<li>
										<a href="#html_Email_template" data-toggle="tab">
											<?php echo $objlang->get('entry_html_Email_template') ?>
										</a>
									</li>
									<li>
										<a href="#advanced_option" data-toggle="tab">
											<?php echo $objlang->get('entry_advanced_option') ?>
										</a>
									</li>
									<li>
										<a href="#so_module_type_footer" data-toggle="tab">
											<?php echo $objlang->get('entry_type_footer') ?>
										</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane" id="module"> <?php //<!--General Option -->?>
										<div class="form-group"> <?php //<!--Class suffix --> ?>
											<label class="col-sm-3 control-label" for="input-class_suffix"> <span data-toggle="tooltip" title="<?php echo $objlang->get('entry_class_suffix_desc'); ?>"><?php echo $objlang->get('entry_class_suffix'); ?> </span> </label>
											<div class="col-sm-9">
												<div class="col-sm-5">
													<input type="text" name="class_suffix" value="<?php echo $module['class_suffix']; ?>" id="input-class_suffix" class="form-control" />
												</div>
											</div>
										</div>
										<div class="form-group"> <?php //<!--Time expired cookie--> ?>
											<label class="col-sm-3 control-label" for="input-expired"><b style="font-weight:bold; color:#f00">*</b> <span data-toggle="tooltip" title="<?php echo $objlang->get('entry_expired_desc'); ?>"><?php echo $objlang->get('entry_expired'); ?> </span></label>
											<div class="col-sm-9">
												<div class="col-sm-5">
													<input type="text" name="expired" value="<?php echo $module['expired']; ?>" id="input-expired" class="form-control" />
												</div>
												<?php if (isset($error['expired'])) { ?>
												<div class="text-danger col-sm-12"><?php echo $error['expired']; ?></div>
												<?php }?>
											</div>
										</div>
										<div class="form-group"> <?php //<!--layout--> ?>
											<label class="col-sm-3 control-label" for="input-layout"><span data-toggle="tooltip" title="<?php echo $objlang->get('entry_layout_desc'); ?>"><?php echo $objlang->get('entry_layout'); ?> </span></label>
											<div class="col-sm-9">
												<div class="col-sm-5">
													<select name="layout" id="input-layout" class="form-control">
														<?php
													foreach($layouts as $option_id => $option_value)
														{
														$selected = ($option_id == $module['layout']) ? 'selected' :'';
														?>
														<option value="<?php echo $option_id ?>" <?php echo $selected ?> ><?php echo $option_value ?></option>
														<?php
														}
													?>
													</select>
												</div>
											</div>
										</div>
										<div class="form-group"> <?php //<!--width-->?>
											<label class="col-sm-3 control-label" for="input-width"><span data-toggle="tooltip" title="<?php echo $objlang->get('entry_width_desc'); ?>"><?php echo $objlang->get('entry_width'); ?> </span></label>
											<div class="col-sm-9">
												<div class="col-sm-5">
													<input type="text" name="width" value="<?php echo $module['width']; ?>" id="input-width" class="form-control" />
												</div>
												<div class="col-sm-3" style="margin-top: 10px;color: red"><?php echo $objlang->get('entry_width_note'); ?></div>
											</div>
										</div>
										<div class="form-group"> <?php //<!--Display image background --> ?>
											<label class="col-sm-3 control-label" for="input-image_bg_display">
												<span data-toggle="tooltip" title="<?php echo $objlang->get('entry_image_bg_display_desc'); ?>"><?php echo $objlang->get('entry_image_bg_display'); ?></span>
											</label>
											<div class="col-sm-9">
												<div class="col-sm-5">
													<label class="radio-inline">
														<?php if ($module['image_bg_display']) { ?>
														<input type="radio" name="image_bg_display" value="1" checked="checked" />
														<?php echo $objlang->get('text_yes'); ?>
														<?php } else { ?>
														<input type="radio" name="image_bg_display" value="1" />
														<?php echo $objlang->get('text_yes'); ?>
														<?php } ?>
													</label>
													<label class="radio-inline">
														<?php if (!$module['image_bg_display']) { ?>
														<input type="radio" name="image_bg_display" value="0" checked="checked" />
														<?php echo $objlang->get('text_no'); ?>
														<?php } else { ?>
														<input type="radio" name="image_bg_display" value="0" />
														<?php echo $objlang->get('text_no'); ?>
														<?php } ?>
													</label>
												</div>
											</div>
										</div>
										<div class="form-group"> <?php //<!--Image--> ?>
											<label class="col-sm-3 control-label" for="input-image"><?php echo $objlang->get('entry_image'); ?></label>
											<div class="col-sm-9">
												<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
												<input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
											</div>
										</div>
										<div class="form-group" id="input-color_bg_form"> <?php //<!--color_bg -->?>
											<label class="col-sm-3 control-label" for="input-color_bg"><span data-toggle="tooltip" title="<?php echo $objlang->get('entry_color_bg_desc'); ?>"><?php echo $objlang->get('entry_color_bg'); ?></span></label>
											<div class="col-sm-9">
												<div class="col-sm-1">
													<input type="text" name="color_bg" value="<?php echo $module['color_bg']; ?>" id="input-color_bg" class="form-control" />
												</div>
											</div>
										</div>
									</div>
								<?php //----------------------------------------------------------------------- ?>
									<div class="tab-pane" id="content_option"> <!--Content Option -->
										<div class="form-group"> <?php //<!--Display title popup --> ?>
											<label class="col-sm-2 control-label" for="input-border_display">
												<span data-toggle="tooltip" title="<?php echo $objlang->get('entry_title_display_desc'); ?>"><?php echo $objlang->get('entry_title_display'); ?></span>
											</label>
											<div class="col-sm-10">
												<div class="col-sm-5">
													<label class="radio-inline">
														<?php if ($module['title_display']) { ?>
														<input type="radio" name="title_display" value="1" checked="checked" />
														<?php echo $objlang->get('text_yes'); ?>
														<?php } else { ?>
														<input type="radio" name="title_display" value="1" />
														<?php echo $objlang->get('text_yes'); ?>
														<?php } ?>
													</label>
													<label class="radio-inline">
														<?php if (!$module['title_display']) { ?>
														<input type="radio" name="title_display" value="0" checked="checked" />
														<?php echo $objlang->get('text_no'); ?>
														<?php } else { ?>
														<input type="radio" name="title_display" value="0" />
														<?php echo $objlang->get('text_no'); ?>
														<?php } ?>
													</label>
												</div>
											</div>
										</div>
										<ul class="nav nav-tabs" id="so_youtech_content">
											<?php foreach ($languages as $language) { ?>
											<li>
												<a href="#content_<?php echo $language['language_id']; ?>" data-toggle="tab">
													<?php if(version_compare(VERSION, '2.1.0.2', '>')) {?>
														<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
													<?php }else{?>
														<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
													<?php }?>
													<?php echo $language['name'] ?>
												</a>
											</li>
											<?php } ?>

										</ul>
										<div class="tab-content">
											<?php foreach ($languages as $language) { ?>
											<div class="tab-pane" id="content_<?php echo $language['language_id']; ?>"> 
												<div class="form-group" id="input-title_form"> 
													<label class="col-sm-2 control-label" for="input-title_<?php echo $language['language_id']; ?>"> <b style="font-weight:bold; color:#f00">*</b> <span data-toggle="tooltip" title="<?php echo $objlang->get('entry_title_desc'); ?>"><?php echo $objlang->get('entry_title'); ?></span></label>
													<div class="col-sm-10">
														<div class="col-sm-5">
															<input type="text" name="description_content[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($description_content[$language['language_id']]) ? $description_content[$language['language_id']]['title'] : ''; ?>" id="input-title_<?php echo $language['language_id']; ?>" class="form-control" />
														</div>
														<?php if (isset($error['title'])) { ?>
														<div class="text-danger col-sm-12"><?php echo $error['title']; ?></div>
														<?php }?>
													</div>
												</div>
												<div id="sign-up-option">
													<div class="form-group" id="input-newsletter_promo"> 
														<label class="col-sm-2 control-label" for="input-newsletter_promo_<?php echo $language['language_id']; ?>"> <b style="font-weight:bold; color:#f00">*</b> <span data-toggle="tooltip" title="<?php echo $objlang->get('entry_newsletter_promo_desc'); ?>"><?php echo $objlang->get('entry_newsletter_promo'); ?></span></label>
														<div class="col-sm-10">
															<div class="col-sm-10">
																<input type="text" name="description_content[<?php echo $language['language_id']; ?>][newsletter_promo]" value="<?php echo isset($description_content[$language['language_id']]) ? $description_content[$language['language_id']]['newsletter_promo'] : ''; ?>" id="input-newsletter_promo_<?php echo $language['language_id']; ?>" class="form-control" />
															</div>
															<?php if (isset($error['newsletter_promo'])) { ?>
															<div class="text-danger col-sm-12"><?php echo $error['newsletter_promo']; ?></div>
															<?php }?>
														</div>
													</div>
												</div>
											</div>
											<?php } ?>
										</div>

									</div>
								<?php //----------------------------------------------------------------------- ?>
									<div class="tab-pane" id="newsletter_subscribers_option"> <?php //<!--newsletter subscribers option --> ?> 
										<div id="history"></div>
									</div>
								<?php //----------------------------------------------------------------------- ?>
									<div class="tab-pane" id="html_Email_template"> <?php //<!--Content Option --> ?>
										<div class="form-group" id="input-title_form"> <!--title -->
											<label class="col-sm-2 control-label" for="input-email_template_subject"> <span data-toggle="tooltip" title="<?php echo $objlang->get('entry_email_template_subject_desc'); ?>"><?php echo $objlang->get('entry_email_template_subject'); ?></span></label>
											<div class="col-sm-10">
												<div class="col-sm-10 input-group">
													<span class="input-group-addon"><i class="fa fa-pencil-square-o"></i> Subject:</span>
													<input type="text" name="email_template_subject" value="<?php echo $module['email_template_subject']; ?>" id="input-email_template_subject" class="form-control" />
												</div>
											</div>
										</div>

										<div class="form-group"> <!--Content Email -->
											<label class="col-sm-2 control-label" for="input-content_email"> <span data-toggle="tooltip" title="<?php echo $objlang->get('entry_content_email_desc'); ?>"><?php echo $objlang->get('entry_content_email'); ?></span></label>
											<div class="col-sm-10">
												<div class="col-sm-10">
													<textarea name="content_email" placeholder="<?php echo $objlang->get('entry_content_email'); ?>" id="input-content_email" class="form-control summernote"><?php echo $module['content_email']; ?></textarea>
												</div>
											</div>
										</div>
									</div>
								<?php //----------------------------------------------------------------------- ?>
									<div class="tab-pane" id="advanced_option"> <?php //<!--Advanced Option --> ?>
										<div class="form-group"> <?php //<!--Pre-text--> ?>
											<label class="col-sm-3 control-label" for="input-pre_text"> <span data-toggle="tooltip" title="<?php echo $objlang->get('entry_pre_text_desc'); ?>"><?php echo $objlang->get('entry_pre_text'); ?></span></label>
											<div class="col-sm-9">
												<div class="col-sm-5">
													<textarea name="pre_text" id="input-pre_text" class="form-control"><?php echo $module['pre_text']; ?></textarea>
												</div>
											</div>
										</div>
										<div class="form-group"> <?php //<!--Post-text--> ?>
											<label class="col-sm-3 control-label" for="input-post_text"> <span data-toggle="tooltip" title="<?php echo $objlang->get('entry_post_text_desc'); ?>"><?php echo $objlang->get('entry_post_text'); ?></span></label>
											<div class="col-sm-9">
												<div class="col-sm-5">
													<textarea name="post_text" id="input-post_text" class="form-control"><?php echo $module['post_text']; ?></textarea>
												</div>
											</div>
										</div>
										<div class="form-group"> <?php //<!--use cache --> ?>
											<label class="col-sm-3 control-label" for="input-use_cache">
												<span data-toggle="tooltip" title="<?php echo $objlang->get('entry_use_cache_desc'); ?>"><?php echo $objlang->get('entry_use_cache'); ?></span>
											</label>
											<div class="col-sm-9">
												<div class="col-sm-5">
													<label class="radio-inline">
														<?php if ($module['use_cache']) { ?>
														<input type="radio" name="use_cache" value="1" checked="checked" />
														<?php echo $objlang->get('text_yes'); ?>
														<?php } else { ?>
														<input type="radio" name="use_cache" value="1" />
														<?php echo $objlang->get('text_yes'); ?>
														<?php } ?>
													</label>
													<label class="radio-inline">
														<?php if (!$module['use_cache']) { ?>
														<input type="radio" name="use_cache" value="0" checked="checked" />
														<?php echo $objlang->get('text_no'); ?>
														<?php } else { ?>
														<input type="radio" name="use_cache" value="0" />
														<?php echo $objlang->get('text_no'); ?>
														<?php } ?>
													</label>
												</div>
											</div>
										</div>
										<div class="form-group" id="input-cache_time_form"> <?php //<!--cache time --> ?>
											<label class="col-sm-3 control-label" for="input-cache_time">
												<span data-toggle="tooltip" title="<?php echo $objlang->get('entry_cache_time_desc'); ?>"><?php echo $objlang->get('entry_cache_time'); ?></span>
											</label>
											<div class="col-sm-9">
												<div class="col-sm-5">
													<input type="text" name="cache_time" value="<?php echo $module['cache_time']; ?>" id="input-cache_time" class="form-control" />
												</div>
												<?php if (isset($error['cache_time'])) { ?>
												<div class="text-danger col-sm-12"><?php echo $error['cache_time']; ?></div>
												<?php }?>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="so_module_type_footer"> <?php // <!--Type Footer -->?>
										
										<?php foreach ($type_footer as $footer_id => $footer_value ) : $footer_id++?>
										<div class="form-group"> <?php //====Theme Custom Code=====?>
											<label class="col-sm-3 control-label" for="input-open_link">Show Module On <?php echo $footer_value ?></label>
											<div class="col-sm-9">
												<div class="col-sm-5">
													<label class="radio-inline">
														<?php if ($module['footer_display'.$footer_id]) { ?>
														<input type="radio" name="footer_display<?php echo $footer_id;?>" value="1" checked="checked" />
														<?php echo $objlang->get('text_yes'); ?>
														<?php } else { ?>
														<input type="radio" name="footer_display<?php echo $footer_id;?>" value="1" />
														<?php echo $objlang->get('text_yes'); ?>
														<?php } ?>
													</label>
													<label class="radio-inline">
														<?php if (!$module['footer_display'.$footer_id]) { ?>
														<input type="radio" name="footer_display<?php echo $footer_id;?>" value="0" checked="checked" />
														<?php echo $objlang->get('text_no'); ?>
														<?php } else { ?>
														<input type="radio" name="footer_display<?php echo $footer_id;?>" value="0" />
														<?php echo $objlang->get('text_no'); ?>
														<?php } ?>
													</label>
												</div>
												
												
											</div>
										</div>
										<?php endforeach; ?>
									</div>
							</div>
							<?php $module_row++; ?>
						<?php } ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript"><!--
		if($("input[name='use_cache']:radio:checked").val() == '0')
		{
			$('#input-cache_time_form').hide();
		}else
		{
			$('#input-cache_time_form').show();
		}
		$("input[name='use_cache']").change(function(){
			val = $(this).val();
			if(val ==0)
			{
				$('#input-cache_time_form').hide();
			}else
			{
				$('#input-cache_time_form').show();
			}
		});
		$('#so_youtech a:first').tab('show');
		$('#so_youtech_content a:first').tab('show');
		$('#so_email_template a:first').tab('show');

		$('#language').change(function(){
			var that = $(this), opt_select = $('option:selected', that).val() , _input = $('#input-head-name-'+opt_select);
			$('[id^="input-head-name-"]').addClass('hide');
			_input.removeClass('hide');
		});

		$('.first-name').change(function(){
			$('input[name="head-name"]').val($(this).val());
		});

		$('#input-color_bg').colpick({
			layout:'hex',
			submit:0,
			colorScheme:'dark',
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				$(el).css('border-color','#'+hex);
				// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
				if(!bySetColor) $(el).val(hex);
			}
		}).keyup(function(){

			$(this).colpickSetColor(this.value);

		});
		var this_value_bg = $('#input-color_bg').val();
		$('#input-color_bg').css('border-left', '25px solid #' + this_value_bg);
		$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
		function mailing_all() {
			$.ajax({
				type: 'get',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/mailing_all&token=<?php echo $token; ?>&module_id=<?php echo $selectedid; ?>',
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['error']) {
						$('#history').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}
		function mailing_selected(subscribe_id) {
			$.ajax({
				type: 'get',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/mailing_selected&token=<?php echo $token; ?>&module_id=<?php echo $selectedid; ?>&subscribe_id=' + subscribe_id,
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['error']) {
						$('#history').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}
		function mailing_all_selected() {
			$.ajax({
				type: 'post',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/mailing_all_selected&token=<?php echo $token; ?>&module_id=<?php echo $selectedid; ?>',
				data: $('#history input[type=\'checkbox\']:checked'),
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['error']) {
						$('#history').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}
		function mailing_all_not_notified() {
			$.ajax({
				type: 'get',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/mailing_all_not_notified&token=<?php echo $token; ?>&module_id=<?php echo $selectedid; ?>',
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['error']) {
						$('#history').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}

		function mailing_all_approved() {
			$.ajax({
				type: 'get',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/mailing_all_approved&token=<?php echo $token; ?>&module_id=<?php echo $selectedid; ?>',
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['error']) {
						$('#history').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}
		function revert_yet_send() {
			$.ajax({
				type: 'get',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/revert_yet_send&token=<?php echo $token; ?>',
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}
		function approve_selected(subscribe_id) {
			$.ajax({
				type: 'get',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/approve_selected&token=<?php echo $token; ?>&subscribe_id=' + subscribe_id,
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}
		function approve_all_selected() {
			$.ajax({
				type: 'post',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/approve_all_selected&token=<?php echo $token; ?>',
				data: $('#history input[type=\'checkbox\']:checked'),
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['error']) {
						$('#history').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}
		function approve_all_not_approved() {
			$.ajax({
				type: 'get',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/approve_all_not_approved&token=<?php echo $token; ?>',
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['error']) {
						$('#history').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}

		function delete_selected(subscribe_id) {
			$.ajax({
				type: 'get',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/delete_selected&token=<?php echo $token; ?>&subscribe_id=' + subscribe_id,
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}
		function delete_all() {
			$.ajax({
				type: 'get',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/delete_all&token=<?php echo $token; ?>',
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['error']) {
						$('#history').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}
		function delete_all_selected() {
			$.ajax({
				type: 'post',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/delete_all_selected&token=<?php echo $token; ?>',
				data: $('#history input[type=\'checkbox\']:checked'),
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['error']) {
						$('#history').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}
		function delete_all_not_approved() {
			$.ajax({
				type: 'get',
				url:  'index.php?route=extension/module/so_newletter_custom_popup/delete_all_not_approved&token=<?php echo $token; ?>',
				dataType: 'json',
				success: function(json) {
					$('.alert, .text-danger').remove();
					if (json['error']) {
						$('#history').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					if (json['success']) {
						$('#history').load('index.php?route=extension/module/so_newletter_custom_popup/history&token=<?php echo $token; ?>');
						$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			});
		}

		//--></script>
		<script type="text/javascript">
		jQuery(document).ready(function ($) {
			var button = '<div class="remove-caching" style="margin-left: 15px"><button type="button" onclick="remove_cache()" title="<?php echo $objlang->get('entry_button_clear_cache'); ?>" class="btn btn-danger"><i class="fa fa-remove"></i> <?php echo $objlang->get('entry_button_clear_cache')?></button></div>';
			var button_min = '<div class="remove-caching" style="margin-left: 7px"><button type="button" onclick="remove_cache()" title="<?php echo $objlang->get('entry_button_clear_cache'); ?>" class="btn btn-danger"><i class="fa fa-remove"></i> </button></div>';
			if($('#column-left').hasClass('active')){
				$('#column-left #stats').after(button);
			}else{
				$('#column-left #stats').after(button_min);
			}
			$('#button-menu').click(function(){
				$('.remove-caching').remove();
				if($(this).parents().find('#column-left').hasClass('active')){
					$('#column-left #stats').after(button);
				}else{
					$('#column-left #stats').after(button_min);
				}
			});
		});
		function remove_cache(){
			var success_remove = '<?php echo $success_remove; ?>';
			$.ajax({
				type: 'POST',
				url: '<?php echo $linkremove; ?>',
				data: {	is_ajax_cache_lite: 1},
				success: function () {
					var html = '<div class="alert alert-success cls-remove-cache"> '+success_remove+' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
					if(!($('.page-header .container-fluid .alert-success')).hasClass('cls-remove-cache')){
						$('.page-header .container-fluid').append(html);
					}
				},
			});
		}
	</script>
	<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
	<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
	<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
</div>
<?php echo $footer; ?>