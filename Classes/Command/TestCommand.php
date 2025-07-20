<?php

declare(strict_types=1);

namespace Ppl\ProjectDesk\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ppl\ProjectDesk\Controller\ConfigController;

class TestCommand extends Command
{
    public function __construct(
        private readonly ConfigController $configController
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('test');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Do awesome stuff
        return 0;
    }
}
