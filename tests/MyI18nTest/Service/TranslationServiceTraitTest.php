<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Service;

use MyI18n\DataMapper\TranslationMapperInterface;
use MyI18nTest\TestAsset;
use PHPUnit_Framework_TestCase;

/**
 * Class LocaleMapperPluginTraitTest
 * @package MyI18nTest\DataMapper
 *
 * @covers \MyI18n\Service\TranslationServiceTrait
 */
class TranslationServiceTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TestAsset\TranslationService $traitExhibitingObject
     */
    protected $translationService;

    /**
     * @var TranslationMapperInterface $translationRepository
     */
    protected $translationMapper;

    public function setUp()
    {
        $translationMapper = $this->getMock('MyI18n\Mapper\TranslationMapperInterface');

        $this->translationService = new TestAsset\TranslationService();
        $this->translationService->setTranslationMapper($translationMapper);

        $this->translationMapper = $translationMapper;
    }

    public function testTranslate()
    {
        $translations = [
            'en' => [
                'field' => 'value',
                'field2' => 'value2',
            ]
        ];

        $entity = new TestAsset\TranslatableEntity();

        $this->translationMapper->expects($this->at(0))
            ->method('translate')
            ->with($entity, 'field', 'en', 'value');

        $this->translationMapper->expects($this->at(1))
            ->method('translate')
            ->with($entity, 'field2', 'en', 'value2');

        $this->translationService->translate($entity, $translations);
    }

    public function testEmptyValuesAreSkipped()
    {
        $translations = [
            'en' => [
                'field' => 'value',
                'empty_field' => '',
            ]
        ];

        $entity = $this->getMock('MyI18n\Entity\TranslatableInterface');

        $this->translationMapper->expects($this->once())
            ->method('translate')
            ->with($entity, 'field', 'en', 'value');

        $this->translationService->translate($entity, $translations);
    }

    public function testEventManagerIntegration()
    {
        $eventManager = $this->getMock('Zend\EventManager\EventManager');

        $eventManager->expects($this->exactly(2))->method('trigger');

        $this->translationService->setEventManager($eventManager);

        $entity = $this->getMock('MyI18n\Entity\TranslatableInterface');

        $this->translationService->translate($entity, []);
    }

    public function testChangeLocale()
    {
        $entity = $this->getMock('MyI18n\Entity\TranslatableInterface');

        $locale = 'en';

        $entity->expects($this->once())
            ->method('setLocale')
            ->with($locale);

        $this->translationService->getEntityManager()
            ->expects($this->once())
            ->method('refresh')
            ->with($entity);

        $result = $this->translationService->changeLocale($entity, $locale);

        $this->assertSame($entity, $result);
    }
}
