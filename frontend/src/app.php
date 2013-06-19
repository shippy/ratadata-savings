<?php
# Implement a non-jQuery version of things
# Figure out how to make the version into a jQuery version of things
# Prettify

/* Setup */
require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();

/* Services */
use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
)); // TODO: investigate
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

/* Models */

/* Controllers */

### Homepage
$app->get('/', function() use ($app) {
	return $app['twig']->render('index.html.twig');
})->bind('home');

### Core functionality
# Form input
$app->get('/input/{type}', function($type = 'basic') use ($app) {
	$form = $app['form.factory']->createBuilder('form', $app['session']->get('data'))
		->setAction($app['url_generator']->generate('process', array('type' => $type)))
		->setMethod('POST');
	// Check what fields to render
	switch ($type) {
		case 'basic':
			$form->add('age_current', 'integer', 
					array('label' => 'Současný věk',
						  'constraints' => new Assert\NotBlank()))
				 ->add('age_retirement', 'integer',
					array('label' => 'Věk odchodu do důchodu',
						  'constraints' => array(
							new Assert\NotBlank(),
							# new Assert\GreaterThan('age_current')
							)))
				 ->add('age_terminal', 'integer',
					array('label' => 'Konečný věk',
						  'constraints' => array(
							new Assert\NotBlank(),
							# new Assert\GreaterThan('age_retirement')
							)))
				 ->add('savings', 'money',
					array('label' => 'Dosavadní úspory',
						  'currency' => 'CZK'))
				 ->add('dividend', 'money',
					array('label' => 'Žádaný roční důchod',
						  'currency' => 'CZK',
						  'constraints' => new Assert\GreaterThan(array('value' => 0))));
			break;
		case 'life':
			break;
		case 'money':
			$form->add('income')
				 ->add('occupation')
				 ->add('industry');
			break;
		default:
			return $app->redirect('/');
			break;
	}
	
	// TODO: Check whether to render partial (for jQuery) or full page
	$form->add('Odeslat', 'submit');
	$form = $form->getForm(); // factory, not setter
	return $app['twig']->render('input.html.twig', array('form' => $form->createView()));
})->bind('input');

# Processing
$app->post('/process/{type}', function($type = 'basic', Request $req) use ($app) {
	// save data into session
	// TODO: validate $req->request->get('form')
	if ($app['session']->has('data')) {
		$app['session']->set('data', array_merge($app['session']->get('data'), $req->request->get('form')));
	} else {
		$app['session']->set('data', $req->request->get('form'));
	}
	// TODO: update serialization in DB associated with session_id
	return $app->redirect('/result'); // or supply the result in some other way?
})->bind('process');

# Recommendation
require_once 'calculator.php';
use Savings;
$app->get('/result', function() use ($app) {
	// $terminal_age_expected = getTerminalAge($data);
	$data = $app['session']->get('data');
	
	$save = Savings\getYearlySavings($data);
	return $app['twig']->render('result.html.twig', array('save' => $save));
})->bind('result');

### Data control
# Save data for later retrieval
$app->get('/store/{email}', function($email) use ($app) {
	// associate serialiazed data with e-mail, send confirmation e-mail
	return false;
})->bind('store');

# Clear data association
$app->get('/clear', function() use ($app) {
	// clean data from central data object (but not anonymized DB)
	// generate new session ID
	return $app->redirect('/input');
})->bind('clear');

### Meta (static pages)
$static = array('methodology' => '/methodology',
				'sources' => '/sources',
				'authors' => '/authors');

foreach ($static as $page => $path) {
	$app->get($path, function() use ($app) {
		return $app['twig']->render($page.'.html.twig');
	})->bind($page);
}
?>