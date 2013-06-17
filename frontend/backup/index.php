<?php
# Get a simple form plugin
# Implement a non-jQuery version of things
# Figure out how to make the version into a jQuery version of things
# Prettify

# Setup
require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

####### Controllers

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
});

$app->get('/input', function() use ($app) {
	return $app['twig']->render('input.html.twig');
});

$app->post('/process', function(Request $req) use ($app) {
	$permissible = array('income' => '[0-9]+',
						 'inflation' => '[0-9]{1,2}[,\.][0-9]{1,3}',
						 'age' => '[0-9]{1,3}',
						 'target' => '[0-9]{1,3}');
	return new Response("Page will exist!", 201);
});

$app->get('/clear', function() use ($app) {
	// clean data
	return $app->redirect('/input'); // or display message?
});

$app->get('/output', function() use ($app) {
	return $app['twig']->render('output.html.twig');
});

$app->run();
?>