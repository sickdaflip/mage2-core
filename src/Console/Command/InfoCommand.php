<?php
/**
 * FlipDev Core Info Command
 *
 * @category  FlipDev
 * @package   FlipDev_Core
 * @author    FlipDev <dev@flipdev.com>
 * @copyright Copyright (c) 2024 FlipDev
 */

namespace FlipDev\Core\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use FlipDev\Core\Helper\Data as DataHelper;
use FlipDev\Core\Helper\Config as ConfigHelper;

/**
 * Console command to display FlipDev module information
 */
class InfoCommand extends Command
{
    /**
     * @var DataHelper
     */
    protected $dataHelper;

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * Constructor
     *
     * @param DataHelper $dataHelper
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        DataHelper $dataHelper,
        ConfigHelper $configHelper
    ) {
        $this->dataHelper = $dataHelper;
        $this->configHelper = $configHelper;
        parent::__construct();
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this->setName('flipdev:info')
            ->setDescription('Display information about installed FlipDev modules');
        
        parent::configure();
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('');
        $output->writeln('<info>FlipDev Extensions Information</info>');
        $output->writeln('<info>================================</info>');
        $output->writeln('');

        // Display configuration status
        $output->writeln('<comment>Configuration Status:</comment>');
        $output->writeln('Enabled: ' . ($this->configHelper->isEnabled() ? '<info>Yes</info>' : '<error>No</error>'));
        $output->writeln('Debug Mode: ' . ($this->configHelper->isDebugMode() ? '<info>Yes</info>' : '<error>No</error>'));
        $output->writeln('Log File: ' . $this->configHelper->getLogFile());
        $output->writeln('');

        // Display installed modules
        $modules = $this->dataHelper->getFlipDevModules();
        
        if (empty($modules)) {
            $output->writeln('<error>No FlipDev modules found!</error>');
            return Command::FAILURE;
        }

        $output->writeln('<comment>Installed Modules:</comment>');
        $output->writeln('');

        $table = new Table($output);
        $table->setHeaders(['Module Name', 'Version']);

        foreach ($modules as $moduleName => $version) {
            $table->addRow([$moduleName, $version]);
        }

        $table->render();
        $output->writeln('');

        return Command::SUCCESS;
    }
}
