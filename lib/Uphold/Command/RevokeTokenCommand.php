<?php

namespace Uphold\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Uphold\Exception\AuthenticationRequiredException;
use Uphold\UpholdClient;

/**
 * Command to revoke a Personal Access Token
 */
class RevokeTokenCommand extends Command
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this
            ->setName('tokens:revoke')
            ->setDescription('Revoke a Personal Access Token')
            ->addArgument(
                'token',
                InputArgument::REQUIRED,
                'The token to be revoked'
            )
            ->addOption(
                'sandbox',
                null,
                InputOption::VALUE_NONE,
                'If set, the request will be made to Uphold\'s sandbox API'
            )
        ;
    }

    /**
     * Execute.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Uphold client.
        $client = new UpholdClient(array('sandbox' => $input->getOption('sandbox')));

        // User's token.
        $token = $input->getArgument('token');

        try {
            $user = $client->getUser($token);

            $user->revokeToken();
        } catch (AuthenticationRequiredException $e) {
            return $output->writeln('Wrong credentials, please try again.');
        } catch(\Exception $e) {
            return $output->writeln($e->getMessage());
        }

        $output->getFormatter()->setStyle('red', new OutputFormatterStyle('red', null, array('bold', 'blink')));

        $output->writeln('');
        $output->writeln('<red>Personal Access Token revoked!</red>');
    }
}
