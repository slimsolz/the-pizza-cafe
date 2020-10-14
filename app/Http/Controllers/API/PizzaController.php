<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PizzaRequest;
use App\Models\Photo;
use App\Models\Pizza;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JD\Cloudder\Facades\Cloudder;

class PizzaController extends Controller
{
    public function getMenu()
    {
        $pizza_list = Pizza::with('photo')->get();
        if (sizeof($pizza_list) === 0) {
            return response()->json([
                'success' => true,
            ], Response::HTTP_NO_CONTENT);
        }

        return $pizza_list;
    }

    public function getPizza($id)
    {
        $pizza = Pizza::with('photo')->where('id', $id)->first();
        if (!$pizza) {
            return response()->json([
                'success' => false,
                'message' => 'Pizza not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return $pizza;
    }

    public function addPizza(PizzaRequest $pizzaRequest)
    {
        $pizza = new Pizza();
        $pizza->name = $pizzaRequest->name;
        $pizza->description = $pizzaRequest->description;
        $pizza->size = $pizzaRequest->size;
        $pizza->price = $pizzaRequest->price;

        $image_file = $pizzaRequest->file('image');
        $image_name = $image_file->getRealPath();
        $image_original_name = $image_file->getClientOriginalName();
        $cloudinary_details = $this->saveToCloudinary($image_name);
        $pizza->save();

        $photo = new Photo();
        $photo->name = $image_original_name;
        $photo->image_url = $cloudinary_details[0];
        $photo->public_id = $cloudinary_details[1];
        $photo->pizza_id = $pizza->id;
        $photo->save();

        return response()->json([
            'message' => 'pizza successfully added',
            'data' => [
                'id' => $pizza->id,
                'name' => $pizza->name,
                'size' => $pizza->size,
                'price' => $pizza->price,
                'description' => $pizza->description,
                'images_url' => $photo->image_url
            ]
        ], Response::HTTP_CREATED);
    }

    public function deletePizza($id)
    {
        $pizza = Pizza::with('photo')->where('id', $id)->first();

        if (!$pizza) {
            return response()->json([
                'success' => false,
                'message' => 'Pizza not found',
            ], Response::HTTP_NOT_FOUND);
        }
        $this->deleteCloudinaryImages($pizza->photo->public_id);
        $pizza->delete();
        return response()->json([
            'success' => true,
            'message' => 'deleted successfully'
        ], Response::HTTP_OK);
    }

    private function saveToCloudinary($name)
    {
        Cloudder::upload($name, Cloudder::getPublicId(), ["folder" => "Pizza-app"]);
        list($width, $height) = getimagesize($name);
        $image_url = Cloudder::show(Cloudder::getPublicId(), ['width' => $width, 'height' => $height]);
        $image_public_id = Cloudder::getPublicId();

        return [$image_url, $image_public_id];
    }

    private function deleteCloudinaryImages($image_id)
    {
        if ($image_id) {
            Cloudder::destroy($image_id);
        }
    }
}
