<?php

namespace PhraseAppPHP\Commands;

use PhraseAppPHP\Config;
use PhraseAppPHP\Translations;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetTranslations extends Command
{
    protected function configure()
    {
        $this->setName('get')
            ->setDescription('Get Translations');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $translations = new Translations();

        $locales = Config::get('locales');
        foreach ($locales as $locale) {
            $output->writeln('Downloading <info>' . strtoupper($locale['name']) . '</info>');
            try {
                $translations->get($locale['id'], $locale['file']);
            } catch (\Exception $exception) {
                $output->writeln('<error>Error!</error> ' . $exception->getMessage());
            }
        }
    }
}
