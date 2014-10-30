<?php
class Application_View_Helper_Theme
{
  public function theme($url)
  {
	/** your theme management system may be different **/
	$theme = Zend_Registry::get('theme');
	$baseURL = "/themes/{$theme->foldername}";
	return $baseURL . $url;
  }
}
