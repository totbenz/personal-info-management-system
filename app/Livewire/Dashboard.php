<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Personnel;
use App\Models\School;
use App\Models\District;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard');
    }

    public function yearlyReport()
    {
        $sales = Sale::select(DB::raw("SUM(total) as total_sum"), DB::raw("MONTHNAME(created_at) as month_name"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(DB::raw("MONTHNAME(created_at)"))
                    ->orderByRaw("MONTH(created_at)")
                    ->pluck('total_sum', 'month_name');

        $sales_labels = $sales->keys();
        $sales_data = $sales->values();

        $purchases = PurchaseOrder::join('purchase_deliveries', 'purchase_orders.id', '=', 'purchase_deliveries.purchase_order_id')
        ->select(DB::raw("SUM(purchase_orders.total) as total_sum"), DB::raw("MONTHNAME(purchase_orders.created_at) as month_name"))
        ->whereYear('purchase_orders.created_at', date('Y'))
        ->where('purchase_deliveries.purchase_status', 'Delivered')
        ->groupBy(DB::raw("MONTHNAME(purchase_orders.created_at)"))
        ->orderByRaw("MONTH(purchase_orders.created_at)")
        ->pluck('total_sum', 'month_name');

        $purchases_labels = $purchases->keys();
        $purchases_data = $purchases->values();

        return [$sales_labels, $sales_data, $purchases_labels, $purchases_data];
    }
}
