<?php

namespace Uphold\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Uphold\Exception\AuthenticationRequiredException;
use Uphold\Exception\BadRequestException;
use Uphold\Exception\TwoFactorAuthenticationRequiredException;
use Uphold\UpholdClient;

/**
 * Command to create a new Personal Access Token
 */
class CreateTokenCommand extends Command
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this
            ->setName('tokens:create')
            ->setDescription('Create a new Personal Access Token')
            ->addOption(
               'sandbox',
               null,
               InputOption::VALUE_NONE,
               'If set, the request will be made to Uphold\'s sandbox API'
            )
        ;
    }

    /**
     * Initializes the command.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        // Command line output interface.
        $this->output = $output;

        // Uphold client.
        $this->client = new UpholdClient(array('sandbox' => $input->getOption('sandbox')));

        // Input variables.
        $this->description = null;
        $this->login = null;
        $this->otp = null;
        $this->password = null;
    }

    /**
     * Execute.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get PAT description.
        $this->description = $this->getPATDescription();

        // Get user credentials.
        $this->login = $this->getLogin();
        $this->password = $this->getPassword();

        $pat = null;

        // Process new PAT.
        while (null === $pat) {
            try {
                $pat = $this->requestToken();
            } catch (AuthenticationRequiredException $e) {
                $output->writeln('Wrong credentials, please try again.');

                // Get user credentials.
                $this->login = $this->getLogin();
                $this->password = $this->getPassword();
            } catch (TwoFactorAuthenticationRequiredException $e) {
                $output->writeln('Two factor authentication is enabled on this account, please enter the verification code.');

                // Get verification code.
                $this->otp = $this->getOtp();
            } catch (BadRequestException $e) {
                $output->writeln('Invalid verification code, please try again.');

                // Get verification code.
                $this->otp = $this->getOtp();
            }
        }

        $output->getFormatter()->setStyle('yellow', new OutputFormatterStyle('yellow', null, array('blink')));
        $output->getFormatter()->setStyle('red', new OutputFormatterStyle('red', null, array('bold', 'blink')));
        $output->getFormatter()->setStyle('green', new OutputFormatterStyle('green', null, array('bold', 'blink')));

        $output->writeln("\n\n");
        $output->writeln("<yellow>Here is your new Personal Access Token</yellow>\n");
        $output->writeln(sprintf('Description: <green>%s</green>', $pat['description']));
        $output->writeln(sprintf('Access Token: <green>%s</green>', $pat['accessToken']));
        $output->writeln('');
        $output->writeln('<red>Keep it secret. Keep it safe.</red>');
    }

    /**
     * Request and returns a new personal access token to the API.
     *
     * @return array
     */
    public function requestToken()
    {
        return $this->client->createToken($this->login, $this->password, $this->description, $this->otp);
    }

    /**
     * Get PAT description from the output.
     *
     * @return string
     */
    public function getPATDescription()
    {
        $dialog = $this->getHelperSet()->get('dialog');

        return $dialog->askAndValidate($this->output, '<question>PAT description: </question> ', function ($value) {
            if (null === $value || '' === $value) {
                return $this->getPATDescription();
            }

            return $value;
        });
    }

    /**
     * Get user login (username or email).
     *
     * @return string
     */
    public function getLogin()
    {
        $dialog = $this->getHelperSet()->get('dialog');

        return $dialog->askAndValidate($this->output, '<question>Username or Email:</question> ', function ($value) {
            if (null === $value || '' === $value) {
                return $this->getLogin();
            }

            return $value;
        });
    }

    /**
     * Get user password.
     *
     * @return string
     */
    public function getPassword()
    {
        $dialog = $this->getHelperSet()->get('dialog');

        return $dialog->askHiddenResponseAndValidate($this->output, '<question>Password:</question> ', function ($value) {
            if (null === $value || '' === $value) {
                return $this->getPassword();
            }

            return $value;
        });
    }

    /**
     * Get user account verification code (OTP).
     *
     * @return void
     */
    public function getOtp()
    {
        $dialog = $this->getHelperSet()->get('dialog');

        return $dialog->askAndValidate($this->output, '<question>Verification code:</question> ', function ($value) {
            if (null === $value || '' === $value) {
                return $this->getOtp();
            }

            return $value;
        });
    }
}
