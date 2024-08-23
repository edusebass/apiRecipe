<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

        $recipe = Recipe::create($request->all());

        if($tags = json_decode($request->tags)) {
            $recipe->tags()->attach($tags);
        }
        return response()->json(new RecipeResource($recipe), Response::HTTP_CREATED);
    }

    public function update(Request $request, Recipe $recipe)
    {
        //
        $recipe->update($request->all());

        if($tags = json_decode($request->tags)) {
            $recipe->tags()->async($tags);
        }
        return response()->json(new RecipeResource($recipe), Response::HTTP_OK);
    }
    public function destroy(Recipe $recipe)
    {
        //
        $recipe->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
