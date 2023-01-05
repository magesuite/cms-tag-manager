<?php

namespace MageSuite\CmsTagManager\Plugin\Cms\Model\Page\Collection;

class AroundFilterByTag
{
    protected \MageSuite\CmsTagManager\Api\TagsRepositoryInterface $tagsRepository;
    protected \MageSuite\CmsTagManager\Model\TagsFactory $tagsFactory;

    public function __construct(
        \MageSuite\CmsTagManager\Api\TagsRepositoryInterface $tagsRepository,
        \MageSuite\CmsTagManager\Model\TagsFactory $tagsFactory
    ) {
        $this->tagsRepository = $tagsRepository;
        $this->tagsFactory = $tagsFactory;
    }

    public function aroundAddFieldToFilter(\Magento\Cms\Model\ResourceModel\Page\Collection $subject, callable $proceed, $field, $condition)
    {
        if ($field != 'cms_page_tags' || !isset($condition['in'])) {
            return $proceed($field, $condition);
        }

        $pageIds = $this->tagsRepository->getCmsPageIdsByTags($condition['in']);
        $subject->addFieldToFilter('page_id', ['in' => $pageIds]);

        return $subject;
    }
}
