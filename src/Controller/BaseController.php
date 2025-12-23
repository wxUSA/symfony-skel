<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends AbstractController
{
	/**
	 * Returns the current route URI using the actual router request.
	 */
	public function getMyURI(): string
	{
		$request = $this->container->get('request_stack')->getCurrentRequest();
		return $this->generateUrl($request->attributes->get('_route'), $request->attributes->get('_route_params', []));
	}

	/**
	 * Returns the current route name (label) using the actual router request.
	 */
	public function getMyRouteName(): string
	{
		$request = $this->container->get('request_stack')->getCurrentRequest();
		return $request->attributes->get('_route');
	}

	/**
	 * Get merged query and request parameters, similar to PHP's $_REQUEST
	 * 
	 * @param Request $request The Symfony request object
	 * @return array Merged parameters (POST overrides GET) or empty array if trigger not found
	 */
	protected function getMergedRequestParams(Request $request): array
	{
		return array_merge($request->query->all(), $request->request->all());
	}

	/**
	 * Check if the request has the submit trigger parameter in either GET or POST
	 * 
	 * @param Request $request The Symfony request object  
	 * @param string $triggerParam Parameter name to check for (default: '__submit')
	 * @return bool True if trigger parameter exists
	 */
	protected function hasSubmitTrigger(Request $request, string $triggerParam = '__submit'): bool
	{
		return $request->query->has($triggerParam) || $request->request->has($triggerParam);
	}
}
