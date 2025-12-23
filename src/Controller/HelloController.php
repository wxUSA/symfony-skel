<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use weatherUSA\PHPUtil\PhpEngineWebTemplate as WebTemplate;

class HelloController extends BaseController
{
    public function helloTwig(Request $request): Response
    {
        return $this->render('hello.html.twig', [
            'client_ip' => $request->getClientIp(),
            'current_time' => new \DateTime(),
            'project_dir' => $this->getParameter('kernel.project_dir'),
        ]);
    }

    public function helloPhp(Request $request, WebTemplate $templating): Response
    {
		$page_content = $templating->render('hello.html.php', [
			'my_uri' => $this->getMyURI(),
			'client_ip' => $request->getClientIp(),
			'current_time' => new \DateTime(),
		]);
		return new Response($page_content);
    }
}
