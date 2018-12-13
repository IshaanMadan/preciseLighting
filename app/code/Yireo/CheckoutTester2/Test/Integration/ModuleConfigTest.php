<?php
/**
 * CheckoutTester2 plugin for Magento
 *
 * @package     Yireo_EmailTester2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types = 1);

namespace Yireo\CheckoutTester2\Test\Integration;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Module\ModuleList;
use Magento\TestFramework\ObjectManager;

/**
 * Class ModuleConfigTest
 *
 * @package Yireo\CheckoutTester2\Test\Integration
 */
class ModuleConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $subjectModuleName;

    /**
     * @var $objectManager ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        $this->subjectModuleName = 'Yireo_CheckoutTester2';
        $this->objectManager = ObjectManager::getInstance();
    }

    public function testModuleIsRegistered()
    {
        $registrar = new ComponentRegistrar();
        $this->assertArrayHasKey($this->subjectModuleName, $registrar->getPaths(ComponentRegistrar::MODULE));
    }

    public function testModuleIsListed()
    {
        /** @var $moduleList ModuleList */
        $moduleList = $this->objectManager->create(ModuleList::class);
        $this->assertTrue($moduleList->has($this->subjectModuleName));
    }

    public function testTheModuleIsConfiguredInTheRealEnvironment()
    {
        /** @var $objectManager ObjectManager */
        $this->objectManager = ObjectManager::getInstance();

        // The tests by default point to the wrong config directory for this test.
        $directoryList = $this->objectManager->create(
            \Magento\Framework\App\Filesystem\DirectoryList::class,
            ['root' => BP]
        );

        $deploymentConfigReader = $this->objectManager->create(
            \Magento\Framework\App\DeploymentConfig\Reader::class,
            ['dirList' => $directoryList]
        );

        $deploymentConfig = $this->objectManager->create(
            \Magento\Framework\App\DeploymentConfig::class,
            ['reader' => $deploymentConfigReader]
        );

        /** @var $moduleList ModuleList */
        $moduleList = $this->objectManager->create(
            ModuleList::class,
            ['config' => $deploymentConfig]
        );

        $this->assertTrue($moduleList->has($this->subjectModuleName));
    }
}