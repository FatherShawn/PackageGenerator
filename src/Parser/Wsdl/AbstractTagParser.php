<?php
namespace WsdlToPhp\PackageGenerator\Parser\Wsdl;

use WsdlToPhp\PackageGenerator\DomHandler\AttributeHandler;
use WsdlToPhp\PackageGenerator\DomHandler\Wsdl\Wsdl as WsdlDocument;
use WsdlToPhp\PackageGenerator\DomHandler\Wsdl\Tag\AbstractTag as Tag;
use WsdlToPhp\PackageGenerator\Model\Struct;
use WsdlToPhp\PackageGenerator\Model\StructAttribute;
use WsdlToPhp\PackageGenerator\Model\Method;
use WsdlToPhp\PackageGenerator\Model\Wsdl;
use WsdlToPhp\PackageGenerator\Model\Schema;
use WsdlToPhp\PackageGenerator\Generator\Generator;
use WsdlToPhp\PackageGenerator\Model\AbstractModel;
use WsdlToPhp\PackageGenerator\Model\StructValue;

abstract class AbstractTagParser extends AbstractParser
{
    /**
     * @return Generator
     */
    public function getGenerator()
    {
        return $this->generator;
    }
    /**
     * Return the model on which the method will be called
     * @param Tag $tag
     * @return Struct|Method
     */
    protected function getModel(Tag $tag)
    {
        switch ($tag->getName()) {
            case WsdlDocument::TAG_OPERATION:
                $model = $this->getMethodByName($tag->getAttributeName());
                break;
            default:
                $model = $this->getStructByName($tag->getAttributeName());
                break;
        }
        return $model;
    }
    /**
     * @param string $name
     * @return null|\WsdlToPhp\PackageGenerator\Model\Struct
     */
    protected function getStructByName($name)
    {
        return $this->generator->getStruct($name);
    }
    /**
     * @param string $name
     * @return null|\WsdlToPhp\PackageGenerator\Model\Method
     */
    protected function getMethodByName($name)
    {
        return $this->generator->getServiceMethod($name);
    }
    /**
     * Most of he time, this method is not used, even if it used,
     * for now, knowing that we are in a schema is not a useful information,
     * so we can simply parse the tag with only the wsdl as parameter
     * @see \WsdlToPhp\PackageGenerator\Parser\Wsdl\AbstractParser::parseSchema()
     */
    protected function parseSchema(Wsdl $wsdl, Schema $schema)
    {
        $this->parseWsdl($wsdl);
    }
    /**
     * @param Tag $tag
     * @param AbstractModel $model
     * @param StructAttribute $structAttribute
     */
    protected function parseTagAttributes(Tag $tag, AbstractModel $model = null, StructAttribute $structAttribute = null)
    {
        $model = $model instanceof AbstractModel ? $model : $this->getModel($tag);
        if ($model instanceof AbstractModel) {
            foreach ($tag->getAttributes() as $attribute) {
                $methodToCall = $this->getParseTagAttributeMethod($attribute->getName());
                if (is_array($methodToCall)) {
                    call_user_func_array($methodToCall, array(
                        $attribute,
                        $model,
                        $structAttribute,
                    ));
                } else {
                    $currentModel = $structAttribute instanceof StructAttribute ? $structAttribute : $model;
                    $currentModel->addMeta($attribute->getName(), $attribute->getValue(true));
                }
            }
        }
    }
    /**
     * @param string $tagName
     * @return string
     */
    protected function getParseTagAttributeMethod($tagName)
    {
        $methodName = sprintf('parseTagAttribute%s', ucfirst($tagName));
        if (method_exists($this, $methodName)) {
            return array($this, $methodName);
        }
        return null;
    }
    /**
     * @param AttributeHandler $tagAttribute
     * @param AbstractModel $model
     * @param StructAttribute $structAttribute
     */
    protected function parseTagAttributeType(AttributeHandler $tagAttribute, AbstractModel $model, StructAttribute $structAttribute = null)
    {
        if ($structAttribute instanceof StructAttribute) {
            $type = $tagAttribute->getValue();
            if ($type !== null) {
                $typeModel = $this->generator->getStruct($type);
                $modelAttributeType = $structAttribute->getType();
                if ($typeModel instanceof Struct && (empty($modelAttributeType) || strtolower($modelAttributeType) === 'unknown')) {
                    if ($typeModel->getIsRestriction()) {
                        $structAttribute->setType($typeModel->getName());
                    } elseif (!$typeModel->getIsStruct() && $typeModel->getInheritance()) {
                        $structAttribute->setType($typeModel->getInheritance());
                    }
                }
            }
        } else {
            $model->addMeta($tagAttribute->getName(), $tagAttribute->getValue(true));
        }
    }
    /**
     * Avoid this attribute to be added as meta
     */
    protected function parseTagAttributeName()
    {
    }
    /**
     * @param AttributeHandler $tagAttribute
     * @param AbstractModel $model
     */
    protected function parseTagAttributeAbstract(AttributeHandler $tagAttribute, AbstractModel $model)
    {
        $model->setIsAbstract($tagAttribute->getValue(false, true, 'bool'));
    }
    /**
     * Enumeration does not need its own value as meta information, it's like the name for struct attribute
     * @param AttributeHandler $tagAttribute
     * @param AbstractModel $model
     */
    protected function parseTagAttributeValue(AttributeHandler $tagAttribute, AbstractModel $model)
    {
        if (!$model instanceof StructValue) {
            $model->addMeta($tagAttribute->getName(), $tagAttribute->getValue(true));
        }
    }
    /**
     * @see \WsdlToPhp\PackageGenerator\Parser\AbstractParser::getName()
     * @return string
     */
    public function getName()
    {
        return $this->parsingTag();
    }
}
