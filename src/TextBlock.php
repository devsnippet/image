<?php
namespace Zodream\Image;


use Zodream\Disk\File;
use Zodream\Service\Factory;

/**
 * Class TextBlock
 * @package Zodream\Image
[字体]<>
[字体,颜色]
[颜色]
[s:字体,b:背景,f:字体,c:颜色,h:高,w:宽,l:左边,t:顶部,r:右边,b:底部]
[]>< 文件
 */
class TextBlock {
    protected $fontSize = 14;

    protected $background;

    protected $color = '#000';

    protected $font;

    protected $width;

    protected $height;

    protected $left;

    protected $top;

    protected $right;

    protected $bottom;

    /**
     * @var string|File
     */
    protected $content;


    /**
     * @param int $size
     * @return TextBlock
     */
    public function setFontSize($size) {
        $this->fontSize = intval($size);
        return $this;
    }

    /**
     * @param mixed $background
     * @return TextBlock
     */
    public function setBackground($background) {
        $this->background = $background;
        return $this;
    }

    /**
     * @param string $color
     */
    public function setColor($color) {
        $this->color = $color;
        return $this;
    }

    /**
     * @param mixed $font
     */
    public function setFont($font) {
        $this->font = $font;
        return $this;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width) {
        $this->width = $width;
        return $this;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height) {
        $this->height = $height;
        return $this;
    }

    /**
     * @param mixed $left
     */
    public function setLeft($left) {
        $this->left = $left;
        return $this;
    }

    /**
     * @param mixed $top
     */
    public function setTop($top) {
        $this->top = $top;
        return $this;
    }

    /**
     * @param mixed $right
     */
    public function setRight($right) {
        $this->right = $right;
        return $this;
    }

    /**
     * @param mixed $bottom
     */
    public function setBottom($bottom) {
        $this->bottom = $bottom;
        return $this;
    }

    /**
     * @param string|File $content
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function setBoxSize($width, $height) {
        return $this->setWidth($width)->setHeight($height);
    }

    public function setBoxBound($x, $y, $width, $height) {
        return $this->setLeft($x)->setTop($y)->setBoxSize($width, $height);
    }

    public function setProperty($tag, $value) {
        if ($tag == 's' || $tag == 'size' || $tag == 'font-size') {
            return $this->setFontSize($value);
        }

        if ($tag == 'b' || $tag == 'background') {
            return $this->setBackground($value);
        }

        if ($tag == 'c' || $tag == 'color' || $tag == 'foreground') {
            return $this->setColor($value);
        }

        if ($tag == 'f' || $tag == 'font' || $tag == 'font-family') {
            return $this->setFont($value);
        }

        if ($tag == 'w' || $tag == 'width') {
            return $this->setWidth($value);
        }

        if ($tag == 'h' || $tag == 'height') {
            return $this->setHeight($value);
        }

        if ($tag == 'l' || $tag == 'x' || $tag == 'left') {
            return $this->setLeft($value);
        }

        if ($tag == 't' || $tag == 'y' || $tag == 'top') {
            return $this->setTop($value);
        }

        if ($tag == 'r' || $tag == 'right') {
            return $this->setRight($value);
        }

        if ($tag == 'bottom') {
            return $this->setRight($value);
        }
        return $this;
    }


    public static function parseProperty($property, $content) {
        $block = new static();
        $block->setContent($content);
        if (empty($property)) {
            return $block;
        }
        $args = explode(',', $property);
        $count = count($args);
        if (strpos($property, ':') > 0) {
            foreach ($args as $item) {
                $block->setProperty(...explode(':', $item, 2));
            }
            return $block;
        }
        if ($count == 1) {
            return is_numeric($property)
                ? $block->setFontSize($property)
                : $block->setColor($property);
        }
        if ($count == 2) {
            return $block->setFontSize($args[0])->setColor($args[1]);
        }
        return $block->setBoxBound(...$args);
    }

    public static function parseImageProperty($property, $content) {
        $block = new static();
        $block->setContent(Factory::root()->file($content));
        if (empty($property)) {
            return $block;
        }
        $args = explode(',', $property);
        $count = count($args);
        if (strpos($property, ':') > 0) {
            foreach ($args as $item) {
                $block->setProperty(...explode(':', $item, 2));
            }
            return $block;
        }
        if ($count == 1) {
            return $block->setWidth($property);
        }
        if ($count == 2) {
            return $block->setBoxSize(...$args);
        }
        return $block->setBoxBound(...$args);
    }

    public static function parse($content) {
        $block_list = [];

    }

    public static function parseHtml($html) {

    }
}