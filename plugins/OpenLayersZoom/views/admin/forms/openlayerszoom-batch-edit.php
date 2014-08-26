<fieldset id="openlayerszoom-item-metadata">
    <h2><?php echo __(' Zoomify'); ?></h2>
    <div class="field">
        <p class="explanation">
            <?php echo __('Zoomify all images of selected items in order to display them via OpenLayersZoom.');
                echo ' ' . __('Warning: Zoomify process is heavy, so you may need to increase memory and time on your server.');
            ?>
        </p>
        <label class="two columns alpha">
            <?php echo __('Zoomify'); ?>
        </label>
        <div class="inputs five columns omega">
            <label class="zoomify">
                <?php
                    echo $this->formCheckbox('custom[openlayerszoom][zoomify]', null, array(
                        'checked' => false, 'class' => 'zoomify-checkbox'));
                ?>
            </label>
        </div>
    </div>
</fieldset>
