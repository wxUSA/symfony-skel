<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

#[AsCommand(
	name: 'app:setup-database',
	description: 'Initialize database schema (creates sessions table)',
)]
class SetupDatabaseCommand extends Command
{
	public function __construct(
		private PdoSessionHandler $sessionHandler
	)
	{
		parent::__construct();
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);

		try
		{
			$this->sessionHandler->createTable();

			$io->success('Database initialized successfully!');
			$io->info('Sessions table created.');

			return Command::SUCCESS;
		}
		catch (\PDOException $e)
		{
			if (str_contains($e->getMessage(), 'already exists'))
			{
				$io->warning('Sessions table already exists. Skipping creation.');
				return Command::SUCCESS;
			}

			$io->error('Failed to initialize database: ' . $e->getMessage());
			return Command::FAILURE;
		}
	}
}
