<?php
    $title = __('Add Exhibit');
    echo head(array('title' => html_escape($title), 'bodyclass' => 'exhibits'));
?>
    <div id="exhibits-breadcrumb">
        <a href="<?php echo html_escape(url('exhibits')); ?>"><?php echo __('Exhibits'); ?></a> &gt;
        <?php echo html_escape($title); ?>
    </div>

<?php echo flash(); ?>

<?php //echo common('exhibit-metadata-form', array('exhibit' => $exhibit, 'theme' => null), 'exhibits'); ?>

<?php
		$formArgs = array('exhibit' => $exhibit, 'theme' => null);
		$formArgs['csrf'] = isset($csrf) ? $csrf : '';
		echo common('exhibit-metadata-form', $formArgs, 'exhibits');
?>

<?php echo foot(); ?>
