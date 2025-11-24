<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(){
        return CustomerResource::collection(Customer::all());

    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|min:10|max:15',
            'address' => 'nullable|min:5|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json( $validator->errors(), 422);
        }

        $customer = Customer::create(
            $request->all()
        );
        return new CustomerResource($customer);
    }

    public function show(Customer $customer){
        return new CustomerResource($customer);
    }

    public function update(Request $request, Customer $customer){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:customers,email,'.$customer->id_customer.',id_customer',
            'phone' => 'nullable|min:10|max:15',
            'address' => 'nullable|min:5|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $customer->update($request->all());
        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer){
        $customer->delete();
        return response()->json(null, 204);
    }
}
