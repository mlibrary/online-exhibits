<?php 
echo $this->form('search-form', $options['form_attributes']); ?>
    <div role="search">
    <div class="form-group search-form-group"> 
        <label class="sr-only" for="search-form"><span>Search Online Exhibits</span></label>
      <?php echo $this->formText('query', $filters['query'], array('class'=>'form-control', 'id'=>'search-form', 'size'=> '50', 'placeholder'=>'Search Online Exhibits')); ?>
        <button type="submit" class="button search-button"> <span class="glyphicon glyphicon-search"></span><span class="search-button-text"> Search</span></button>
    </div>
    <?php if ($options['show_advanced']): ?>
        <fieldset id="advanced-form">
            <fieldset id="query-types">
                <p><?php echo __('Search using this query type:'); ?></p>
                <?php echo $this->formRadio('query_type', $filters['query_type'], null, $query_types); ?>
            </fieldset>
            <?php if ($record_types): ?>
            <fieldset id="record-types">
                <p><?php echo __('Search only these record types:'); ?></p>
                <?php foreach ($record_types as $key => $value): ?>
                    <?php echo $this->formCheckbox('record_types[]', $key, in_array($key, $filters['record_types']) ? array('checked' => true, 'id' => 'record_types-' . $key) : null); ?> <?php echo $value; ?><br>
                <?php endforeach; ?>
            </fieldset>
            <?php elseif (is_admin_theme()): ?>
                <p><a href="<?php echo url('settings/edit-search'); ?>"><?php echo __('Go to search settings to select record types to use.'); ?></a></p>
            <?php endif; ?>
            <p><?php echo link_to_item_search(__('Advanced Search (Items only)')); ?></p>
        </fieldset>
    <?php endif; ?>
    </div>
</form>
