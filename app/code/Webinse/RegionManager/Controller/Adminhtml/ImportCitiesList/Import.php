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

namespace Webinse\RegionManager\Controller\Adminhtml\ImportCitiesList;

use Magento\Backend\App\Action;
use Magento\Framework\File\Csv;
use Webinse\RegionManager\Model\Cities;
use Webinse\RegionManager\Model\ResourceModel\Cities\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class Import extends Action
{
    /**
     * @var Csv
     */
    protected $_csv;
    /**
     * @var Cities
     */
    protected $_citiesListModel;
    /**
     * @var CollectionFactory
     */
    protected $_citiesListCollection;
    /**
     * @var DirectoryList
     */
    protected $_directoryList;

    /**
     * Import constructor.
     * @param Action\Context $context
     * @param Csv $csv
     * @param Cities $citiesListModel
     * @param DirectoryList $directoryList
     * @param CollectionFactory $citiesListCollectionFactory
     */
    public function __construct(
        Action\Context $context,
        Csv $csv,
        Cities $citiesListModel,
        DirectoryList $directoryList,
        CollectionFactory $citiesListCollectionFactory
    ) {
        $this->_csv                  = $csv;
        $this->_citiesListModel      = $citiesListModel;
        $this->_citiesListCollection = $citiesListCollectionFactory;
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
        ini_set('max_execution_time', '100');
        $resultRedirect = $this->resultRedirectFactory->create();
        $cities_list = $this->_citiesListModel;

        /**
         * Get Cities List and filter only email
         */
        $cities_list_collection = $this->_citiesListCollection->create();
        $data = $cities_list_collection->getData();
        $cities_list_city = [];

        foreach ($data as $item)
        {
            $cities_list_city[] = $item['cities_name'];
        }

        $tmpDir = $this->_directoryList->getPath('tmp');
        $file = $tmpDir . '/datasheet-citiesList.csv';

        if (!isset($file)) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));
        }

        $csv = $this->_csv;
        $csv->setDelimiter(',');
        $csvData = $csv->getData($file);

        foreach ($csvData as $row => $data) {
            if ( count($data) == 2 )
            {
                if ($data[0] == 'State name') continue;

                if ( !in_array($data[1],$cities_list_city) )
                {
                    $cities_list->setData([
                        'states_name' => $data[0],
                        'cities_name' => $data[1]
                    ])->save();
                }
            }
            else{
                $this->messageManager->addError('The list of states should be in two column!');
                return $resultRedirect->setPath('*/*/index');
            }
        }
        $this->messageManager->addSuccess('Import Successful!');
        return $resultRedirect->setPath('webinse_regionmanager/cities/index');
    }
}