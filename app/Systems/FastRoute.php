<?php
// Source: https://github.com/nikic/FastRoute

$dispatcher = FastRoute\cachedDispatcher(function (FastRoute\RouteCollector $route) {
	require_once ROOT_PATH . '/config/routes.php';
}, [
	'cacheFile' => ROOT_PATH . '/storage/cache/route.cache', /* required */
	'cacheDisabled' => config('app.debug'),     /* optional, enabled by default */
]);

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
	$uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
	case FastRoute\Dispatcher::NOT_FOUND:
		// ... 404 Not Found
		// exit('404 not found'); //default
		http_response_code(404);
		echo '404 Not Found';
		return;
		break;
	case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
		$allowedMethods = $routeInfo[1];
		exit('method type error');
		// ... 405 Method Not Allowed
		break;
	case FastRoute\Dispatcher::FOUND:
		$handler = $routeInfo[1];
		$vars = $routeInfo[2];

		$middlewares = $handler[2] ?? [];
		// run middleware
		foreach ($middlewares as $middleware) {
			(new $middleware())->handle();
		}

		global $container;
		$controller = $container->make($handler[0]);
		// call_user_func_array([$controller, $handler[1]], $vars);
		$action = $handler[1];
		$result = $controller->$action(...array_values($vars));

		// Handle Response objects
		if ($result instanceof \App\Systems\Response) {
			$result->send();
		}
		break;
}
