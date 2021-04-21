<?php
$count_item = count($list);
?>
<div class="module sohomepage-slider <?php echo $class_suffix?>">
<?php if($disp_title_module){?>
	<h3 class="modtitle"><?php echo $head_name?></h3>
<?php }?>

<?php if($pre_text != '')
	{
?>
	<div class="form-group">
		<?php echo html_entity_decode($pre_text);?>
	</div>
<?php
	}
?>

<div class="modcontent">
	<?php if($list){ ?>
	<div id="sohomepage-slider<?php echo $module?>">
		 <div class="so-homeslider sohomeslider-inner-<?php echo $module?>">
			<?php foreach($list as $item){?>
				<div class="item ">
					<a href="<?php echo $item['url']?>" title="<?php echo $item['title']?>" target="<?php echo $item_link_target?>">
						<img class="responsive" src="<?php echo $item['thumb']?>"  alt="<?php echo $item['title']?>" />
					</a>
					<div class="sohomeslider-description">
						<h2><?php echo html_entity_decode($item['caption'])?></h2>
						<?php echo html_entity_decode($item['description'])?>
					</div>
				</div>
			<?php }?>
		</div>
		<script type="text/javascript">
			var total_item = <?php echo $count_item ; ?>;
			$(".sohomeslider-inner-<?php echo $module?>").owlCarousel2({
					animateOut: '<?php echo $animateOut?>',
					animateIn: '<?php echo $animateIn?>',
					autoplay: <?php echo $autoplay?>,
					autoplayTimeout: <?php echo $autoplayTimeout?>,
					autoplaySpeed:  <?php echo $autoplaySpeed?>,
					smartSpeed: 500,
					autoplayHoverPause: <?php echo $autoplayHoverPause?>,
					startPosition: <?php echo $startPosition?>,
					mouseDrag:  <?php echo $mouseDrag?>,
					touchDrag: <?php echo $touchDrag?>,
					dots: <?php echo $dots?>,
					autoWidth: false,
					dotClass: "owl2-dot",
					dotsClass: "owl2-dots",
					loop: <?php echo $loop?>,
					navText: ["Next", "Prev"],
					navClass: ["owl2-prev", "owl2-next"],
					responsive: {
					0:{	items: <?php echo $nb_column4;?>,
						nav: total_item <= <?php echo $nb_column4;?> ? false : ((<?php echo $nav ; ?>) ? true: false),
					},
					480:{ items: <?php echo $nb_column3;?>,
						nav: total_item <= <?php echo $nb_column3;?> ? false : ((<?php echo $nav ; ?>) ? true: false),
					},
					768:{ items: <?php echo $nb_column2;?>,
						nav: total_item <= <?php echo $nb_column2;?> ? false : ((<?php echo $nav ; ?>) ? true: false),
					},
					992:{ items: <?php echo $nb_column1;?>,
						nav: total_item <= <?php echo $nb_column1;?> ? false : ((<?php echo $nav ; ?>) ? true: false),
					},
					1200:{ items: <?php echo $nb_column0;?>,
						nav: total_item <= <?php echo $nb_column0;?> ? false : ((<?php echo $nav ; ?>) ? true: false),
					}
				},
			});
	</script>
	</div>
	<?php } else{ ?>
		<?php echo $objlang->get('text_noitem');?>
	<?php }?>
</div> <!--/.modcontent-->

<?php if($post_text != '')
{
?>
	<div class="form-group">
		<?php echo html_entity_decode($post_text);?>
	</div>
<?php
}
?>

</div> <!--/.module-->
