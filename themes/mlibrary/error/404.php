<?php 
$pageTitle = __('404: Page Not Found');
echo head(array('title'=> $pageTitle,'bodyclass'=>'error404 primary')); ?>

<h1><?php echo $pageTitle; ?></h1>

<div id="primary" class="filenotfound">
    <?php echo flash(); ?>
    
    <p><?php echo __('You&#8217;ve tried to access a page that does not seem to exist. Sometimes this happens. Below is a quick break-down of the items and exhibits on this site.'); ?>
    <br/>
    <?php 
    $siteAdministratorEmail = '<a href="mailto:'. option('administrator_email') . '">' . __('site administrator') . '</a>';
    echo __('If this does not help, try contacting the %s.', $siteAdministratorEmail); ?></p>
    <div id="recent-items" class="errorPreview">
        <h2><?php echo __('Recent Items'); ?></h2>
        <ul class="items">
            <?php 
            set_loop_records('items', get_recent_items('10'));
            foreach (loop('items') as $item): ?>
		            <li class="item"><?php echo link_to_item(metadata('item', array('Dublin Core', 'Title')));?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div id="recent-exhibits" class="errorPreview">
        <h2><?php echo __('Recent Exhibits'); ?></h2>
        <ul class="exhibits">	
    			<?php 
						set_loop_records('exhibits', exhibit_builder_recent_exhibits('9'));
						foreach (loop('exhibit') as $exhibit): ?>
								<li class="item"><?php echo link_to_exhibit(); ?></li> 
						<?php endforeach; ?>
				</ul>
    </div>
  
</div>

<?php echo foot(); ?>