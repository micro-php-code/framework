<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Commands;

use MicroPHP\Framework\Attribute\Attributes\CMD;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[CMD]
class StartCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = new Process(['./rr', 'serve'], timeout: null);
        $process->mustRun(function ($type, $buffer) use ($output) {
            if (str_contains($buffer, 'ERROR')) {
                $output->writeln('<error>'.$buffer.'</error>');
            } elseif (str_contains($buffer, 'WARN')) {
                $output->writeln('<comment>'.$buffer.'</comment>');
            } elseif (preg_match('/INFO\s+server/', $buffer)) {
                $output->writeln('<fg=cyan>'.$buffer . '</>');
            } else {
                $output->writeln('<info>'.$buffer.'</info>');
            }
        });
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('start')->setDescription('Start the application');
    }
}