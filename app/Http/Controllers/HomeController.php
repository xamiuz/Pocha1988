<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DeliveryZone;
use App\Models\MenuItem;
use App\Models\PaymentSetting;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the Pocha Landing Page and Food Menu.
     */
    public function index(Request $request): View
    {
        $categories = Category::where('is_active', true)
            ->with(['menuItems' => function ($query) {
                $query->where('is_available', true);
            }])
            ->orderBy('sort_order')
            ->get();

        $popularItems = MenuItem::where('is_available', true)
            ->where('is_popular', true)
            ->with('category')
            ->take(6)
            ->get();

        $deliveryZones = DeliveryZone::where('is_active', true)
            ->orderBy('district')
            ->get();

        $promos = Promo::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->get();

        $paymentSettings = PaymentSetting::current();

        return view('home', compact('categories', 'popularItems', 'deliveryZones', 'promos', 'paymentSettings'));
    }
}
