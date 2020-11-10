<?php

namespace App\Http\Controllers;

use App\Models\Authors;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthorsController extends Controller
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

    public function index()
    {
        return Authors::all();
    }

    public function authorsId($id)
    {
        $authorsId = Authors::find($id);

        if ($authorsId) {
            return response()->json([
                'message' => 'tampil author sesuai id',
                'data' => $authorsId
            ], 200);
        } else {
            return response()->json([
                'message' => 'Authors not found',
            ], 404);
        }
    }

    public function insertAuthors(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'biography' => 'required'
        ]);

        $authors = Authors::create(
            $request->only(['name', 'gender', 'biography'])
        );

        return response()->json([
            'created' => true,
            'data' => $authors
        ], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $authors = Authors::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Authors not found'
            ], 404);
        }

        $authors->fill(
            $request->only(['name', 'gender', 'biography'])
        );
        $authors->save();

        return response()->json([
            'updated' => true,
            'data' => $authors
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $authors = Authors::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Authors not found'
                ]
            ], 404);
        }

        $authors->delete();

        return response()->json([
            'deleted' => true
        ], 200);
    }
}
