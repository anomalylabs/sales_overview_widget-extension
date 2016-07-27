<?php namespace Anomaly\SalesOverviewWidgetExtension\Command;

use Anomaly\DashboardModule\Widget\Contract\WidgetInterface;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Database\DatabaseManager;

/**
 * Class LoadSalesOverview
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\SalesOverviewWidgetExtension\Command
 */
class LoadSalesOverview implements SelfHandling
{

    /**
     * The widget interface.
     *
     * @var WidgetInterface
     */
    protected $widget;

    /**
     * Create a new LoadSalesOverview instance.
     *
     * @param WidgetInterface $widget
     */
    public function __construct(WidgetInterface $widget)
    {
        $this->widget = $widget;
    }

    /**
     * Handle the command.
     *
     * @param DatabaseManager $database
     */
    public function handle(DatabaseManager $database)
    {
        $orders = $database
            ->table('orders_orders')
            ->select(
                [
                    $database->raw('COUNT(id) AS count'),
                    $database->raw('DATE(created_at) AS date')
                ]
            )
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->where('created_at', '<=', date('Y-m-d H:i:s'))
            ->where('status', 'complete')
            ->orderBy('created_at', 'ASC')
            ->groupBy('date')
            ->get();

        $revenue = $database
            ->table('orders_orders')
            ->select(
                [
                    $database->raw('SUM(total) AS revenue'),
                    $database->raw('DATE(created_at) AS date')
                ]
            )
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->where('created_at', '<=', date('Y-m-d H:i:s'))
            ->where('status', 'complete')
            ->orderBy('created_at', 'ASC')
            ->groupBy('date')
            ->get();

        for ($days = 29; $days >= 0; $days--) {

            $date = date('Y-m-d', strtotime('-' . $days . ' days'));

            $exists = array_filter(
                $orders,
                function ($order) use ($date) {
                    return $order->date == $date;
                }
            );

            if (!$exists) {
                $orders[] = (object)['count' => 0, 'date' => $date];
            }

            $exists = array_filter(
                $revenue,
                function ($sale) use ($date) {
                    return $sale->date == $date;
                }
            );

            if (!$exists) {
                $revenue[] = (object)['revenue' => 0, 'date' => $date];
            }
        }

        array_walk(
            $revenue,
            function ($sale) {
                return $sale->revenue = (int)$sale->revenue;
            }
        );

        uasort($orders, function($a, $b) {
            return $a->date > $b->date;
        });

        uasort($revenue, function($a, $b) {
            return $a->date > $b->date;
        });

        $this->widget->addData('orders', array_values($orders));
        $this->widget->addData('revenue', array_values($revenue));
    }
}
