<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cities = City::query();

        // Apply filters if provided
        if ($request->filled('name')) {
            $cities->where('name', 'like', '%' . $request->input('name') . '%');
        }

        // You can add more filters as needed

        $cities = $cities->paginate(10);

        return view('cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:cities,name',
        ]);

        // Create the new city
        City::create([
            'name' => $request->name,
        ]);

        // Redirect back with a success message
        return redirect()->route('cities.index')->with('success', 'تم إضافة المدينة بنجاح.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        return view('cities.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:cities,name,' . $city->id,
        ]);

        // Update the city
        $city->update([
            'name' => $request->name,
        ]);

        // Redirect back with a success message
        return redirect()->route('cities.index')->with('success', 'تم تحديث بيانات المدينة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $city->delete();

        return redirect()->route('cities.index')->with('success', 'تم حذف المدينة بنجاح.');
    }
}
