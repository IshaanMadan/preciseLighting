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
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class Upload extends Action
{
    protected $_directoryList;
    protected $_jsonHelper;

    public function __construct(
        Action\Context $context,
        DirectoryList $directoryList,
        JsonHelper $jsonHelper
    )
    {
        $this->_jsonHelper = $jsonHelper;
        $this->_directoryList = $directoryList;
        parent::__construct($context);
    }

    public function execute()
    {
        try{
            $tmpDir = $this->_directoryList->getPath('tmp');
            $ext = pathinfo('import_states_list.csv')['extension'];
            move_uploaded_file($this->getRequest()->getFiles("csv_uploader")['tmp_name'], $tmpDir . "/datasheet-statesList." . $ext);
            return $this->jsonResponse(['error' => "File was successfully uploaded! You can import data."]);
        }catch (\Exception $e){
            return $this->jsonResponse(['error' => $e->getMessage()]);
        }
    }

    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson($this->_jsonHelper->jsonEncode($response));
    }

}