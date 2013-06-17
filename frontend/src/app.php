<?php
# Implement a non-jQuery version of things
# Figure out how to make the version into a jQuery version of things
# Prettify

/* Setup */
require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();

/* Services */
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

/* Models */

/* Controllers */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Homepage
$app->get('/', function() use ($app) {
	return $app['twig']->render('index.html.twig');
});

// Core functionality
# Form
$app->get('/input/{$type}', function($type = 'basic') use ($app) {
	return $app['twig']->render('input.html.twig');
});

# Processing
$app->post('/process', function(Request $req) use ($app) {
	// save data in central data object
	// update serialization in DB associated with session_id
	return $app->redirect('/result');
});

# Recommendation
$app->get('/result', function() use ($app) {
	return $app['twig']->render('result.html.twig');
});

// Data control
# Save data for later retrieval
$app->get('/store/{email}', function($email) use ($app) {
	// associate serialiazed data with e-mail, send confirmation e-mail
	return false;
});

# Clear data association
$app->get('/clear', function() use ($app) {
	// clean data from central data object (but not anonymized DB)
	// generate new session ID
	return $app->redirect('/input');
});
?>