<?php 
$text = metadata('simple_pages_page', 'text', array('no_escape' => true));
if (metadata('simple_pages_page', 'use_tiny_mce')) {
   echo $text;
} else {
   echo eval('?>' . $text);
}

