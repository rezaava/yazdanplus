<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Shop;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index($shopId)
    {
        $shop = Shop::findOrFail($shopId);
        $contracts = Contract::where('shop_id', $shopId)->orderBy('delay')->get();
        return view('admin.contract', compact('shop', 'contracts'));
    }

    public function store(Request $request, $shopId)
    {
        $request->validate([
            'delay' => 'required|integer|min:0',
            'off'   => 'required|integer|min:0|max:100',
        ]);

        Contract::create([
            'shop_id' => $shopId,
            'delay' => $request->delay,
            'off' => $request->off,
        ]);

        return back()->with('success', 'قرارداد با موفقیت ثبت شد');
    }
    public function delete($id){
        $contract=Contract::find($id);
        $contract->delete();
        return back();
    }
}
