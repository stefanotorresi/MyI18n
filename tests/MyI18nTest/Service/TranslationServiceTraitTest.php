<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Service;

use Gedmo\Translatable\Entity\Repository\TranslationRepository;
use MyI18nTest\TestAsset;
use PHPUnit_Framework_TestCase;

/**
 * Class LocaleHelperTraitTest
 * @package MyI18nTest\Service
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
     * @var TranslationRepository $translationRepository
     */
    protected $translationRepository;

    public function setUp()
    {
        $this->translationService = new TestAsset\TranslationService;

        $entityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();

        $translationRepository = $this->getMockBuilder('Gedmo\Translatable\Entity\Repository\TranslationRepository')
            ->disableOriginalConstructor()->getMock();

        $entityManager->expects($this->any())
            ->method('getRepository')
            ->with('MyI18n\Entity\Translation')
            ->will($this->returnValue($translationRepository));

        $this->translationRepository = $translationRepository;

        $this->translationService->setEntityManager($entityManager);
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

        $this->translationRepository->expects($this->at(0))
            ->method('translate')
            ->with($entity, 'field', 'en', 'value');

        $this->translationRepository->expects($this->at(1))
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

        $this->translationRepository->expects($this->once())
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
