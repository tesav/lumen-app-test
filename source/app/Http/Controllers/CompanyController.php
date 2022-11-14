<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getList(Request $request)
    {
        $user = Auth::user();
        $companies = $user->companies()->paginate(10);

        return CompanyResource::collection($companies);
    }

    public function save(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'title' => 'required|unique:companies',
            'phone' => 'required',
            'description' => 'required',
        ]);

        $company = new Company;
        $company->fill($request->all());
        $company->user()->associate($user);
        $company->save();

        return response(new CompanyResource($company));
    }

}
