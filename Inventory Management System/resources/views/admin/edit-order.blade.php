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
                        <div class="card-header">Edit Order</div> 
                        @if (Session::has('fail'))
                            <span class="alert alert-danger p-2">{{Session::get('fail')}}</span>
                        @endif
                        <div class="card-body">
                            <form action="{{ route('admin.EditOrder') }}" method="post">
                                @csrf
                                <input type="hidden" name="order_id" value="{{$order->id}}">
                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Supplier Name</label>
                                    <input type="text" name="username" value="{{ $order->user->name }}" class="form-control" id="formGroupExampleInput" placeholder="Enter Supplier Name" readonly>
                                    @error('username')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Product Name</label>
                                    <input type="text" name="productname" value="{{ $order->product->name }}" class="form-control" id="formGroupExampleInput" placeholder="Enter Product Name" readonly>
                                    @error('productname')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput2" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" id="quantityInput" value="{{$order->quantity}}" class="form-control" id="formGroupExampleInput2" placeholder="Enter Quantity < {{ $order->product->quantity }}" readonly>
                                    @error('quantity')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput2" class="form-label">Price</label>
                                    <input type="text" name="price" id="priceInput" value="{{$order->price}}" class="form-control" id="formGroupExampleInput2" placeholder="Enter Price" readonly>
                                    @error('price')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput2" class="form-label">Total Price</label>
                                    <input type="text" name="totalprice" id="totalPriceInput" value="{{$order->totalprice}}" class="form-control" id="formGroupExampleInput2" placeholder="Enter Total Price" readonly>
                                    @error('totalprice')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput2" class="form-label">Status</label>
                                    <select name="status" class="form-select" id="formGroupExampleInput2">
                                        <option disabled {{ $order->status == '' ? 'selected' : '' }}>Select Status</option>
                                        <option value="Ordering" {{ $order->status == 'Ordering' ? 'selected' : '' }}>Ordering</option>
                                        <option value="Contacting" {{ $order->status == 'Contacting' ? 'selected' : '' }}>Contacting</option>
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
