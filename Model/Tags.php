<?php

namespace MageSuite\CmsTagManager\Model;

class Tags extends \Magento\Catalog\Model\AbstractModel implements \MageSuite\CmsTagManager\Api\Data\TagsInterface
{
    protected function _construct()
    {
        $this->_init(\MageSuite\CmsTagManager\Model\ResourceModel\Tags::class);
    }

    /**
     * @return mixed
     */
    public function getCmsPageId()
    {
        return $this->getData('cms_page_id');
    }

    /**
     * @param string $cmsPageId
     * @return \Magento\Framework\DataObject
     */
    public function setCmsPageId($cmsPageId)
    {
        return $this->setData('cms_page_id', $cmsPageId);
    }

    /**
     * @return mixed
     */
    public function getTagName()
    {
        return $this->getData('tag_name');
    }

    /**
     * @param $tagName
     * @return \Magento\Framework\DataObject
     */
    public function setTagName($tagName)
    {
        return $this->setData('tag_name', $tagName);
    }
}
