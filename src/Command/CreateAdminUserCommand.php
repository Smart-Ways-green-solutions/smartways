<?php

namespace App\Command;

use Pimcore\Model\DataObject\Customer;
use Pimcore\Tool\Admin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdminUserCommand extends Command
{
    protected static $defaultName = 'app:create-admin-user';

    protected function configure()
    {
        $this
            ->setDescription('Creates a default admin user in the Customer class.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Check if user already exists
        $existingUser = Customer::getByEmail('admin@smartways.app', 1);
        if ($existingUser) {
            $output->writeln('<comment>Admin user already exists.</comment>');
            return Command::SUCCESS;
        }

        // Create new customer object
        $customer = new Customer();
        $customer->setKey('admin-user');
        $customer->setPublished(true);
        $customer->setEmail('admin@smartways.app');
        $customer->setFirstname('Admin');
        $customer->setLastname('User');
        $customer->setRole('Administrator');
        $customer->setCompanyAdmin(true);
        $customer->setPermission_wegepate(true);
        $customer->setPermission_lagerist(true);
        $customer->setPermission_wegemanager(true);
        $customer->setPermission_admin(true);
        $customer->setActive(true);
        $customer->setPassword('admin@1234');
        // Save the customer
        $customer->save();

        $output->writeln('<info>Admin user created successfully.</info>');
        return Command::SUCCESS;
    }
}
