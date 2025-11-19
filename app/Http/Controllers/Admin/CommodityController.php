<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableCommodity;
use App\Models\UserCommodity; // Although user balance is separate, might be useful later
use Illuminate\Http\Request;

class CommodityController extends Controller
{
    /**
     * Display a listing of the available commodities.
     */
    public function index(Request $request)
    {
        $perPage = 10;
        $search = $request->get('search');

        // Build query with search functionality
        $query = AvailableCommodity::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('commodity_type', 'LIKE', "%{$search}%");
            });
        }

        $availableCommodities = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Append search parameter to pagination links
        $availableCommodities->appends(['search' => $search]);

        return view('admin.commodity.index', compact('availableCommodities', 'search'));
    }

    /**
     * Show the form for creating a new available commodity.
     */
    public function create()
    {
        return view('admin.commodity.create');
    }

    /**
     * Store a newly created available commodity in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'commodity_type' => 'required|string|in:Essential Commodity,Electronics',
            'status' => 'required|string|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Add validation for other relevant fields from available_commodities table
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('commodity_images', 'public');
            $data['image'] = $imagePath;
        }

        AvailableCommodity::create($data);

        return redirect()->route('admin.commodities.index')->with('success', 'Available commodity created successfully.');
    }

    /**
     * Display the specified available commodity.
     */
    public function show(AvailableCommodity $commodity)
    {
        return view('admin.commodity.show', compact('commodity'));
    }

    /**
     * Show the form for editing the specified available commodity.
     */
    public function edit(AvailableCommodity $commodity)
    {
        return view('admin.commodity.edit', compact('commodity'));
    }

    /**
     * Update the specified available commodity in storage.
     */
    public function update(Request $request, AvailableCommodity $commodity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'commodity_type' => 'required|string|in:Essential Commodity,Electronics',
            'status' => 'required|string|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('commodity_images', 'public');
            $data['image'] = $imagePath;
        }

        $commodity->update($data);

        return redirect()->route('admin.commodities.index')->with('success', 'Available commodity updated successfully.');
    }

    /**
     * Remove the specified available commodity from storage.
     */
    public function destroy(AvailableCommodity $commodity)
    {
        $commodity->delete();

        return redirect()->route('admin.commodities.index')->with('success', 'Available commodity deleted successfully.');
    }

    // Methods for managing user commodity balances would go elsewhere,
    // likely in a separate controller or within the User controller.
}
