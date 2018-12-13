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

namespace Webinse\RegionManager\Controller\Adminhtml\ImportZipList;

use Magento\Backend\App\Action;
use Magento\Framework\File\Csv;
use Webinse\RegionManager\Model\Zip;
use Webinse\RegionManager\Model\ResourceModel\Zip\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class Import extends Action
{
    /**
     * @var Csv
     */
    protected $_csv;
    /**
     * @var Zip
     */
    protected $_zipListModel;
    /**
     * @var CollectionFactory
     */
    protected $_zipListCollection;
    /**
     * @var DirectoryList
     */
    protected $_directoryList;

    /**
     * Import constructor.
     * @param Action\Context $context
     * @param Csv $csv
     * @param Zip $zipListModel
     * @param DirectoryList $directoryList
     * @param CollectionFactory $zipListCollectionFactory
     */
    public function __construct(
        Action\Context $context,
        Csv $csv,
        Zip $zipListModel,
        DirectoryList $directoryList,
        CollectionFactory $zipListCollectionFactory
    ) {
        $this->_csv                  = $csv;
        $this->_zipListModel      = $zipListModel;
        $this->_zipListCollection = $zipListCollectionFactory;
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
        ini_set('max_execution_time', '150');
        $resultRedirect = $this->resultRedirectFactory->create();
        $zip_list = $this->_zipListModel;

        /**
         * Get Zip List and filter only email
         */
        $zip_list_collection = $this->_zipListCollection->create();
        $data = $zip_list_collection->getData();
        $zip_list_city = [];

        foreach ($data as $item)
        {
            $zip_list_city[] = $item['zip_code'];
        }

        $tmpDir = $this->_directoryList->getPath('tmp');
        $file = $tmpDir . '/datasheet-zipList.csv';

        if (!isset($file)) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));
        }

        $csv = $this->_csv;
        $csv->setDelimiter(',');
        $csvData = $csv->getData($file);

        foreach ($csvData as $row => $data) {
            if ( count($data) == 3 )
            {
                if ($data[0] == 'State name') continue;
                if ( !in_array($data[2],$zip_list_city) )
                {
                    $zip_list->setData([
                        'states_name' => $data[0],
                        'cities_name' => $data[1],
                        'zip_code'    => $data[2]
                    ])->save();
                }
            }
            else{
                $this->messageManager->addError('The list of states should be in three column!');
                return $resultRedirect->setPath('*/*/index');
            }
        }
        $this->messageManager->addSuccess('Import Successful!');
        return $resultRedirect->setPath('webinse_regionmanager/zip/index');
    }
}