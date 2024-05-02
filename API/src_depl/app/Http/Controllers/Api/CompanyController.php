<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        return Company::all();
    }

    public function store(Request $request)
    {
        $newCompany = new Company();
        foreach ($request->request as $columnName => $columnValue) {
            $newCompany->$columnName = $columnValue;
        }
        $newCompany->save();
        return $newCompany;
    }

    public function show(Company $company ) {
        return $company;
    }

    public function update(Request $request, Company $company)
    {
        if ( isset($request->id) ) {
            return response()->json(["error" => "cannot change id"], 404);
        }

        foreach ($request->request as $attributeName => $attributeValue) {
            $company->$attributeName = $attributeValue;
        }
        $company->save();
        return $company;
    }

    public function destroy(Company $company) {
        $company->delete();
        return $company;
    }
}
