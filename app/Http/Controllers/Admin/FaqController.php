<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\FaqCategory;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $allCategories = FaqCategory::all(); // Fetch all categories for the form

        $categories = FaqCategory::with(['faqs' => function ($query) use ($search) {
            if ($search) {
                $query->where('question', 'like', '%' . $search . '%')
                      ->orWhere('answer', 'like', '%' . $search . '%');
            }
        }])->get();

        // Filter out categories that have no FAQs after searching
        $categories = $categories->filter(function ($category) {
            return $category->faqs->count() > 0;
        });

        return view('admin.faqs.index', compact('categories', 'search', 'allCategories'));
    }

    /**
     * Store a newly created FAQ category in storage.
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:faq_categories|max:255',
        ]);

        FaqCategory::create($request->only('name'));

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ category created successfully.');
    }

    /**
     * Store a newly created FAQ in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|max:255',
            'answer' => 'required',
            'category_id' => 'required|exists:faq_categories,id',
            'is_important' => 'boolean',
        ]);

        // Handle the checkbox value
        $data = $request->all();
        $data['is_important'] = $request->has('is_important');

        Faq::create($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = FaqCategory::all();
        return view('admin.faqs.create', compact('categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        $categories = FaqCategory::all();
        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        // Debug the request
        Log::info('FAQ Update Request', [
            'request' => $request->all(),
            'faq_id' => $faq->id,
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        $request->validate([
            'question' => 'required|max:255',
            'answer' => 'required',
            'category_id' => 'required|exists:faq_categories,id',
            'is_important' => 'boolean',
        ]);

        // Handle the checkbox value
        $data = $request->all();
        $data['is_important'] = $request->has('is_important');

        $faq->update($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }
}
