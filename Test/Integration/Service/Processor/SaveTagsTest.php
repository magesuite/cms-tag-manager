<?php
namespace MageSuite\CmsTagManager\Test\Integration\Service\Processor;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class SaveTagsTest extends \PHPUnit\Framework\TestCase
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
    public function testItSavesTagsCorrectly()
    {
        $saveProcessor = $this->saveProcessor;

        $expectedTags = $this->expectedTags();

        foreach ($this->dummyCmsPagesTags() as $dummyTag) {
            $saveProcessor->processSave($dummyTag);

            $savedTags = $this->tagsRepository->getTagsByCmsPageId($dummyTag['page_id']);

            $this->assertEquals($expectedTags[$dummyTag['page_id']], $savedTags);
        }
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
            [
                'page_id' => 5,
                'page_tags' => ''
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
            ],
            5 => [],
        ];
    }

    public static function loadPages()
    {
        include __DIR__.'/../../../_files/pages.php';
    }
}
