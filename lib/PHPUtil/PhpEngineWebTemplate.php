<?php

namespace weatherUSA\PHPUtil;

use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;

class PhpEngineWebTemplate extends PhpEngine
{

	public $site_name = '';
	public $page_title = '';
	public $page_heading = '';
	public $page_subheading = '';
	public $extra_head_html = '';
	public $hero_content = '';
	public $db = null;
	public $path = null;
	public $request = null;
	public $session = null;
	public $dateformat = null;
	public $footer = '';

	public function __construct($site_name, $template_path = 'templates')
	{
		// Set the site name
		$this->site_name = $site_name;

		// Initialize the template engine with a filesystem loader
		$loader = new FilesystemLoader($template_path . '/%name%');

		return parent::__construct(new TemplateNameParser(), $loader);
	}

	public function setTitle($title, $overwrite = FALSE)
	{
		if($overwrite === FALSE)
		{
			$this->page_title = $title . ' | ' . $this->site_name;
		}
		else
		{
			$this->page_title = $title;
		}
		$this->page_heading = $title;
	}

	public function setTitleHeading($h)
	{
		$this->page_heading = $h;
	}

	public function setTitleSubHeading($h)
	{
		$this->page_subheading = $h;
	}

	public function setPageHeading($h)
	{
		$this->page_heading = $h;
	}

	public function setPageSubheading($h)
	{
		$this->page_subheading = $h;
	}

	public function setPageContent($s)
	{
		$this->addGlobal('page_content', $s);
	}

	public function appendHeadHtml($h)
	{
		$this->extra_head_html .= $h;
	}

	public function setHeroContent($h)
	{
		$this->hero_content = $h;
	}

	public function setFooter($h)
	{
		$this->footer = $h;
	}

	public function render(string|TemplateReferenceInterface $name, array $parameters = []): string
	{
		// FIXME review all of the below.
		$vars = [];
		$vars['dateformat'] = $this->dateformat;
		$vars['path'] = $this->path;
		$vars['path_info'] = $this->request->getPathInfo();
		$vars['request_uri'] = $this->request->getRequestUri();
		$vars['flashbag'] = $this->session->getFlashBag();

		$parameters = array_merge($vars, $parameters);

		return parent::render($name, $parameters);
	}

	public function renderDefault($page_content, $vars = [])
	{
		// Render the default web template
		$this->addGlobal('site_name', $this->site_name);
		$this->addGlobal('page_title', $this->page_title);
		$this->addGlobal('page_heading', $this->page_heading);
		$this->addGlobal('page_subheading', $this->page_subheading);
		$this->addGlobal('extra_head_html', $this->extra_head_html);
		$this->addGlobal('hero_content', $this->hero_content);
		$this->addGlobal('path', $this->path);
		$this->addGlobal('footer', $this->footer);

		$vars['page_content'] = $page_content;
		$vars['dateformat'] = $this->dateformat;
		$vars['path_info'] = $this->request->getPathInfo();
		$vars['request_uri'] = $this->request->getRequestUri();
		$vars['flashbag'] = $this->session->getFlashBag();

		return $this->render('default.php', $vars);

	}

	public function renderAdminTemplate($page_content, $vars = [])
	{
		// Render the admin web template
		$this->addGlobal('site_name', $this->site_name);
		$this->addGlobal('page_title', $this->page_title);
		$this->addGlobal('page_heading', $this->page_heading);
		$this->addGlobal('page_subheading', $this->page_subheading);
		$this->addGlobal('extra_head_html', $this->extra_head_html);
		$this->addGlobal('path', $this->path);
		$this->addGlobal('session', $this->session);
		$this->addGlobal('footer', $this->footer);

		$vars['page_content'] = $page_content;
		$vars['dateformat'] = $this->dateformat;
		$vars['path_info'] = $this->request->getPathInfo();
		$vars['request_uri'] = $this->request->getRequestUri();
		$vars['flashbag'] = $this->session->getFlashBag();
		$vars['user_level'] = $this->session->get('permission_level');

		return $this->render('admin/default.php', $vars);

	}

	public static function factory($page_title, $template_storage_path, UrlGeneratorInterface $path, RequestStack $rs, DateFormatter $df)
	{
		$templating = new PhpEngineWebTemplate($page_title, $template_storage_path);
		$templating->path = $path;
		$templating->request = $rs->getCurrentRequest();
		$templating->session = $rs->getCurrentRequest()->getSession();
		$templating->dateformat = $df;
		return $templating;
	}

}
