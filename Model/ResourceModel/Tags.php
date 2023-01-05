<?php

namespace MageSuite\CmsTagManager\Model\ResourceModel;

class Tags extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('cms_page_tags', 'entity_id');
    }
}
