<?php  


if (! function_exists('configureConnectionByName')) {
	function configureConnectionByName($db_name)
	{
		$config = App::make('config');
		$connections = $config->get('database.connections');
		$defaultConnection = $connections[$config->get('database.default')];
		$newConnection = $defaultConnection;
		$newConnection['database'] = $db_name;
		App::make('config')->set('database.connections.'.$db_name, $newConnection);
	}
}

?>