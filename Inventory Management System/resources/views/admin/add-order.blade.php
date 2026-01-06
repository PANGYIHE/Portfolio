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
                        <div class="card-header">Add New Order</div> 
                        @if (Session::has('fail'))
                            <span class="alert alert-danger p-2">{{Session::get('fail')}}</span>
                        @endif
                        <div class="card-body">
                            <form action="{{ route('admin.AddOrder') }}" method="post">
                                @csrf
                                <input type="hidden" name="productid" value="{{ $product->id }}" id="formGroupExampleInput">
                                <input type="hidden" name="userid" value="{{ $product->user->id }}" id="formGroupExampleInput">
                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Supplier Name</label>
                                    <input type="text" name="username" value="{{ $product->user->name }}" class="form-control" id="formGroupExampleInput" placeholder="Enter Supplier Name" readonly>
                                    @error('username')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Product Name</label>
                                    <input type="text" name="productname" value="{{ $product->name }}" class="form-control" id="formGroupExampleInput" placeholder="Enter Product Name" readonly>
                                    @error('productname')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="quantityInput" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" id="quantityInput" placeholder="Enter Quantity < {{ $product->quantity }}">
                                    @error('quantity')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="priceInput" class="form-label">Price</label>
                                    <input type="text" name="price" value="{{ $product->price }}" class="form-control" id="priceInput" placeholder="Enter Price" readonly>
                                    @error('price')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="totalPriceInput" class="form-label">Total Price</label>
                                    <input type="text" name="totalprice" class="form-control" id="totalPriceInput" placeholder="Enter Total Price" readonly>
                                    @error('totalprice')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput2" class="form-label">Status</label>
                                    <select type="text" name="status" class="form-select" id="formGroupExampleInput2">
                                        <div class="col-md-4">
                                        <option selected disabled>Select Status</option>
                                        <option value="Ordering">Ordering</option>
                                        <option value="Contacting">Contacting</option>
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
