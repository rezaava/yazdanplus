<?php

namespace App\Http\Controllers;

use App\Models\stock_forms;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function index()
    {
        return view('survey');
    }

    public function survey()
    {
        $surveys = Survey::get();
        $user_count = Survey::count();
    
        $priceRange = $surveys->groupBy('price_range')->map->count();
    
        $monthlyPayment = $surveys->groupBy('monthly_payment')->map->count();
    
        $term = $surveys->groupBy('term')->map->count();
    
        $downPayment = $surveys->groupBy('down_payment')->map->count();
    
        return view('admin.new.survey', compact(
            'priceRange',
            'monthlyPayment',
            'term',
            'user_count',
            'downPayment'
        ));
    }

    public function submit(Request $request)
    {
        if ($request->website) {
            return response()->json([
                'success' => false,
                'message' => 'درخواست نامعتبر است.'
            ], 422);
        }

        $validated = $request->validate([
            'priceRange' => [
                'required',
                'in:under_50,50_80,80_120,120_150,over_150,undecided'
            ],

            'monthlyPayment' => [
                'required',
                'in:under_5,5_8,8_12,12_20,over_20'
            ],

            'term' => [
                'required',
                'in:6,12,18,24,36,depends'
            ],

            'downPayment' => [
                'required',
                'in:none,under_25,25_50,over_50,depends'
            ],
        ]);


       $user = Auth::user();

Survey::create([
    'user_id' => $user ? $user->id : null,
    'price_range' => $validated['priceRange'],
    'monthly_payment' => $validated['monthlyPayment'],
    'term' => $validated['term'],
    'down_payment' => $validated['downPayment'],
]);

        return response()->json([
            'success' => true,
            'message' => 'پاسخ شما با موفقیت ثبت شد.'
        ]);
    }

    public function stock(){
        $stocks=stock_forms::get();
        return view('admin.new.stock_forms',compact('stocks'));
    }
}