<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Hierachy cell column (used for detailInit).
 *
 * @author Tomas Saghy <segy@riesenia.com>
 *
 * @see http://demos.telerik.com/kendo-ui/grid/hierarchy
 */
class HierarchyCell extends Base
{
    /**
     * Column template with %field% placeholder.
     *
     * @var string
     */
    protected $_template = '<td class="%class%" style="%style%"><div><a class="k-icon k-i-expand" href="\\#" tabindex="-1"></a></div></td>';

    /**
     * Predefined class.
     *
     * @var string
     */
    protected $_class = 'k-hierarchy-cell';

    /**
     * Predefined style.
     *
     * @var string
     */
    protected $_style = '';
}
