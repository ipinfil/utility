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
 * Window helper.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Window extends KendoHelper
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $id)
    {
        $this->_id = $id;

        $this->widget = Kendo::createWindow('#' . $id)
            ->setWidth(700)
            ->setModal(true)
            ->setResizable(false)
            ->setVisible(false);
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

        // center window on open
        $script .= '$("#' . $this->_id . '").data("' . $this->widget->name() . '").bind("open", function (e) {
            $("#' . $this->_id . '").data("' . $this->widget->name() . '").center();
        });';

        // center window on resize
        $script .= '$(window).resize(function (e) {
            $("#' . $this->_id . '").data("' . $this->widget->name() . '").center();
        });';

        // define global function for loading content and opening window
        $script .= 'window.' . $this->_id . 'Open = function (title, url) {
            $("#' . $this->_id . '").data("' . $this->widget->name() . '").title(title);

            $.get(url, {}, function (data) {
                $("#' . $this->_id . '").data("' . $this->widget->name() . '").content(data).center().open();
            });

            return false;
        };';

        return $script;
    }
}
