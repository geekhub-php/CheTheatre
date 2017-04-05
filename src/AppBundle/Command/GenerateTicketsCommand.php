<?php

namespace AppBundle\Command;

use AppBundle\Domain\PerformanceEvent\PerformanceEventInterface;
use AppBundle\Domain\Ticket\TicketInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateTicketsCommand extends ContainerAwareCommand
{

    /** @var  PerformanceEventInterface */
    public $performanceEventService;

    /** @var  TicketInterface */
    public $ticketService;

    public function __construct(
        PerformanceEventInterface $performanceEventService,
        TicketInterface $ticketService
    ) {
        $this->performanceEventService = $performanceEventService;
        $this->ticketService = $ticketService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:generate-tickets')
            ->setDescription('Generate new Set of Tickets for performanceEvent')
            ->addArgument(
                'performanceEventId',
                InputArgument::REQUIRED,
                'The Performance Event ID.'
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_OPTIONAL,
                'Remove previously generated tickets set for PerformanceEvent and generates new one.',
                false
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $startTime = new \DateTime('now');

            /** @var EntityManager $em */
            $output->writeln('<comment>Running Tickets Generation</comment>');

            $performanceEventId = (int) $input->getArgument('performanceEventId') ?: null;
            $force = (bool) $input->getOption('force')  ? true : false;

            $this->ticketService->generateSet($this->performanceEventService->getById($performanceEventId), $force);

            $output->writeln(sprintf('<info>SUCCESS</info>'));
        } catch (\Exception $e) {
            $output->writeln('<error>ERROR Generating Tickets: '.$e->getMessage().'</error>');
        } finally {
            $finishTime = new \DateTime('now');
            $interval = $startTime->diff($finishTime);
            $output->writeln(sprintf('<comment>DONE in %s seconds<comment>', $interval->s));
        }
    }
}
