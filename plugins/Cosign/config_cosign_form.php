<?php
// create a form inputs to collect the user's configuration text
echo '<div class="field">';
echo '<div class="two columns alpha">';
echo '<label for="configure_this_configuration">Enter the default URL you want your site to use:</label>';
echo '</div>';
echo '<div class="inputs five columns omega">';
echo get_view()->formText(array('name'=>'configuration_this_configuration'), get_option('configuration_this_configuration'));
echo '</div>';
echo '</div>';
