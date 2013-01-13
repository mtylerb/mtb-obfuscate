<?php
/**
 * Security measure for Wolf 0.7.0+
 */
if (!defined('CMS_VERSION'))
{
	Flash::set('error', __('Fatal Error: CMS_VERSION not defined.'));
}
else 
{
	$ver_check = explode('.',CMS_VERSION);
	if (($ver_check[0] >= 1) || ($ver_check[0] < 1 && $ver_check[1] > 6))
	{
		if (!defined('IN_CMS')) 
		{
			Flash::set('error', __('Fatal Error:  Not In CMS'));
			exit();
		}
	}
}

 /**
 * MTB's Email Obfuscator Plugin for Wolf CMS <http://www.tbeckett.net/articles/plugins/adv-find.xhtml>
 *
 * Copyright (C) 2011 - 2013 Tyler Beckett <tyler@tbeckett.net>
 * 
 * Dual licensed under the MIT (/license/mit-license.txt)
 * and GPL (/license/gpl-license.txt) licenses.
 */

Plugin::setInfos(array(
    'id'			=> 'mtb-obfuscate',
    'title'			=> __('MTB Email Obfuscator'), 
    'description'	=> __('Allows you to obfuscate any plain text email address to reduce the likelihood of SPAM.'), 
    'version'		=> '1.0.2',
	'license'		=> 'MIT/GPL',
    'website'		=> 'http://www.tbeckett.net/',
	'author'		=> 'Tyler Beckett')
);

require_once('obfuscate.php');

function mtb_obfuscate()
{
	$object = new Obfuscate();
	
	return $object;
}

?>