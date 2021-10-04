<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * ComboBox helper.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class ComboBox extends KendoHelper
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $id)
    {
        $this->_id = $id;

        $this->dataSource = Kendo::createDataSource()
            ->setSchema(['data' => 'results', 'total' => 'count'])
            ->setServerFiltering(true)
            ->setServerPaging(true);

        $this->widget = Kendo::createComboBox('#' . $id)
            ->setDataSource($this->dataSource)
            ->setDataValueField('id')
            ->setDataTextField('name');

        $this->addAttribute('name', $id);
    }

    /**
     * {@inheritdoc}
     */
    public function html(): string
    {
        return $this->_input($this->_id);
    }

    /**
     * {@inheritdoc}
     */
    public function script(): string
    {
        $script = $this->widget->__toString();

        return $script;
    }
}
