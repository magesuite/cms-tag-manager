<?php
namespace MageSuite\CmsTagManager\Test\Integration\Model;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class TagsRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\CmsTagManager\Service\Processor\SaveTags
     */
    protected $saveProcessor;

    /**
     * @var \MageSuite\CmsTagManager\Api\TagsRepositoryInterface
     */
    protected $tagsRepository;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->saveProcessor = $this->objectManager->create(\MageSuite\CmsTagManager\Service\Processor\SaveTags::class);

        $this->tagsRepository = $this->objectManager->create(\MageSuite\CmsTagManager\Api\TagsRepositoryInterface::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadPages
     */
    public function testTagsRepository()
    {
        $saveProcessor = $this->saveProcessor;

        foreach ($this->dummyCmsPagesTags() as $dummyTag) {
            $saveProcessor->processSave($dummyTag);
        }
        $this->itReturnTagsByCmsPageId();
        $this->itReturnsCmsPagesByTagName();
        $this->itReturnsTag();
        $this->itReturnsAllTags();
        $this->itReturnsCmsPageCollectionByTags();
    }

    protected function itReturnTagsByCmsPageId()
    {
        $tagsRepository = $this->tagsRepository;

        $expectedTags = $this->expectedTags();
        $this->assertEquals($expectedTags[2], $tagsRepository->getTagsByCmsPageId(2));
        $this->assertEquals($expectedTags[3], $tagsRepository->getTagsByCmsPageId(3));
        $this->assertEquals($expectedTags[4], $tagsRepository->getTagsByCmsPageId(4));
    }

    protected function itReturnsCmsPagesByTagName()
    {
        $tagsRepository = $this->tagsRepository;

        $this->assertEquals($tagsRepository->getCmsPagesByTagName('one'), [2,3]);
        $this->assertEquals($tagsRepository->getCmsPagesByTagName('two'), [2,4]);
        $this->assertEquals($tagsRepository->getCmsPagesByTagName('five'), [3]);
        $this->assertEquals($tagsRepository->getCmsPagesByTagName('four'), [3,4]);
        $this->assertEquals($tagsRepository->getCmsPagesByTagName('five'), [3]);
        $this->assertEquals($tagsRepository->getCmsPagesByTagName('not existing tag'), []);
    }

    protected function itReturnsTag()
    {
        $tagsRepository = $this->tagsRepository;

        $tag = $tagsRepository->getTag(2, 'two');

        $this->assertInstanceOf(\MageSuite\CmsTagManager\Model\Tags::class, $tag);

        $tag = $tagsRepository->getTag(2, 'not existing tag');

        $this->assertNull($tag);
    }

    protected function itReturnsAllTags()
    {
        $allTags = $this->tagsRepository->getAllTags();
        $this->assertEquals(['double tag', 'five', 'four', 'nine', 'one', 'second', 'test tag', 'third', 'three', 'two'], $allTags);
    }

    protected function itReturnsCmsPageCollectionByTags()
    {
        $tagsCollection = $this->tagsRepository->getCmsPageCollectionByTags(['two']);

        $this->assertInstanceOf(\Magento\Cms\Model\ResourceModel\Page\Collection::class, $tagsCollection);

        $this->assertEquals(2, $tagsCollection->getSize());
    }

    private function dummyCmsPagesTags()
    {
        return [
            [
                'page_id' => 2,
                'page_tags' => 'one,two,three'
            ],
            [
                'page_id' => 3,
                'page_tags' => 'one,five,four'
            ],
            [
                'page_id' => 4,
                'page_tags' => 'two,four,nine'
            ],

        ];
    }

    private function expectedTags()
    {
        return [
            2 => [
                'one',
                'two',
                'three'
            ],
            3 => [
                'one',
                'five',
                'four'
            ],
            4 => [
                'two',
                'four',
                'nine'
            ]
        ];
    }

    public static function loadPages()
    {
        include __DIR__.'/../../_files/pages.php';
    }
}
