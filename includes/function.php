<?php

/**
* retourne le nom du dossier
*
* @return string
*/
function uri($cible="")//:string
{
	$uri = "http://".$_SERVER['HTTP_HOST'];
	$folder = basename(dirname(dirname(__FILE__)));
	return $uri.'/'.$folder.'/'.$cible;
}