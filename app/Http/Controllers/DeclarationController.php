<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Declaration;

class DeclarationController extends Controller
{
    public function index()
    {
        $declarations = Declaration::where('status', 'published')
            ->latest('published_at')
            ->with('aspirant')
            ->paginate(12);

        return view('declarations.index', compact('declarations'));
    }

    public function show(Declaration $declaration)
    {
        if ($declaration->status !== 'published') {
            abort(404);
        }
        
        $declaration->load('aspirant');
        
        return view('declarations.show', compact('declaration'));
    }
}
