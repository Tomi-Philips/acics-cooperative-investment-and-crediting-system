<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AvailableCommodity;
use App\Models\UserCommodity;
use App\Models\CommodityTransaction;
use Illuminate\Support\Facades\Auth;

class CommodityController extends Controller
{
    /**
     * Display the user's commodity balance and transaction history.
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's commodity balances
        $userCommodities = UserCommodity::where('user_id', $user->id)->get();

        // Calculate total commodity balance
        $totalCommodityBalance = $userCommodities->sum('balance');

        // Get recent commodity transactions
        $commodityTransactions = CommodityTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('user.commodities.balance', compact('userCommodities', 'totalCommodityBalance', 'commodityTransactions'));
    }

    /**
     * Display available commodities for purchase (marketplace).
     */
    public function marketplace()
    {
        $availableCommodities = AvailableCommodity::where('status', 'active')->get();

        return view('user.commodities.marketplace', compact('availableCommodities'));
    }

    /**
     * Display details of a specific available commodity.
     */
    public function show($id)
    {
        $commodity = AvailableCommodity::findOrFail($id);
        $user = Auth::user();

        // Get user's total commodity balance
        $totalCommodityBalance = UserCommodity::where('user_id', $user->id)->sum('balance');

        return view('user.commodities.show', compact('commodity', 'totalCommodityBalance'));
    }
}
