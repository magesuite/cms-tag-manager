<?php

/** @var $page \Magento\Cms\Model\Page */

$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Cms\Model\Page::class);
$page->setTitle('Cms Test Tag Page1')
    ->setId(1)
    ->setIdentifier('page_test_tag1')
    ->setStores([0])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page Design Blank Title1</h1>')
    ->setPageLayout('1column')
    ->setCmsPageTags('test tag,second')
    ->setCmsImageTeaser('image1.jpg')
    ->save();

$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Cms\Model\Page::class);
$page->setTitle('Cms Test Tag Page2')
    ->setIdentifier('page_test_tag2')
    ->setId(2)
    ->setStores([0])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page Design Blank Title2</h1>')
    ->setPageLayout('1column')
    ->setCmsPageTags('test tag,double tag')
    ->setCmsImageTeaser('image2.jpg')
    ->save();

$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Cms\Model\Page::class);
$page->setTitle('Cms Test Tag Page3')
    ->setIdentifier('page_test_tag3')
    ->setId(3)
    ->setStores([0])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page Design Blank Title3</h1>')
    ->setPageLayout('1column')
    ->setCmsPageTags('second,third,double tag')
    ->setCmsImageTeaser('image3.jpg')
    ->save();

$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Cms\Model\Page::class);
$page->setTitle('Cms Test Tag Page4')
    ->setIdentifier('page_test_tag4')
    ->setId(4)
    ->setStores([0])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page Design Blank Title4</h1>')
    ->setPageLayout('1column')
    ->setCmsPageTags('double tag')
    ->setCmsImageTeaser('image4.jpg')
    ->save();

$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Cms\Model\Page::class);
$page->setTitle('Cms Test Tag Page5')
    ->setIdentifier('page_test_tag5')
    ->setId(5)
    ->setStores([0])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page Design Blank Title5</h1>')
    ->setPageLayout('1column')
    ->setCmsPageTags('first,second')
    ->setCmsImageTeaser('image5.jpg')
    ->save();

