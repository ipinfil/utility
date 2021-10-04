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
 * Tree helper.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Tree extends KendoHelper
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $id)
    {
        $this->_id = $id;

        $this->model = Kendo::createModel()
            ->setId('id');

        $this->dataSource = Kendo::createHierarchicalDataSource()
            ->setSchema(['model' => $this->model, 'data' => 'results', 'total' => 'count']);

        $this->widget = Kendo::createTreeView('#' . $id)
            ->setDataSource($this->dataSource)
            ->setDataTextField('name');
    }

    /**
     * Add hasChildren field to model.
     *
     * @param string $field
     *
     * @return $this
     */
    public function hasChildren(string $field = 'hasChildren'): self
    {
        $this->model->setHasChildren($field)
            ->addField($field, [
                'type' => 'boolean',
                'parse' => Kendo::js('function (d) {
                    return d > 0;
                }')
            ]);

        return $this;
    }

    /**
     * Add checkboxes.
     *
     * @param mixed|null $options
     *
     * @return $this
     */
    public function addCheckboxes($options = null): self
    {
        // default template
        if ($options === null) {
            $options = ['template' => '<input type="checkbox" name="tableCheckbox" value="#: item.id #" />'];
        }

        $this->widget->setCheckboxes($options);

        return $this;
    }

    /**
     * Expand tree through provided path.
     *
     * @param int[] $path from highest node to the last
     *
     * @return $this
     */
    public function expand(array $path = []): self
    {
        $this->widget->setDataBound(Kendo::js('function (e) {
            var expand = $("#' . $this->_id . '").data("expand");

            // initial
            if (typeof expand === "undefined") {
                expand = ' . \json_encode($path) . ';
            }

            if (expand.length) {
                var id = expand.shift();

                // select if last node or expand this node
                var node = this.findByUid(this.dataSource.get(id).uid);
                if (!expand.length) {
                    this.select(node);
                } else {
                    this.expand(node);
                }

                $("#' . $this->_id . '").data("expand", expand);
            }
        }'));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function html(): string
    {
        return $this->_div($this->_id);
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
