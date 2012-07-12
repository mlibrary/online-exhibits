<?php 
$pageTitle = __('404: Page Not Found');
head(array('bodyclass'=>'error404 primary', 'title'=> $pageTitle)); ?>
<h1><?php echo $pageTitle; ?></h1>

<div id="primary" class="filenotfound">
    <?php echo flash(); ?>
    
    <p><?php echo __('You&#8217;ve tried to access a page that does not seem to exist. Sometimes this happens. Below is a quick break-down of the items and exhibits on this site.'); ?>
    <br/>
    <?php 
    $siteAdministratorEmail = '<a href="mailto:'. settings('administrator_email') . '">' . __('site administrator') . '</a>';
    echo __('If this does not help, try contacting the %s.', $siteAdministratorEmail); ?></p>
    <div id="recent-items" class="errorPreview">
        <h2><?php echo __('Recent Items'); ?></h2>
        <ul class="items">
            <?php 
            set_items_for_loop(recent_items('10'));
            if(has_items_for_loop()): while(loop_items()): ?>
            <li class="item"><?php echo link_to_item(); ?></li>
            <?php endwhile; endif; ?>
        </ul>
    </div>
    <div id="recent-exhibits" class="errorPreview">
        <h2><?php echo __('Recent Exhibits'); ?></h2>
        <ul class="exhibits">	
    	<?php 
		set_exhibits_for_loop(exhibit_builder_get_exhibits(array('recent' => true)));
		while(loop_exhibits()): ?>
		<?php $exhibitCount++; if($exhibitCount > 10){ break; }; ?>
		<li class="item"><?php echo link_to_exhibit(); ?></li> 
		<?php endwhile; ?>
	</ul>
    </div>

</div>

<?php foot(); ?>