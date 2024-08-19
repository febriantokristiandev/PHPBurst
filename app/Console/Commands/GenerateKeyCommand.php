<?php

namespace App\Console\Commands;

use Defuse\Crypto\Key;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateKeyCommand extends Command
{
    protected static $defaultName = 'key:generate';

    protected function configure()
    {
        $this
            ->setDescription('Generate a new encryption key.')
            ->setHelp('This command allows you to generate a new encryption key.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Generate a new random key
        $key = Key::createNewRandomKey();
        $keyString = $key->saveToAsciiSafeString();

        // Output the key
        $output->writeln('Generated key: ' . $keyString);

        // Path to the .env file
        $envFile = __DIR__ . '/../../../.env';

        // Read the existing .env file
        $envContents = file_exists($envFile) ? file_get_contents($envFile) : '';

        // Check if SESSION_KEY already exists
        if (preg_match('/^SESSION_KEY=.*$/m', $envContents, $matches)) {
            // Replace the existing SESSION_KEY value
            $envContents = preg_replace('/^SESSION_KEY=.*$/m', "SESSION_KEY={$keyString}", $envContents);
        } else {
            // Append new SESSION_KEY
            $envContents .= "\nSESSION_KEY={$keyString}";
        }

        // Write updated contents back to the .env file
        file_put_contents($envFile, $envContents);

        return Command::SUCCESS;
    }
}
