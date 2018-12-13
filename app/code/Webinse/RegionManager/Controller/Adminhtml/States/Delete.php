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

namespace Webinse\RegionManager\Controller\Adminhtml\States;

use Magento\Backend\App\Action;
use Webinse\RegionManager\Model\States;

class Delete extends Action
{
    /**
     * @var States
     */
    protected $_model;
    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param States $model
     */
    public function __construct(Action\Context $context, States $model)
    {
        parent::__construct($context);
        $this->_model = $model;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Exception
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_model;
        $model->load($id);

        if ($model->getId()) {
            $model->delete();
            $this->messageManager->addSuccessMessage(__('Record with ID = %1 deleted!', $id));
            $this->_redirect('*/*/index');
        } else {
            $this->messageManager->addErrorMessage(__('Record with ID = %1 not found.',$id));
            $this->_redirect('*/*/index');
        }

    }
}