<?php
namespace Opengento\DevPluginList\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\Action
{
    public function execute() :void
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
