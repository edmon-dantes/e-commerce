<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\SubOrder;
use Illuminate\Http\Request;

class SubOrderController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function pay(SubOrder $suborder)
    {
        $suborder->transactions()->create([
            'transaction_id' => uniqid('trans-'.$suborder->id),
            'amount_paid' => $suborder->grand_total,
            'commision' => 0.1 * $suborder->grand_total
        ]);

        return redirect()->to('/admin/transactions')->with('success', 'Transaction Created');
    }
}
