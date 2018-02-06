<?php declare(strict_types=1);

namespace Shopware\Api\Category\Definition;

use Shopware\Api\Category\Collection\CategoryTranslationBasicCollection;
use Shopware\Api\Category\Collection\CategoryTranslationDetailCollection;
use Shopware\Api\Category\Event\CategoryTranslation\CategoryTranslationDeletedEvent;
use Shopware\Api\Category\Event\CategoryTranslation\CategoryTranslationWrittenEvent;
use Shopware\Api\Category\Repository\CategoryTranslationRepository;
use Shopware\Api\Category\Struct\CategoryTranslationBasicStruct;
use Shopware\Api\Category\Struct\CategoryTranslationDetailStruct;
use Shopware\Api\Entity\EntityDefinition;
use Shopware\Api\Entity\EntityExtensionInterface;
use Shopware\Api\Entity\Field\FkField;
use Shopware\Api\Entity\Field\LongTextField;
use Shopware\Api\Entity\Field\ManyToOneAssociationField;
use Shopware\Api\Entity\Field\StringField;
use Shopware\Api\Entity\FieldCollection;
use Shopware\Api\Entity\Write\Flag\PrimaryKey;
use Shopware\Api\Entity\Write\Flag\Required;
use Shopware\Api\Shop\Definition\ShopDefinition;

class CategoryTranslationDefinition extends EntityDefinition
{
    /**
     * @var FieldCollection
     */
    protected static $primaryKeys;

    /**
     * @var FieldCollection
     */
    protected static $fields;

    /**
     * @var EntityExtensionInterface[]
     */
    protected static $extensions = [];

    public static function getEntityName(): string
    {
        return 'category_translation';
    }

    public static function getFields(): FieldCollection
    {
        if (self::$fields) {
            return self::$fields;
        }

        self::$fields = new FieldCollection([
            (new FkField('category_id', 'categoryId', CategoryDefinition::class))->setFlags(new PrimaryKey(), new Required()),
            (new FkField('language_id', 'languageId', ShopDefinition::class))->setFlags(new PrimaryKey(), new Required()),
            (new StringField('name', 'name'))->setFlags(new Required()),
            new LongTextField('path_names', 'pathNames'),
            new LongTextField('meta_keywords', 'metaKeywords'),
            new StringField('meta_title', 'metaTitle'),
            new LongTextField('meta_description', 'metaDescription'),
            new StringField('cms_headline', 'cmsHeadline'),
            new LongTextField('cms_description', 'cmsDescription'),
            new ManyToOneAssociationField('category', 'category_id', CategoryDefinition::class, false),
            new ManyToOneAssociationField('language', 'language_id', ShopDefinition::class, false),
        ]);

        foreach (self::$extensions as $extension) {
            $extension->extendFields(self::$fields);
        }

        return self::$fields;
    }

    public static function getRepositoryClass(): string
    {
        return CategoryTranslationRepository::class;
    }

    public static function getBasicCollectionClass(): string
    {
        return CategoryTranslationBasicCollection::class;
    }

    public static function getDeletedEventClass(): string
    {
        return CategoryTranslationDeletedEvent::class;
    }

    public static function getWrittenEventClass(): string
    {
        return CategoryTranslationWrittenEvent::class;
    }

    public static function getBasicStructClass(): string
    {
        return CategoryTranslationBasicStruct::class;
    }

    public static function getTranslationDefinitionClass(): ?string
    {
        return null;
    }

    public static function getDetailStructClass(): string
    {
        return CategoryTranslationDetailStruct::class;
    }

    public static function getDetailCollectionClass(): string
    {
        return CategoryTranslationDetailCollection::class;
    }
}