<?php

namespace App\Http\Controllers;
use App\Http\Middleware\CheckAdmin;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use App\Models\User;




class ProductController extends Controller
{


    public function __construct()
    {
        $this->middleware(CheckAdmin::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    }


    public function index()
    {

        return view('backend.product.index');
    }

    public function ajaxIndex(Request $request)
    {
        $column = array("id");

        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length;

        $columnIndex = $request->order[0]['column'];
        $columnName = empty($column[$columnIndex]) ? $column[0] : $column[$columnIndex];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];

        $totalProducts = $totalDbProducts = 0;
        $allData = [];

        if ($searchValue == '') {
            $products = Product::orderBy($columnName, $columnSortOrder)->skip($row)->take($rowperpage)->get();
            $totalProducts_count = Product::count();
            $totalProducts = $totalDbProducts = !empty($totalProducts_count) ? $totalProducts_count : 0;
        } else {
            $products = Product::where('name', 'like', '%' . $searchValue . '%')
                ->orWhere('id', 'like', '%' . $searchValue . '%')
                ->orderBy($columnName, $columnSortOrder)->skip($row)->take($rowperpage)->get();

            $totalProducts_count = Product::where('name', 'like', '%' . $searchValue . '%')
                ->orWhere('name', 'like', '%' . $searchValue . '%')
                ->orWhere('id', 'like', '%' . $searchValue . '%')
                ->count();
            $totalProducts = $totalDbProducts = !empty($totalProducts_count) ? $totalProducts_count : 0;
        }

        foreach ($products as $key => $product) {
            $data = [];

            $data[] = ++$key;
            $data[] = $product->name;
            $data[] = $product->price;
            $data[] = $product->quantity;

            $data[] = '<div class="btn-group d-flex justify-content-around">
        <a title="edit" class="text-info fs-6" href="' . route("products.edit", $product->id) . '"><i class="fas fa-edit"></i></a>
        <a title="delete" class="text-danger fs-6"  href="' . route("products.destroy", $product->id) . '"><i class="fas fa-trash"></i></a>
    </div>';

            $allData[] = $data;
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalProducts,
            "iTotalDisplayRecords" => $totalDbProducts,
            "aaData" => $allData
        );

        echo json_encode($response);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);

        $product = new Product();

        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;

        $product->save();

        SendNewProductEmail::dispatch($product)->onQueue('emails');

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $categories=Category::all();
        return view('backend.product.edit',['product'=>$product,'categories'=>$categories]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);

        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('products.index')
                ->with('error', 'Product not found.');
        }

        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;

        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('products.index')
                ->with('error', 'Product not found.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }



    public function import()
    {

        return view('backend.csv.import');
    }



    public function importProducts(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Read and process the file
            $data = array_map('str_getcsv', file($file->getRealPath()));

            foreach ($data as $row) {
                Product::create([
                    'name' => $row[0],
                    'price' => $row[1],
                    'quantity' => $row[2],
                ]);
            }

            return redirect()->back()->with('success', 'CSV file imported successfully.');
        }
    }




    public function exportProducts()
    {
        $products = Product::all();

        $csvFileName = 'products.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, ['Name', 'Price', 'Quantity']);

            foreach ($products as $product) {
                fputcsv($file, [$product->name, $product->price, $product->quantity]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
