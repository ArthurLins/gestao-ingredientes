<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Models\User;

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->get('me', 'AuthController@me');
    $router->get('logout', 'AuthController@logout');
});

$router->group([
    'prefix' => 'stock',
    'middleware'=> [
        'auth',
    ]
], function () use ($router) {

    $router->get('/list', 'StocksController@all');
    $router->post('create','StocksController@create');
    $router->delete("/", 'StocksController@delete');

    $router->group([
        'prefix' => '{stockId}',
        'middleware'=>[
            'stock.validator'
        ]
    ], function () use ($router){
        $router->get("/", 'StocksController@infos');

        $router->group(['prefix' => 'recipe'], function () use ($router) {
            $router->get("/list", 'RecipesController@all');
            $router->post("/create", 'RecipesController@create');
            $router->group([
                'prefix' => '{recipeId}',
                'middleware'=>[
                    'recipe.validator'
                ]
            ], function () use ($router) {
                //basic entity interactions
                $router->get("/", 'RecipesController@infos');
                $router->delete("/", 'RecipesController@delete');
                $router->put("/", 'RecipesController@edit');
                //Specific interactions
                $router->post("/make", 'RecipesController@make');
                $router->get("/canMake", 'RecipesController@canMake');
                $router->post("/undo", 'RecipesController@undo');

                //Recipe item
                $router->group(['prefix' => 'item'], function () use ($router) {
                    $router->post("create", 'RecipeItemsController@create');
                    $router->get("list", 'RecipeItemsController@all');
                    $router->group([
                        'prefix' => '{recipeItemId}',
                        'middleware'=>[
                            'recipe.item.validator'
                        ]
                    ], function () use ($router) {
                        $router->delete("/", 'RecipeItemsController@delete');
                        $router->put("/", 'RecipeItemsController@edit');
                    });
                });

            });
        });

        $router->group(['prefix' => 'item'], function () use ($router) {
            $router->get("/list", 'ItemsController@all');
            $router->post("/create", 'ItemsController@create');
            $router->group([
                'prefix' => '{itemId}',
                'middleware'=>[
                    'item.validator'
                ]
            ], function () use ($router) {
                //basic entity interactions
                $router->get("/", 'ItemsController@infos');
                $router->delete("/", 'ItemsController@delete');
                $router->put("/", 'ItemsController@edit');
                //Specific interactions
                $router->put("/incoming", 'ItemsController@incoming');
                $router->put("/outgoing", 'ItemsController@outgoing');
            });
        });



    });
});
