<?php
namespace MageSuite\CmsTagManager\Model\Config\Source;

class TagsOptions implements \Magento\Framework\Option\ArrayInterface
{
    protected \MageSuite\CmsTagManager\Api\TagsRepositoryInterface $tagsRepository;

    public function __construct(
        \MageSuite\CmsTagManager\Api\TagsRepositoryInterface $tagsRepository
    ) {
        $this->tagsRepository = $tagsRepository;
    }

    public function toOptionArray(): array
    {
        $result = [];

        $allTags = $this->tagsRepository->getAllTags();

        foreach ($allTags as $tag) {
            $result[] = [
                'value' => $tag,
                'label' => $tag
            ];
        }

        return $result;
    }
}
