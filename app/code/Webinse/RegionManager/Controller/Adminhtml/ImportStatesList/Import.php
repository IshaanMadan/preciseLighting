<?php
/**
​ * ​ ​ Webinse
​ *
​ * ​ ​ PHP​ ​ Version​ ​ 7.0.22
​ *
​ * ​ ​ @category     Webinse
​ * ​ ​ @package    ​ ​ Webinse_RegionManager
​ * ​ ​ @author       Webinse​ ​ Team​ ​ <info@webinse.com>
​ * ​ ​ @copyright  ​ ​ 2018 ​ Webinse​ ​ Ltd.​ ​ (https://www.webinse.com)
​ * ​ ​ @license    ​ ​ http://opensource.org/licenses/OSL-3.0​ ​ The​ ​ Open​ ​ Software​ ​ License​ ​ 3.0
​ */
/**
​ * ​ ​ Comment​ ​ for​ ​ file
​ *
​ * ​ ​ @category     Webinse
​ * ​ ​ @package    ​ ​ Webinse_RegionManager
​ * ​ ​ @author       Webinse​ ​ Team​ ​ <info@webinse.com>
​ * ​ ​ @copyright  ​ ​ 2018 ​ Webinse​ ​ Ltd.​ ​ (https://www.webinse.com)
​ * ​ ​ @license    ​ ​ http://opensource.org/licenses/OSL-3.0​ ​ The​ ​ Open​ ​ Software​ ​ License​ ​ 3.0
​ */

namespace Webinse\RegionManager\Controller\Adminhtml\ImportStatesList;

use Magento\Backend\App\Action;
use Magento\Framework\File\Csv;
use Webinse\RegionManager\Model\States;
use Webinse\RegionManager\Model\ResourceModel\States\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class Import extends Action
{
    /**
     * @var Csv
     */
    protected $_csv;
    /**
     * @var States
     */
    protected $_statesListModel;
    /**
     * @var CollectionFactory
     */
    protected $_statesListCollection;
    /**
     * @var DirectoryList
     */
    protected $_directoryList;

    /**
     * Import constructor.
     * @param Action\Context $context
     * @param Csv $csv
     * @param States $statesListModel
     * @param DirectoryList $directoryList
     * @param CollectionFactory $statesListCollectionFactory
     */
    public function __construct(
        Action\Context $context,
        Csv $csv,
        States $statesListModel,
        DirectoryList $directoryList,
        CollectionFactory $statesListCollectionFactory
    ) {
        $this->_csv                  = $csv;
        $this->_statesListModel      = $statesListModel;
        $this->_statesListCollection = $statesListCollectionFactory;
        $this->_directoryList        = $directoryList;
        parent::__construct($context);
    }

    /**
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $states_list = $this->_statesListModel;

        /**
         * Get States List and filter only email
         */
        $states_list_collection = $this->_statesListCollection->create();
        $data = $states_list_collection->getData();
        $states_list_state = [];

        foreach ($data as $item)
        {
            $states_list_state[] = $item['states_name'];
        }

        $tmpDir = $this->_directoryList->getPath('tmp');
        $file = $tmpDir . '/datasheet-statesList.csv';

        if (!isset($file)) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));
        }

        $csv = $this->_csv;
        $csv->setDelimiter(',');
        $csvData = $csv->getData($file);

        foreach ($csvData as $row => $data) {
            if ( count($data) == 1 )
            {
                if ( !in_array($data[0],$states_list_state) )
                {
                    $states_list->setData([
                        'states_name' => $data[0]
                    ])->save();
                }
            }
            else{
                $this->messageManager->addError('The list of states should be in one column!');
                return $resultRedirect->setPath('*/*/index');
            }
        }
        $this->messageManager->addSuccess('Import Successful!');
        return $resultRedirect->setPath('webinse_regionmanager/states/index');
    }
}