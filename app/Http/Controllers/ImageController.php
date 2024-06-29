<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function index()
    {
        return view('backend.image.index');
    }

    public function getImages()
    {
        $images = Image::all();
        return response()->json($images);
    }


    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|array',
            'file.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $images = [];
        foreach ($request->file('file') as $file) {
            // Generate a unique UUID
            $uuid = Str::uuid();

            // Get original file name and extension

            $extension = $file->getClientOriginalExtension();

            // Generate filename with UUID and original extension
            $filename = $uuid . '.' . $extension;

            // Move file to public/images directory with the generated filename
            $file->move(public_path('images'), $filename);

            // Save image details to database
            $image = new Image();
            $image->image_path = 'images/' . $filename; // Store relative path in database

            $image->save();

            $images[] = $image;
        }



        return response()->json(['success' => 'Images uploaded successfully', 'images' => $images], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $file = $request->file('image');

        // Generate a unique UUID
        $uuid = Str::uuid();

        // Get the file extension
        $extension = $file->getClientOriginalExtension();

        // Generate filename with UUID and original extension
        $filename = $uuid . '.' . $extension;

        // Move the file to the public/images directory with the generated filename
        $file->move(public_path('images'), $filename);

        // Find the image record
        $image = Image::findOrFail($id);

//         Optionally, delete the old image file from storage
         if (file_exists(public_path($image->image_path))) {
             unlink(public_path($image->image_path));
         }

        // Update the image path in the database
        $image->image_path = 'images/' . $filename;
        $image->save();

        return response()->json(['success' => 'Image Updated Successfully.']);
    }

    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        // Delete the image file from storage
        unlink(public_path($image->image_path));
        // Delete the image record from database
        $image->delete();

        return response()->json(['success' => 'Image deleted successfully'], 200);
    }
    public function ajaxIndex(Request $request)
    {
        $column = array("id"); // Adjust columns as needed

        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows per page

        $columnIndex = $request->order[0]['column']; // Column index
        $columnName = empty($column[$columnIndex]) ? $column[0] : $column[$columnIndex]; // Column name
        $columnSortOrder = $request->order[0]['dir']; // asc or desc
        $searchValue = $request->search['value']; // Search value

        $totalRecords = Image::count();
        $totalRecordsWithFilter = $totalRecords;

        // Fetch records
        if ($searchValue != '') {
            $images = Image::where('id', 'like', '%' . $searchValue . '%')
                ->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();
            $totalRecordsWithFilter = Image::where('id', 'like', '%' . $searchValue . '%')
                ->count();
        } else {
            $images = Image::orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();
        }

        $allData = [];

        foreach ($images as $key => $image) {
            $data = [];

            $data[] = ++$key;
            $data[] = '<img src="' . asset($image->image_path) . '" width="100" height="100" style="object-fit: cover;">';
            $data[] = $image->created_at->format('Y-m-d H:i:s');
            $data[] = '<div class="btn-group d-flex justify-content-around">
<a title="edit" class="text-info fs-6 mr-3" href="#" onclick="editImage(' . $image->id . ')"><i class="fas fa-edit"></i></a>
        <a title="delete" class="text-danger fs-6" href="#" onclick="deleteImage(' . $image->id . ')"><i class="fas fa-trash"></i></a>
    </div>';

            $allData[] = $data;
        }


        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData" => $allData
        );

        return response()->json($response);
    }

}
