<?php

namespace App\Http\Controllers;
use App\Models\Division; // ✅ benar
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions'
        ]);
        
        $division = Division::create(['name' => $request->name]);
        
        return response()->json([
            'success' => true,
            'divisi' => $division
        ]);
    }

    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,'.$division->id
        ]);
        
        $oldName = $division->name;
        $division->update(['name' => $request->name]);
        
        return response()->json([
            'success' => true,
            'divisi' => $division,
            'oldName' => $oldName
        ]);
    }

    public function destroy(Division $division)
    {
        $divisionName = $division->name;
        $division->delete();
        
        return response()->json([
            'success' => true,
            'divisiName' => $divisionName
        ]);
    }
}