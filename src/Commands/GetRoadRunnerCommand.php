<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Commands;

use MicroPHP\Framework\Attribute\Attributes\CMD;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[CMD]
class GetRoadRunnerCommand extends Command
{
    /** @noinspection PhpPossiblePolymorphicInvocationInspection */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = "curl --proto '=https' --tlsv1.2 -sSf  https://raw.githubusercontent.com/roadrunner-server/roadrunner/master/download-latest.sh | sh && tar -xzvf roadrunner-*.tar.gz && rm -rf roadrunner-*.tar.gz && mv roadrunner-*/rr . && rm -rf roadrunner-* && chmod +x ./rr";
        $result = shell_exec($command);
        $output->writeln($result);

        $helper = $this->getHelper('question');

        // 创建一个问题对象
        $question = new Question('Add .rr.yaml config file ? (y/n) ', 'y');

        // 提示用户回答问题
        $answer = $helper->ask($input, $output, $question);

        if ('y' == $answer) {
            copy(__DIR__ . '/../.rr.yaml', BASE_PATH . '/.rr.yaml');
        }

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('roadrunner:get')
            ->setDescription('get last version of roadrunner');
    }
}
