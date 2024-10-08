<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
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

    public function store(StoreRecipeRequest $request)
    {

        $recipe = $request->user()->recipes()->create($request->all());
        if($tags = json_decode($request->tags)) {
            $recipe->tags()->attach($tags);
        }

        $recipe->image = $request->file('image')->store('recipes', 'public');
        $recipe->save();

        return response()->json(new RecipeResource($recipe), Response::HTTP_CREATED);
    }

    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        //
        Gate::authorize('update', $recipe);
        $recipe->update($request->all());

        if($tags = json_decode($request->tags)) {
            $recipe->tags()->sync($tags);
        }
        return response()->json(new RecipeResource($recipe), Response::HTTP_OK);
    }
    public function destroy(Recipe $recipe)
    {
        Gate::authorize('delete', $recipe);

        $recipe->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
