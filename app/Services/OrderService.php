<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade as PDF;

class OrderService {

    public function createCMR($id) {
        $pdf = PDF::loadView('cmr.cmr', ['order' => Order::find($id)]);
        return $pdf->download('cmr_'.$id.'.pdf');
    }

}
