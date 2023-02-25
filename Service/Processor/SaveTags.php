<?php
namespace MageSuite\CmsTagManager\Service\Processor;

class SaveTags
{
    protected \MageSuite\CmsTagManager\Api\TagsRepositoryInterface $tagsRepository;
    protected \MageSuite\CmsTagManager\Model\TagsFactory $tags;

    public function __construct(
        \MageSuite\CmsTagManager\Api\TagsRepositoryInterface $tagsRepository,
        \MageSuite\CmsTagManager\Model\TagsFactory $tags
    ) {
        $this->tagsRepository = $tagsRepository;
        $this->tags = $tags;
    }

    public function processSave(array $data)
    {
        if (!isset($data['page_tags']) || $data['page_tags'] === null) {
            return;
        }

        $postTagsArray = explode(',', $data['page_tags']);

        $pageTags = $this->tagsRepository->getTagsByCmsPageId($data['page_id']);

        $tagsToSkip = [];
        foreach ($pageTags as $tag) {
            if (!in_array($tag, $postTagsArray)) {
                $tag = $this->tagsRepository->getTag($data['page_id'], $tag);
                $this->tagsRepository->delete($tag);
            }

            $tagsToSkip[] = $tag;
        }

        foreach ($postTagsArray as $postTag) {
            if (!$postTag) {
                continue;
            }
            if (in_array($postTag, $tagsToSkip)) {
                continue;
            }

            $newTag = $this->tags->create();

            $newTag->setCmsPageId($data['page_id']);
            $newTag->setTagName($postTag);
            $this->tagsRepository->save($newTag);
        }
    }
}
