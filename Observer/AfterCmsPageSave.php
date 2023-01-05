<?php
namespace MageSuite\CmsTagManager\Observer;

class AfterCmsPageSave implements \Magento\Framework\Event\ObserverInterface
{
    protected \MageSuite\CmsTagManager\Service\Processor\SaveTags $saveTagsProcessor;

    public function __construct(
        \MageSuite\CmsTagManager\Service\Processor\SaveTags $saveTagsProcessor
    ) {
        $this->saveTagsProcessor = $saveTagsProcessor;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $pageObject = $observer->getObject();

        $data = [
            'page_id' => $pageObject->getPageId(),
            'page_tags' => $pageObject->getCmsPageTags()
        ];

        $this->saveTagsProcessor->processSave($data);
    }
}
