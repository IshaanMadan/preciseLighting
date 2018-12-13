<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Console;

use Magento\Customer\Model\Data\Customer;
use Magento\Customer\Model\Data\Group;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTestCustomersCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     */
    private $customerRepository;

    /**
     * @var \Magento\Customer\Model\Data\CustomerFactory $customerDataFactory
     */
    private $customerDataFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\GroupRepository
     */
    private $groupRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Encryption\Encryptor
     */
    private $encryptor;

    /**
     * @var array $customerProfiles
     */
    private $customerProfiles = [
        'qatester3131@gmail.com' => [
            'firstname' => 'Qa Tester',
            'lastname'  => 'Tester',
            'password'   => '1234567!',
            'customer_group_code' => 'General'
        ],
        'johnharnabi@gmail.com' => [
            'firstname' => 'Qa Automation',
            'lastname'  => 'Automation',
            'password'   => '1234567!',
            'customer_group_code' => 'Wholesale'
        ]
    ];

    /**
     * CreateTestCustomersCommand constructor.
     *
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Customer\Model\ResourceModel\CustomerRepository\Proxy $customerRepository
     * Use proxy instead of the interface in the above parameter because repo initialization while getting the commands
     * list may lead to breaking the installation process in Magento 2.1.x. Anyway, this is a good solution
     * @param \Magento\Customer\Model\Data\CustomerFactory $customerDataFactory
     * @param \Magento\Customer\Model\ResourceModel\GroupRepository $groupRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Encryption\Encryptor $encryptor
     * @param null $name
     */
    public function __construct(
        \Magento\Framework\App\State $appState,
        \Magento\Customer\Model\ResourceModel\CustomerRepository\Proxy $customerRepository,
        \Magento\Customer\Model\Data\CustomerFactory $customerDataFactory,
        \Magento\Customer\Model\ResourceModel\GroupRepository $groupRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Encryption\Encryptor $encryptor,
        $name = null
    ) {
        parent::__construct($name);
        $this->appState = $appState;
        $this->customerRepository = $customerRepository;
        $this->customerDataFactory = $customerDataFactory;
        $this->groupRepository = $groupRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->encryptor = $encryptor;
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('mageworkshop:detailedreview:create-test-customers')
            ->setDescription('MageWorkshop DetailedReview Test Customers for functional tests');
        parent::configure();
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->appState->emulateAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL, [$this, 'createTestCustomers']);
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createTestCustomers()
    {
        $customerGroupsByName = [];

        foreach ($this->customerProfiles as $email => $profile) {
            $customerGroupsByName[$profile['customer_group_code']] = 0;
        }

        $searchCriteria = $this->searchCriteriaBuilder->addFilter(
            'customer_group_code',
            implode(',', array_keys($customerGroupsByName)),
            'in'
        )->create();

        /** @var Group $customerGroup */
        foreach ($this->groupRepository->getList($searchCriteria)->getItems() as $customerGroup) {
            $customerGroupsByName[$customerGroup->getCode()] = $customerGroup->getId();
        }

        foreach ($this->customerProfiles as $email => $profile) {
            /** @var Customer $customerData */
            $customerData = $this->customerDataFactory->create();
            $customerData->setFirstname($profile['firstname'])
                ->setEmail($email)
                ->setGroupId($customerGroupsByName[$profile['customer_group_code']])
                ->setLastname($profile['lastname']); // incorrect annotation here in the core
            $passwordHash = $this->encryptor->getHash($profile['password'], true);
            $this->customerRepository->save($customerData, $passwordHash);
        }
    }
}
