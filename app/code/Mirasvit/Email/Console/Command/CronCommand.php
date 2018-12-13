<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-email
 * @version   2.1.14
 * @copyright Copyright (C) 2018 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Email\Console\Command;

use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mirasvit\Email\Cron\CleanHistoryCron;
use Mirasvit\Email\Cron\HandleEventsCron;
use Mirasvit\Email\Cron\SendQueueCron;

class CronCommand extends Command
{
    /**
     * @var State
     */
    protected $state;

    /**
     * @var CleanHistoryCron
     */
    protected $cleanHistoryCron;

    /**
     * @var HandleEventsCron
     */
    protected $handleEventsCron;

    /**
     * @var SendQueueCron
     */
    protected $sendQueueCron;

    public function __construct(
        State $state,
        CleanHistoryCron $cleanHistoryCron,
        HandleEventsCron $handleEventsCron,
        SendQueueCron $sendQueueCron
    ) {
        $this->state = $state;
        $this->cleanHistoryCron = $cleanHistoryCron;
        $this->handleEventsCron = $handleEventsCron;
        $this->sendQueueCron = $sendQueueCron;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('mirasvit:email:cron')
            ->setDescription('Run cron jobs')
            ->setDefinition([]);

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode('frontend');

        $output->write('Cron "Fetch Events"....');
        $this->handleEventsCron->execute();
        $output->writeln('done');

        $output->write('Cron "Send Queue"....');
        $this->sendQueueCron->execute();
        $output->writeln('done');

        $output->write('Cron "Clean History"....');
        $this->cleanHistoryCron->execute();
        $output->writeln('done');
    }
}
