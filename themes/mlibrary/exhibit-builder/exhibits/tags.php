<?php echo head(array('title' =>'Browse Exhibits by Tag', 'bodyid'=>'exhibit', 'bodyclass' => 'tags'));?>
<div id="primary"  class="browse tags" >
<h1>Browse by Tag</h1>
<ul class="navigation exhibit-tags" id="secondary-nav">
	<?php //echo nav(array('Browse All' => url('exhibits/browse'), 'Browse by Tag' => url('exhibits/tags'))); ?>
</ul>

<?php //echo tag_cloud($tags,url('exhibits/browse')); ?>
</div>
<?php echo foot(); ?>