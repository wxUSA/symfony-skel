<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ErrorController extends AbstractController
{
	public function show(Throwable $exception, Request $request): Response
	{
		// Get referer for display
		$referer = $request->headers->get('referer') ?: 'none';

		// Determine status code
		$statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
		if ($exception instanceof HttpExceptionInterface)
		{
			$statusCode = $exception->getStatusCode();
		}

		// Get the status text from Symfony's built-in mapping
		$statusText = Response::$statusTexts[$statusCode] ?? null;

		// Log the exception (Symfony will handle this automatically in most cases)
		// But you can add custom logging here if needed

		// Render appropriate error template
		return $this->render('error/exception.html.twig', [
			'exception_message' => $exception->getMessage(),
			'referer' => $referer,
			'webmaster_email' => EMAIL_CURATOR,
			'show_details' => $this->getParameter('kernel.environment') !== 'prod',
			'status_code' => $statusCode,
			'status_text' => $statusText
		], new Response('', $statusCode));
	}
}
