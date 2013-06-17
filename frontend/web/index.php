<?php
# Get a PHP mini-MVC
# Get a simple form plugin
# Implement a non-jQuery version of things
# Figure out how to make the version into a jQuery version of things
# Prettify

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

/* Controllers */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
});

$app->get('/input', function() use ($app) {
	return $app['twig']->render('input.html.twig');
});

$app->post('/process', function(Request $req) use ($app) {
	return false;
});

$app->get('/clear', function() use ($app) {
	// clean data
	return $app->redirect('/input');
});

$app->get('/output', function() use ($app) {
	return $app['twig']->render('output.html.twig');
});

$app->run();
?>