<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function loadAllOrders(){
        $all_orders = Order::where('adminid', Auth::id())
            ->orderByRaw("
                CASE 
                    WHEN status = 'Ordering' THEN 1 
                    WHEN status = 'Contacting' THEN 2
                    WHEN status = 'Delivered' THEN 3 
                    ELSE 4
                END
            ")
            ->get();
    
        return view('admin.order', compact('all_orders'));
    }
    
    public function loadAllOrdersForSupplier(){
        $all_orders = Order::where('userid', Auth::id())
            ->orderByRaw("
                CASE 
                    WHEN status = 'Ordering' THEN 1 
                    WHEN status = 'Contacting' THEN 2
                    WHEN status = 'Delivering' THEN 3 
                    ELSE 4
                END
            ")
            ->get();
    
        return view('order', compact('all_orders'));
    }
    
    public function loadAddOrderForm($id){
        $product = Product::findOrFail($id); 

        return view('admin.add-order', compact('product'));
    }

    public function AddOrder(Request $request)
    {
        // Form validation
        $request->validate([
            'productid' => 'required|string',
            'productname' => 'required|string',
            'userid' => 'required|string',
            'username' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|string',
            'totalprice' => 'required|string',
            'status' => 'required|string'
        ]);

        try {
            // Find the product
            $product = Product::findOrFail($request->productid);

            // Check if sufficient quantity is available
            if ($product->quantity >= $request->quantity) {
                // Deduct the product quantity
                $product->quantity -= $request->quantity;
                $product->save();

                // Register the order
                $new_order = new Order;
                $new_order->productid = $request->productid;
                $new_order->userid = $request->userid;
                $new_order->quantity = $request->quantity;
                $new_order->price = $request->price;
                $new_order->totalprice = $request->totalprice;
                $new_order->status = $request->status;

                // Add the logged-in user's ID
                $new_order->adminid = Auth::id();
                $new_order->save();

                return redirect('/admin/order')->with('success', 'Order Added Successfully');
            } else {
                // Not enough quantity available
                return redirect('/admin/order')->with('fail', 'Insufficient product quantity to fulfill this order.');
            }
        } catch (\Exception $e) {
            return redirect('/admin/product/add')->with('fail', $e->getMessage());
        }
    }

    public function EditOrder(Request $request) {
        // Form validation
        $request->validate([
            'order_id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|string',
            'totalprice' => 'required|string',
            'status' => 'required|string',
        ]);
    
        try {
            // Fetch the current order details
            $order = Order::find($request->order_id);
            if (!$order) {
                return redirect('/admin/order')->with('fail', 'Order not found.');
            }
    
            // Retrieve the associated product
            $product = Product::find($order->productid);
            if (!$product) {
                return redirect('/admin/order')->with('fail', 'Associated product not found.');
            }
    
            // Adjust product quantity
            // Add back the old order quantity to the product's stock
            $product->quantity += $order->quantity;
    
            // Deduct the new quantity from the product's stock
            $product->quantity -= $request->quantity;
    
            // Save the updated product quantity
            $product->save();
    
            // Update the order details
            $update_order = Order::where('id', $request->order_id)->update([
                'quantity' => $request->quantity,
                'price' => $request->price,
                'totalprice' => $request->totalprice,
                'status' => $request->status,
            ]);
    
            return redirect('/admin/order')->with('success', 'Order Updated Successfully');
        } catch (\Exception $e) {
            return redirect('/admin/order/edit')->with('fail', $e->getMessage());
        }
    }
    

    public function EditOrderForSupplier(Request $request){
        //form validation
        $request->validate([
            'quantity' => 'required|integer',
            'price' => 'required|string',
            'totalprice' => 'required|string',
            'status' => 'required|string'
        ]);
        try {
            //register order
            $update_order = Order::where('id', $request->order_id)->update([
                'quantity' => $request->quantity,
                'price' => $request->price,
                'totalprice' => $request->totalprice,
                'status' => $request->status,
            ]);
            
            return redirect('/order')->with('success', 'Order Updated Successfully');
        } catch (\Exception $e) {
            return redirect('/order/edit')->with('fail', $e->getMessage());
        }    
    }

    public function loadEditForm($id){
        $order = Order::find($id);

        return view('admin.edit-order', compact('order'));
    }
    
    public function loadEditFormForSupplier($id){
        $order = Order::find($id);

        return view('edit-order', compact('order'));
    }

    public function deleteOrder($id)
    {
        try {
            // Fetch the order details
            $order = Order::findOrFail($id);

            // Fetch the associated product
            $product = Product::findOrFail($order->productid);

            // Add back the quantity from the deleted order
            $product->quantity += $order->quantity;
            $product->save();

            // Delete the order
            $order->delete();

            return redirect('/admin/order')->with('success', 'Order Deleted Successfully!');
        } catch (\Exception $e) {
            return redirect('/admin/order')->with('fail', $e->getMessage());
        }
    }

}
