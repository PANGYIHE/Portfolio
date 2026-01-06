@extends('admin.layouts.app')

@section('content')
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Retailer Dashboard') }}
            </h2>
        </div>
    </header>
        
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container p-4">
                    <div class="card">
                        <div class="card-header">Add New Inventory</div> 
                        @if (Session::has('fail'))
                            <span class="alert alert-danger p-2">{{Session::get('fail')}}</span>
                        @endif
                        <div class="card-body">
                            <form action="{{ route('admin.AddInventory') }}" method="post">
                                @csrf
                                <input type="hidden" name="orderid" value="{{ $order ? $order->id : '' }}" id="formGroupExampleInput">
                                <input type="hidden" name="productid" value="{{ $order ? $order->product->id : '' }}" id="formGroupExampleInput">

                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Name</label>
                                    <input type="text" name="name" value="{{ $order ? $order->product->name : '' }}" class="form-control" id="formGroupExampleInput" placeholder="Enter Product Name">
                                    @error('name')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Brand</label>
                                    <input type="text" name="brand" value="{{ $order ? $order->product->brand : '' }}" class="form-control" id="formGroupExampleInput" placeholder="Enter Brand">
                                    @error('brand')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="quantityInput" class="form-label">Category</label>
                                    <input type="text" name="category" value="{{ $order ? $order->product->category : '' }}" class="form-control" placeholder="Enter Category">
                                    @error('category')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="priceInput" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" value="{{ $order ? $order->quantity : '' }}" class="form-control" id="priceInput" placeholder="Enter Quantity">
                                    @error('quantity')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="priceInput" class="form-label">Selling Price</label>
                                    <input type="text" name="price" class="form-control" id="priceInput" placeholder="Enter Selling Price (Original Price: {{ $order ? $order->price : '' }})">
                                    @error('price')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="formGroupExampleInput2" class="form-label">Status</label>
                                    <select type="text" name="status" class="form-select" id="formGroupExampleInput2">
                                        <option selected disabled>Select Status</option>
                                        <option value="Available">Available</option>
                                        <option value="Unavailable">Unavailable</option>
                                        <option value="Need Restock">Need Restock</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get input elements
        const quantityInput = document.getElementById('quantityInput');
        const priceInput = document.getElementById('priceInput');
        const totalPriceInput = document.getElementById('totalPriceInput');

        // Add event listeners to calculate total price
        function calculateTotalPrice() {
            // Parse inputs to ensure numeric operations
            const quantity = parseFloat(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;

            // Calculate total price
            const totalPrice = quantity * price;

            // Update total price input field
            totalPriceInput.value = totalPrice.toFixed(2); // Ensure two decimal places
        }

        // Listen for input events on quantity and price fields
        quantityInput.addEventListener('input', calculateTotalPrice);
        priceInput.addEventListener('input', calculateTotalPrice);
    </script>
@endsection
