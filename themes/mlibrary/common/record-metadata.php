
<?php // you can access all the Elements type and not Dublin Core as it is used to be in Omeka 1.5, we care here about Dublin Core that is
// why the if condition is added.
foreach ($elementsForDisplay as $setName => $setElements): 
if ($setName=='Dublin Core'):?>
<div class="element-set">
    <h2><?php //echo html_escape(__($setName)); ?></h2>
    <?php foreach ($setElements as $elementName => $elementInfo): ?>
          <?php if ($elementName == 'Description') {?>
             <div id="<?php echo text_to_id(html_escape("$setName $elementName")); ?>" class="element">
             <h2><?php echo html_escape(__($elementName)); ?></h2>
             <?php foreach ($elementInfo['texts'] as $text): ?>
                   <div class="element-text"><?php echo $text; ?></div>
             <?php endforeach; ?>
             </div><!-- end element -->
          <?php }?>
    <?php endforeach; ?>

   <?php foreach ($setElements as $elementName => $elementInfo):?>
         <?php if (($elementName!='Title') and ($elementName!='Creator') and ($elementName!='Date') and ($elementName!='Identifier') and ($elementName!='Description')){ ?>   
             <div id="<?php echo text_to_id(html_escape("$setName $elementName")); ?>" class="element">
             <h2><?php echo html_escape(__($elementName)); ?></h2>
             <?php foreach ($elementInfo['texts'] as $text): ?>
                   <div class="element-text"><?php echo $text; ?></div>
             <?php endforeach; ?>
             </div><!-- end element -->
         <?php }?>
    <?php endforeach; ?>
</div><!-- end element-set -->
<?php endif;?>
<?php endforeach;
