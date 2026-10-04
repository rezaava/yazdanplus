<?php

namespace App\Http\Controllers;

use App\Exports\ArdeSaleExport;
use App\Exports\HoneySaleExport;
use App\Models\Product_orders;
use App\Models\Products;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class productController extends Controller
{
    public function sale_arde()
{
    $products = Products::where('status', 1)
        ->where('type', 2)
        ->get();

    $productOrders = Product_orders::with([
        'order.user',
        'product'
    ])
    ->whereHas('order', function ($query) {
        $query->where('shop_id', 8)
            ->where('status', 11);
    })
    ->orderBy('id', 'desc')
    ->get();

    $grouped = $productOrders->groupBy(function ($item) {
        return $item->order->user_id . '_' . $item->order->datis_turn;
    });

    $rows = [];

    foreach ($grouped as $orders) {

        $firstOrder = $orders->first();

        if (!$firstOrder || !$firstOrder->order || !$firstOrder->order->user) {
            continue;
        }

        $user = $firstOrder->order->user;
        $order = $firstOrder->order;

        $row = [
            'name' => $user->name ?? '',
            'family' => $user->family ?? '',
            'mobile' => $user->mobile ?? '',
            'datis_turn' => $order->datis_turn ?? '',
        ];

        foreach ($products as $product) {
            $row['product_' . $product->id] = $orders
                ->where('product_id', $product->id)
                ->sum('num');
        }

        $rows[] = $row;
    }

    return view('admin.list_products.list_arde', compact(
        'products',
        'rows'
    ));
}

public function sale_honey()
{
    $products = Products::where('status', 1)
        ->where('type', 3)
        ->get();

    $productOrders = Product_orders::with([
        'order.user',
        'product'
    ])
    ->whereHas('order', function ($query) {
        $query->where('shop_id', 8)
            ->where('status', 12);
    })
    ->orderBy('id', 'desc')
    ->get();

    $grouped = $productOrders->groupBy(function ($item) {
        return $item->order->user_id . '_' . $item->order->datis_turn;
    });

    $rows = [];

    foreach ($grouped as $orders) {

        $firstOrder = $orders->first();

        if (!$firstOrder || !$firstOrder->order || !$firstOrder->order->user) {
            continue;
        }

        $user = $firstOrder->order->user;
        $order = $firstOrder->order;

        $row = [
            'name' => $user->name ?? '',
            'family' => $user->family ?? '',
            'mobile' => $user->mobile ?? '',
            'datis_turn' => $order->datis_turn ?? '',
        ];

        foreach ($products as $product) {
            $row['product_' . $product->id] = $orders
                ->where('product_id', $product->id)
                ->sum('num');
        }

        $rows[] = $row;
    }

    return view('admin.list_products.list_honey', compact(
        'products',
        'rows'
    ));
}

public function sale_kerem()
{
    $products = Products::where('status', 1)
        ->where('type', 4)
        ->get();

    $productOrders = Product_orders::with([
        'order.user',
        'product'
    ])
    ->whereHas('order', function ($query) {
        $query->where('shop_id', 8)
            ->where('status', 13);
    })
    ->orderBy('id', 'desc')
    ->get();

    $grouped = $productOrders->groupBy(function ($item) {
        return $item->order->user_id . '_' . $item->order->datis_turn;
    });

    $rows = [];

    foreach ($grouped as $orders) {

        $firstOrder = $orders->first();

        if (!$firstOrder || !$firstOrder->order || !$firstOrder->order->user) {
            continue;
        }

        $user = $firstOrder->order->user;
        $order = $firstOrder->order;

        $row = [
            'name' => $user->name ?? '',
            'family' => $user->family ?? '',
            'mobile' => $user->mobile ?? '',
            'datis_turn' => $order->datis_turn ?? '',
        ];

        foreach ($products as $product) {
            $row['product_' . $product->id] = $orders
                ->where('product_id', $product->id)
                ->sum('num');
        }

        $rows[] = $row;
    }

    return view('admin.list_products.list_kerem', compact(
        'products',
        'rows'
    ));
}

public function sale_aroosha()
{
    $products = Products::where('status', 1)
        ->where('type', 5)
        ->get();

    $productOrders = Product_orders::with([
        'order.user',
        'product'
    ])
    ->whereHas('order', function ($query) {
        $query->where('shop_id', 8)
            ->where('status', 14);
    })
    ->orderBy('id', 'desc')
    ->get();

    $grouped = $productOrders->groupBy(function ($item) {
        return $item->order->user_id . '_' . $item->order->datis_turn;
    });

    $rows = [];

    foreach ($grouped as $orders) {

        $firstOrder = $orders->first();

        if (!$firstOrder || !$firstOrder->order || !$firstOrder->order->user) {
            continue;
        }

        $user = $firstOrder->order->user;
        $order = $firstOrder->order;

        $row = [
            'name' => $user->name ?? '',
            'family' => $user->family ?? '',
            'mobile' => $user->mobile ?? '',
            'datis_turn' => $order->datis_turn ?? '',
        ];

        foreach ($products as $product) {
            $row['product_' . $product->id] = $orders
                ->where('product_id', $product->id)
                ->sum('num');
        }

        $rows[] = $row;
    }

    return view('admin.list_products.list_aroosha', compact(
        'products',
        'rows'
    ));
}

public function ardeSaleExcel()
{
    return Excel::download(
        new ArdeSaleExport,
        'فروش-ارده.xlsx'
    );
}

public function honeySaleExcel()
{
    return Excel::download(
        new HoneySaleExport,
        'فروش-عسل.xlsx'
    );
}
}
