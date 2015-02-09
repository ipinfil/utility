<?php

namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Table helper
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Table extends KendoHelper
{
    /**
     * Row template
     *
     * @var string
     */
    protected $_rowTemplate;

    /**
     * Table columns
     *
     * @var array
     */
    protected $_columns;

    /**
     * Construct the table
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->model = Kendo::createModel()
            ->setId('id');

        $this->dataSource = Kendo::createDataSource()
            ->setSchema(['model' => $this->model, 'data' => 'results', 'total' => 'count'])
            ->setServerFiltering(true)
            ->setServerSorting(true)
            ->setServerPaging(true);

        $this->_widget = Kendo::createGrid('#' . $this->_id)
            ->setDataSource($this->dataSource)
            ->setSortable(['allowUnsort' => false]);

        $this->_rowTemplate = '<tr data-uid="#: uid #">';
        $this->_columns = [];
    }

    /**
     * Add transport (passed to datasource)
     *
     * @param string type
     * @param array options
     * @return Riesenia\Utility\Kendo\Table
     */
    public function addTransport($type, $options = [])
    {
        $this->dataSource->addTransport($type, $options);

        return $this;
    }

    /**
     * Add table column
     *
     * @param string field name
     * @param string column title
     * @param string type
     * @param array options
     * @return Riesenia\Utility\Kendo\Table
     */
    public function addColumn($field, $title = '&nbsp;', $type = null, $options = [])
    {
        // type can be a name of user defined class
        if (!class_exists($type) || !is_subclass_of($type, __NAMESPACE__ . '\\Table\\Column\\Base')) {
            // default class
            if (is_null($type)) {
                $type = 'base';
            }

            $type = __NAMESPACE__ . '\\Table\\Column\\' . ucfirst($type);

            if (!class_exists($type)) {
                throw new \BadMethodCallException("Invalid column class: " . $type);
            }
        }

        // field and title
        $options['field'] = $field;
        $options['title'] = $title;

        // create column class instance
        $column = new $type($options);

        $this->model->addField($field, $column->getModelOptions());
        $this->_widget->addColumns(null, $column->getColumnOptions());

        $this->_columns[] = $column;

        return $this;
    }

    /**
     * Return HTML
     *
     * @return string
     */
    public function html()
    {
        return '<div id="' . $this->_id . '"></div>';
    }

    /**
     * Return JavaScript
     *
     * @return string
     */
    public function script()
    {
        // complete row template
        $this->_widget->setRowTemplate($this->_rowTemplate . implode('', $this->_columns) . '</tr>');

        $script = $this->_widget->__toString();

        // add column scripts
        foreach ($this->_columns as $column) {
            $script .= $column->script();
        }

        return $script;
    }
}
