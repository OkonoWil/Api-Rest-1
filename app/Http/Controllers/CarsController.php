<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Cars::all();
        if (count($cars) <= 0)
            return response(["message" => "No content"], 204);
        return response($cars, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $carData = $request->validate([
                'model' => ['required', 'string'],
                'price' => ['required', 'numeric'],
                'description' => ['required'],
                'user_id' => ['required'],
                'image' => ['required', 'max:4096', "mimes:png,jpg,jpeg"],
            ]);
        } catch (ValidationException $validationError) {
            return response($validationError->errors(), 422);
        }
        $filename = 'cars' . time() . 'user' . $carData['user_id'] . '.' . $carData["image"]->extension();
        $path = $request->image->storeAs(
            'images/cars',
            $filename,
            'public'
        );
        $carData['image'] = $path;
        $car = Cars::create($carData);
        return response($car, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function show(Cars $cars)
    {
        return response($cars, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cars $cars)
    {
        try {
            $carData = $request->validate([
                'model' => ['required', 'string'],
                'price' => ['required', 'numeric'],
                'description' => ['required'],
                'user_id' => ['required', ''],
            ]);
        } catch (ValidationException $validationError) {
            return response($validationError->errors(), 422);
        }
        if ($carData['user_id'] != $cars->user_id) return response(['message' => 'Action interdit'], 403);
        $cars->model = $carData['model'];
        $cars->price = $carData['price'];
        $cars->description = $carData['description'];
        $cars->save();
        return response($cars, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cars $cars)
    {
        $cars->destroy($cars->id);
        return response('', 200);
    }
}
