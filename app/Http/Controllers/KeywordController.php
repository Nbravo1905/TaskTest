<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Keyword::all());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['name' => 'required|string|unique:keywords']);
        $keyword = Keyword::create($request->only('name'));
        return response()->json($keyword, 201);
    }
}
