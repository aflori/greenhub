<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adress;

class AdressController extends Controller
{
    public function index()
    {
        return Adress::all();
    }

    public function store(Request $request)
    {
        $newAdress = new Adress();
        foreach ($request->request as $columnName => $columnValue) {
            $newAdress->$columnName = $columnValue;
        }
        $newAdress->save();
        return $newAdress;
    }

    public function show(Adress $adress ) {
        return $adress;
    }

    public function update(Request $request, Adress $adress)
    {
        if ( isset($request->id) ) {
            return response()->json(["error" => "cannot change id"], 404);
        }

        foreach ($request->request as $attributeName => $attributeValue) {
            $adress->$attributeName = $attributeValue;
        }
        $adress->save();
        return $adress;
    }

    public function destroy(Adress $adress) {
        $adress->delete();
        return $adress;
    }
}
