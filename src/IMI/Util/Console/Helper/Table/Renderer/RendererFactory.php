<?php

namespace IMI\Util\Console\Helper\Table\Renderer;

class RendererFactory
{
    protected static $formats = array(
        'csv'  => 'IMI\Util\Console\Helper\Table\Renderer\CsvRenderer',
        'json' => 'IMI\Util\Console\Helper\Table\Renderer\JsonRenderer',
        'xml'  => 'IMI\Util\Console\Helper\Table\Renderer\XmlRenderer',
    );

    /**
     * @param string $format
     *
     * @return bool|RendererInterface
     */
    public function create($format)
    {
        $format = strtolower($format);
        if (isset(self::$formats[$format])) {
            $rendererClass = self::$formats[$format];
            return new $rendererClass;
        }

        return false;
    }

    /**
     * @return array
     */
    public static function getFormats()
    {
        return array_keys(self::$formats);
    }
}