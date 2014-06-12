<?php

namespace src\controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MainController implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->get('/', function (Request $request) use ($app)
		{
			$today = date('Y-m-d');
			$deadLine = new \DateTime();
			$deadLine->setTime(23,59,0);

			$client = new \Github\Client();
			$client->authenticate(OAUTH_TOKEN,null, 'http_token');
			$issues = $client->api('issue')->all('ZnanyLekarz', 'znanylekarz', array('labels' => "coding day", 'state' => 'open'));

			$issueCount = count($issues);

			if ($app['session']->has($today))
			{
				if ($deadLine < new \DateTime())
				{
					$app['session']->set($today, $issueCount);
				}

				$issueCount = $app['session']->get($today);
			}
			else
			{
				$app['session']->set($today, $issueCount);
			}

			return $app['twig']->render('main.html.twig', ['issueCount' => $issueCount]);
		});

		$controllers->get('/issues-count', function (Request $request) use ($app)
		{
			$client = new \Github\Client();
			$client->authenticate(OAUTH_TOKEN,null, 'http_token');
			$issues = $client->api('issue')->all('ZnanyLekarz', 'znanylekarz', array('labels' => "coding day", 'state' => 'open'));

			$leftOpen = count($issues);

			return new JsonResponse(array('issues_count' => $leftOpen));
		});

		return $controllers;
	}
}