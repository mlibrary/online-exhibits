	<div class ="exhibit record panel panel-default index-exhibits">
		<div class="panel-heading">
			<?php echo link_to($exhibit,'show',$exhibitImage); ?>
		</div>
		<div class ="card-info panel-body">
	  	<h3 class="panel-card-title"><?php echo link_to($exhibit, 'show', strip_formatting($title)); ?></h3>
		</div>
	</div>
