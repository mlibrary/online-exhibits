<?php
	/**
	* bulk_build_tiles.php
	* This script will build all zoom_tiles for a specific collection 
	*
	* @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
	* @author Sylvain Machefert - Bordeaux 3
	*/

	// Building tiles asks for more memory than usual php, maybe need to modify default setting
	ini_set("memory_limit", "1024M");
	// max_picture_size in bytes, to prevent memory errors for big files
	$max_picture_size = 29900000;
	$collection_id = "";
	if ($collection_id == "")
	{
		print "Please provide a collection_id\n";
		die;
	}

	require_once dirname(dirname(dirname(__FILE__))).'/bootstrap.php';
	require_once("OpenLayersZoomPlugin.php");
	require_once("helpers/ZoomifyFileProcessor.php");

	$autoloader = Zend_Loader_Autoloader::getInstance();
	$application = new Omeka_Application(APPLICATION_ENV);
//		APP_DIR."/config/application.ini");

	$application->getBootstrap()->setOptions(array(
		'resources' => array(
			'theme' => array(
				'basePath' => THEME_DIR,
				'webBasePath' => WEB_THEME
			)
		)
	));
	$application->initialize();
	$db = get_db();

	$sql = " SELECT item_id, filename
	FROM {$db->File} files, {$db->Item} items
	WHERE files.item_id = items.id ";

	if ($collection_id != "")
	{
		$sql .= " AND items.collection_id = $collection_id";
	}
	$file_ids = $db->fetchAll($sql);
	$originalDir = FILES_DIR . DIRECTORY_SEPARATOR . 'original' . DIRECTORY_SEPARATOR;

	foreach ($file_ids as $one_id)
	{
		$filename = $originalDir.$one_id["filename"];
		$computer_size = filesize($filename);
		$decimals = 2;
		$sz = 'BKMGTP';
		$factor = floor((strlen($computer_size) - 1) / 3);
		$human_size = sprintf("%.{$decimals}f", $computer_size / pow(1024, $factor)) . @$sz[$factor];

		$item_id	= $one_id["item_id"];
		$fp = new ZoomifyFileProcessor();
		list($root, $ext) = $fp->getRootAndDotExtension($filename);
		$sourcePath = $root . '_zdata';
		$destination = str_replace("/original/", "/zoom_tiles/", $sourcePath);

		if ($computer_size > $max_picture_size)
		{
			print "Picture too big, skipped : $filename ($human_size)\n";
		}
		elseif (file_exists($destination))
		{
			print "This picture has already been tiled ($destination) : $human_size ($computer_size)\n";
		}
		else
		{
			print "En cours : ".$computer_size."\n";
			$fp->ZoomifyProcess($filename);
			rename($sourcePath,$destination);	
			print "Tiling $filename [$item_id]\n";
		}
	}

print_r("I am here");

exit;
?>
