<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../conf/config.php';


$app = getAppConfigured();
session_start();


$app->mount('/', new \src\controller\MainController());


//$client = new \Github\Client();
//$client->authenticate(OAUTH_TOKEN,null, 'http_token');
//$issues = $client->api('issue')->find('ZnanyLekarz', 'znanylekarz', array('lables' => 'coding day', 'state' => 'open'));
//foreach ($issues as $issue)
//{
//	var_dump($issue);
////	echo $issue['title']. "<hr>";
//}




$app->run();


/**
 * @return Silex\Application
 */
function getAppConfigured()
{
	$app = new Silex\Application();

	$app['debug'] = true;

	$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => __DIR__.'/../src/view',
	));

	$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

	$app->register(new \FF\ServiceProvider\LessServiceProvider(), array(
		'less.sources'     => array(__DIR__.'/../src/resources/less/styles.less'),
		'less.target'      => __DIR__.'/../web/css/styles.css',
		'less.target_mode' => 0775,));

	return $app;
}

