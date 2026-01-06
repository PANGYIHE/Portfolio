<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductController extends Controller
{
    //CRUD logic
    public function loadAllProducts(){
        $all_products = Product::orderByRaw("
            CASE 
                WHEN status = 'Available' THEN 1 
                WHEN status = 'Restocking' THEN 2
                WHEN status = 'Unavailable' THEN 3 
                ELSE 4
            END
        ")->get();

        return view('admin.product', compact('all_products'));
    }

    public function loadOwnProducts() {
        $all_products = Product::where('userid', Auth::id())->get();
        return view('product', compact('all_products'));
    }

    public function loadNeededProducts() {
        $all_products = Product::where('userid', Auth::id())->where('quantity', '<', 50)->get();
        return view('dashboard', compact('all_products'));
    }
    
    public function loadAddProductForm(){
        return view('add-product');
    }

    public function AddProduct(Request $request){
        //form validation
        $request->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'category' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|string',
            'status' => 'required|string'
        ]);
        try {
            //register product
            $new_product = new Product;
            $new_product->userid = 1;
            $new_product->name = $request->name;
            $new_product->brand = $request->brand;
            $new_product->category = $request->category;
            $new_product->quantity = $request->quantity;
            $new_product->price = $request->price;
            $new_product->status = $request->status;
            // Add the logged-in user's ID
            $new_product->userid = Auth::id();
            $new_product->save();
            
            return redirect('/product')->with('success', 'Product Added Successfully');
        } catch (\Exception $e) {
            return redirect('/product')->with('fail', $e->getMessage());
        }        
    }

    public function EditProduct(Request $request){
        //form validation
        $request->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'category' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|string',
            'status' => 'required|string'
        ]);
        try {
            //register product
            $update_product = Product::where('id', $request->product_id)->update([
                'name' => $request->name,
                'brand' => $request->brand,
                'category' => $request->category,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'status' => $request->status,
            ]);
            
            return redirect('/product')->with('success', 'Product Updated Successfully');
        } catch (\Exception $e) {
            return redirect('/product')->with('fail', $e->getMessage());
        }    
    }

    public function loadEditForm($id){
        $product = Product::find($id);

        return view('edit-product', compact('product'));
    }

    public function deleteProduct($id) {
        try {
            Product::where('id',$id)->delete();
            return redirect('/product')->with('success', 'Product Deleted Successfully!');
        } catch (\Exception $e) {
            return redirect('/product')->with('fail',$e->getMessage());
        }
    }
}
