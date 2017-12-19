<?php
    $pageTitle = __('Search Omeka ') . __('(%s total)', $total_results);
    set_theme_option('display_header','0');
    echo head(array('title' => $pageTitle, 'bodyclass' => 'search'));
    $searchRecordTypes = get_search_record_types();
?>
    <h1><?php echo $pageTitle; ?></h1>
    <h5><?php echo search_filters(); ?></h5>
    <?php if ($total_results): ?>
        <table id="search-results">
            <thead>
                <tr>
                    <th><?php echo __('Record Type');?></th>
                    <th><?php echo __('Title');?></th>
                </tr>
            </thead>
            <tbody>
                <?php $filter = new Zend_Filter_Word_CamelCaseToDash; ?>
                <?php foreach (loop('search_texts') as $searchText): ?>
                <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
                <?php $recordType = $searchText['record_type']; ?>
                <?php set_current_record($recordType, $record); ?>
                <tr class="<?php echo strtolower($filter->filter($recordType)); ?>">
                    <td>
                        <?php echo $searchRecordTypes[$recordType]; ?>
                    </td>
                    <td>
                        <?php if ($recordImage = record_image($recordType, 'square_thumbnail')): ?>
                            <?php echo link_to($record, 'show', $recordImage, array('class' => 'image')); ?>
                        <?php endif; ?>
                        <a href="<?php echo record_url($record, 'show'); ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php echo pagination_links(); ?>
    <?php else: ?>
        <p><?php echo __('Your query returned no results.');?></p>
    <?php endif; ?>
<?php echo foot(); ?>
