<?php
namespace BlackBoxCode\Pando\ContentBundle\Annotation;

/**
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class UniqueDocument
{
    /** @var array */
    private $fields = [];


    /**
     * @param array $options
     */
    public function __construct($options)
    {
        if (isset($options['value'])) {
            $options['fields'] = $options['value'];
            unset($options['value']);
        }

        foreach ($options as $k => $v) {
            if (!property_exists($this, $k)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $k));
            }

            $this->$k = $v;
        }
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
}
