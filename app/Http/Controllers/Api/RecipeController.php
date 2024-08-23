<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    //
    public function index()
    {
        $recipes = Recipe::with('category', 'tags', 'user')->get();
        return RecipeResource::collection($recipes);
    }

    public function show(Recipe $recipe)
    {
        $recipes = $recipe->load('category', 'tags', 'user');
        return new RecipeResource($recipes);
    }

    public function store(Request $request)
    {
        //

        $recipe = Recipe::create($request->all());
        return $recipe;
    }

    public function update(Request $request, Recipe $recipe)
    {
        //
        $recipe->update($request->all());
        return $recipe;
    }

    public function destroy(Recipe $recipe)
    {
        //
        $recipe->delete();
        return $recipe;
    }
}
