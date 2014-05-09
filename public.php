<?php 
/**
 * This is the file that will fetch the image from S3 and generate it.
 *
 */
if(!defined('ABSPATH'))
    die('Direct access of file not allowed');

//Get S3 url
if (wpro_get_option('wpro-aws-virthost')) {
	$s3_url = 'http://' . trim(str_replace('//', '/', wpro_get_option('wpro-aws-bucket') . '/' . trim(wpro_get_option('wpro-folder'))), '/');
} else {
	$s3_url = 'http://' . trim(str_replace('//', '/', wpro_get_option('wpro-aws-bucket') . '.s3.amazonaws.com/' . trim(wpro_get_option('wpro-folder'))), '/');
}

$file_url = $s3_url . '/' . $wp->query_vars['year'] . '/' . $wp->query_vars['month'] . '/' . $wp->query_vars['file'];

//attemp to get the file
$file =  @file_get_contents($file_url, false, NULL);

if($file){
	include_once('include/mime_type_lib.php');
	$mimetype = get_file_mime_type($wp->query_vars['file']); 
	header("Content-type: $mimetype");
	echo $file;
	flush(); 
	exit;
}else{
	status_header(404);
	nocache_headers();
	include( get_404_template() );
	exit;
}