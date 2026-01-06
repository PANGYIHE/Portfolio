<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function loadAllInventories(){
        $all_inventories = Inventory::where('adminid', Auth::id())->get();
        $all_inventories = Inventory::orderByRaw("
            CASE 
                WHEN status = 'Need Restock' THEN 1 
                WHEN status = 'Available' THEN 2
                WHEN status = 'Unavailable' THEN 3 
                ELSE 4
            END
        ")->get();
        return view('admin.dashboard', compact('all_inventories'));
    }

    public function loadAddInventoryForm(){
        $order = null; 

        return view('admin.add-inventory', compact('order'));
    }

    public function loadAddInventoryFormFromOrder($id){
        $order = Order::findOrFail($id); 

        return view('admin.add-inventory', compact('order'));
    }

    public function AddInventory(Request $request) {
        // Form validation
        $request->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'category' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|string',
            'status' => 'required|string'
        ]);
    
        try {
            // Check if productid is not null
            if (!is_null($request->orderid)) {
                // Delete the order with matching adminid and productid
                Order::where('id', $request->orderid)
                    ->where('adminid', Auth::id())
                    ->delete();
            }
    
            // Check if the product already exists in the inventory
            $existing_inventory = Inventory::where('productid', $request->productid)
                ->where('adminid', Auth::id())
                ->first();
    
            if ($existing_inventory) {
                // If product already exists, update the quantity and other fields if they have changed
                $existing_inventory->quantity += $request->quantity;
    
                // Update other fields only if they have changed
                if ($existing_inventory->name != $request->name) {
                    $existing_inventory->name = $request->name;
                }
                if ($existing_inventory->brand != $request->brand) {
                    $existing_inventory->brand = $request->brand;
                }
                if ($existing_inventory->category != $request->category) {
                    $existing_inventory->category = $request->category;
                }
                if ($existing_inventory->price != $request->price) {
                    $existing_inventory->price = $request->price;
                }
                if ($existing_inventory->status != $request->status) {
                    $existing_inventory->status = $request->status;
                }
    
                $existing_inventory->save();
            } else {
                // Register new inventory if the product doesn't exist
                $new_inventory = new Inventory;
                $new_inventory->productid = $request->productid;
                $new_inventory->name = $request->name;
                $new_inventory->brand = $request->brand;
                $new_inventory->category = $request->category;
                $new_inventory->quantity = $request->quantity;
                $new_inventory->price = $request->price;
                $new_inventory->status = $request->status;
                $new_inventory->adminid = Auth::id();
                $new_inventory->save();
            }
    
            return redirect('/admin/dashboard')->with('success', 'Inventory Added/Updated Successfully');
        } catch (\Exception $e) {
            return redirect('/admin/inventory/add')->with('fail', $e->getMessage());
        }
    }    

    public function EditInventory(Request $request){
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
            //register inventory
            $update_inventory = Inventory::where('id', $request->inventory_id)->update([
                'name' => $request->name,
                'brand' => $request->brand,
                'category' => $request->category,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'status' => $request->status,
            ]);
            
            return redirect('/admin/dashboard')->with('success', 'Inventory Updated Successfully');
        } catch (\Exception $e) {
            return redirect('/admin/inventory/edit')->with('fail', $e->getMessage());
        }    
    }


    public function loadEditForm($id){
        $inventory = Inventory::find($id);

        return view('admin.edit-inventory', compact('inventory'));
    }
    
    public function deleteInventory($id) {
        try {
            Inventory::where('id',$id)->delete();
            return redirect('/admin/dashboard')->with('success', 'Inventory Deleted Successfully!');
        } catch (\Exception $e) {
            return redirect('/admin/dashboard')->with('fail',$e->getMessage());
        }
    }

    #API---------------------
    public function getUserInventories(Request $request)
    {
        // Get the authenticated user's ID
        $user = $request->user();

        // Fetch inventories for the user
        $inventories = Inventory::where('adminid', $user->id)->get();

        // Return the inventories in a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Inventories fetched successfully',
            'data' => $inventories
        ], 200);
    }

    public function addUserInventories(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string',
            'category' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|string',
            'status' => 'required|string'
        ]);

        // Get the authenticated user
        $user = $request->user();

        // Create the inventory associated with the user
        $inventory = Inventory::create([
            'name' => $validatedData['name'],
            'brand' => $validatedData['brand'],
            'category' => $validatedData['category'],
            'quantity' => $validatedData['quantity'],
            'price' => $validatedData['price'],
            'status' => $validatedData['status'],
            'adminid' => $user->id,
        ]);

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Inventory added successfully',
            'data' => $inventory,
        ], 201);
    }

    public function updateUserInventories(Request $request, $inventoryId)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string',
            'category' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|string',
            'status' => 'required|string'
        ]);
    
        // Get the authenticated user
        $user = $request->user();
    
        // Find the inventory by ID and ensure the user is the owner
        $inventory = Inventory::where('id', $inventoryId)->where('adminid', $user->id)->first();
    
        if (!$inventory) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory not found or unauthorized',
            ], 404);
        }
    
        // Update the inventory with the validated data
        $inventory->update([
            'name' => $validatedData['name'],
            'brand' => $validatedData['brand'],
            'category' => $validatedData['category'],
            'quantity' => $validatedData['quantity'],
            'price' => $validatedData['price'],
            'status' => $validatedData['status'],
        ]);
    
        // Return a success response with the updated inventory
        return response()->json([
            'success' => true,
            'message' => 'Inventory updated successfully',
            'data' => $inventory,
        ], 200);
    }
    
    public function deleteUserInventory(Request $request, $inventoryId)
    {
        // Get the authenticated user
        $user = $request->user();

        // Find the inventory by ID and ensure the user is the owner
        $inventory = Inventory::where('id', $inventoryId)->where('adminid', $user->id)->first();

        if (!$inventory) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory not found or unauthorized',
            ], 404);
        }

        // Delete the inventory
        $inventory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inventory deleted successfully',
        ], 200);
    }
}
