<?php
namespace MageSuite\CmsTagManager\Observer;

class BeforeCmsPageSave implements \Magento\Framework\Event\ObserverInterface
{
    protected \Magento\Framework\App\RequestInterface $request;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $pageObject = $observer->getObject();

        $params = $this->request->getParams();
        if ($params && (!isset($params['cms_image_teaser']) || !$params['cms_image_teaser'])) {
            $pageObject->setData('cms_image_teaser', null);
            return;
        }

        $imageTeaserData = $pageObject->getData('cms_image_teaser');

        if ($imageTeaserData && isset($imageTeaserData[0]['name'])) {
            $pageObject->setData('cms_image_teaser', $imageTeaserData[0]['name']);
        }
    }
}
