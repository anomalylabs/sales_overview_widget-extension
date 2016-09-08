<?php namespace Anomaly\SalesOverviewWidgetExtension;

use Anomaly\DashboardModule\Widget\Contract\WidgetInterface;
use Anomaly\DashboardModule\Widget\Extension\WidgetExtension;
use Anomaly\SalesOverviewWidgetExtension\Command\LoadSalesOverview;

/**
 * Class SalesOverviewWidgetExtension
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\SalesOverviewWidgetExtension
 */
class SalesOverviewWidgetExtension extends WidgetExtension
{

    /**
     * This extension provides the "Top Products"
     * products module widget for the dashboard module.
     *
     * @var null|string
     */
    protected $provides = 'anomaly.module.dashboard::widget.sales_overview';

    /**
     * Load the widget data.
     *
     * @param WidgetInterface $widget
     */
    protected function load(WidgetInterface $widget)
    {
        $this->dispatch(new LoadSalesOverview($widget));
    }

}
