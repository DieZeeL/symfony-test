<?php

namespace App\Command;

use App\Message\Product;
use App\Service\ProductMessageFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;


#[AsCommand(
    name: 'app:send-message',
    description: 'Send test message to RabbitMQ',
)]
class SendMessageToRabbitMQCommand extends Command
{
    private MessageBusInterface $messageBus;
    private ProductMessageFactory $productMessageFactory;

    public function __construct(MessageBusInterface $messageBus, ProductMessageFactory $productMessageFactory)
    {
        parent::__construct();
        $this->messageBus = $messageBus;
        $this->productMessageFactory = $productMessageFactory;
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'queue',
                InputArgument::OPTIONAL,
                'Queue name',
                'product_import')
            ->addOption('count',
                null,
                InputOption::VALUE_OPTIONAL,
                'Number of messages to send',
                1);
    }

    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $queue = $input->getArgument('queue');
        $count = $input->getOption('count');

        $io->note(sprintf('Send %d test messages to queue: %s', $count, $queue));

        for ($i = 0; $i < $count; $i++) {
            $this->messageBus->dispatch(new Product(
                $this->productMessageFactory->getName(),
                $this->productMessageFactory->getPrice(),
                $this->productMessageFactory->getCategory()
            ));
        }

        $io->success(sprintf('Successfully send %d test messages to queue', $count));

        return Command::SUCCESS;
    }
}
