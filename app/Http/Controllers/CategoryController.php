<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Store a new category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $request->user()->categories()->create($validated);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    /**
     * Delete a category.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $category = Category::where('user_id', $request->user()->id)->findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
