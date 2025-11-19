<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display the FAQ page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $faqs = Faq::with('category')->get();
        $categories = FaqCategory::all();
        
        return view('pages.faq', compact('faqs', 'categories'));
    }
}
