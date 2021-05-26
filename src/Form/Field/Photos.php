<?php

namespace Lake\FormMedia\Form\Field;

use Lake\FormMedia\Form\Field;

/**
 * 表单多图字段
 *
 * @create 2021-05-26
 * @author halyang92
 */
class Photos extends Field
{
    protected $limit = 99;
    protected $remove = true;
    protected $type = 'image';
}
