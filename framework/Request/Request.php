<?php
namespace Framework\Request;

class Request
{
	public function __construct()
	{
	}

	public function getPathInfo()
	{
		$request_uri = $_SERVER['REQUEST_URI'];
        $query_string = $_SERVER['QUERY_STRING'];
        $script_name = $_SERVER['SCRIPT_NAME'];
		
		$path_info = str_replace('?' . $query_string, '', $request_uri);
		$path_info = str_replace($script_name, '', $path_info);
		$path_info = trim($path_info, '/');
	}
}