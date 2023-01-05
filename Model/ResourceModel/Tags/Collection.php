<?php

namespace MageSuite\CmsTagManager\Model\ResourceModel\Tags;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \MageSuite\CmsTagManager\Model\Tags::class,
            \MageSuite\CmsTagManager\Model\ResourceModel\Tags::class
        );
    }
}
