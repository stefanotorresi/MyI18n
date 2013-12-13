<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\DataMapper;

use MyBase\DataMapper\MapperInterface;
use MyI18n\Entity\Locale;

interface LocaleMapperInterface extends MapperInterface
{
    public function findOneByCode($code);
    public function findAll();
    public function findAllWithDefaultFirst();
    public function findLastById();
    public function findDefaultLocale();
    public function getAllCodesAsArray();
    public function makeDefault(Locale $locale);
}
