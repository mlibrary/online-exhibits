<div class="element-set">
    <?php foreach ($elementsInSet as $info):
        $elementName = $info['elementName'];
        $elementRecord = $info['element'];

    if (($info['isShowable']) and ($elementName=='Description')): ?>
          <div id="<?php echo text_to_id(html_escape("$setName $elementName")); ?>" class="element">
        <h2><?php echo html_escape($elementName); ?></h2>
    <?php if($info['isEmpty']): ?>
            <div class="element-text-empty"><?php echo $info['emptyText']; ?></div>
        <?php else: ?>
        <?php
        // We need to extract the element set name from the record b/c
        // $setName contains the 'pretty' version of it that may be named differently
        // than the actual element set.
        ?>
        <?php foreach ($info['texts'] as $text): ?>
            <div class="element-text"><?php echo $text; ?></div>
        <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <!-- end element -->
    <?php endif; ?>
      <?php endforeach; ?>

    <?php foreach ($elementsInSet as $info):
        $elementName = $info['elementName'];
        $elementRecord = $info['element'];

         if (($info['isShowable']) and (($elementName!='Title') and ($elementName!='Creator') and ($elementName!='Date') and ($elementName!='Identifier') and ($elementName!='Description'))) : ?>
    <div id="<?php echo text_to_id(html_escape("$setName $elementName")); ?>" class="element">
        <h2><?php echo html_escape($elementName); ?></h2>
        <?php if ($info['isEmpty']): ?>
            <div class="element-text-empty"><?php echo $info['emptyText']; ?></div>
        <?php else: ?>
        <?php
        // We need to extract the element set name from the record b/c
        // $setName contains the 'pretty' version of it that may be named differently
        // than the actual element set.
        ?>
        <?php foreach ($info['texts'] as $text): ?>
            <div class="element-text"><?php echo $text; ?></div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div><!-- end element -->
    <?php endif; ?>
    <?php endforeach; ?>

</div><!-- end element-set -->