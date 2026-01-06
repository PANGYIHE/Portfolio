<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Supplier Dashboard') }}
        </h2>
    </x-slot>
        
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container p-4">
                    <div class="card">
                        <div class="card-header">Edit Product</div> 
                        @if (Session::has('fail'))
                            <span class="alert alert-danger p-2">{{Session::get('fail')}}</span>
                        @endif
                        <div class="card-body">
                            <form action="{{ route('EditProduct') }}" method="post">
                                @csrf
                                <input type="hidden" name="product_id" value="{{$product->id}}">
                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Name</label>
                                    <input type="text" name="name" value="{{$product->name}}" class="form-control" id="formGroupExampleInput" placeholder="Enter Product Name">
                                    @error('name')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Brand</label>
                                    <input type="text" name="brand" value="{{$product->brand}}" class="form-control" id="formGroupExampleInput" placeholder="Enter Product Brand">
                                    @error('brand')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput2" class="form-label">Category</label>
                                    <input type="text" name="category" value="{{$product->category}}" class="form-control" id="formGroupExampleInput2" placeholder="Select Category">
                                    @error('category')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput2" class="form-label">Quantity</label>
                                    <input type="text" name="quantity" value="{{$product->quantity}}" class="form-control" id="formGroupExampleInput2" placeholder="Enter Quantity">
                                    @error('quantity')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput2" class="form-label">Price</label>
                                    <input type="text" name="price" value="{{$product->price}}" class="form-control" id="formGroupExampleInput2" placeholder="Enter Price">
                                    @error('price')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput2" class="form-label">Status</label>
                                    <select name="status" class="form-select" id="formGroupExampleInput2">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="Available" {{ $product->status == 'Available' ? 'selected' : '' }}>Available</option>
                                        <option value="Unavailable" {{ $product->status == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                        <option value="Restocking" {{ $product->status == 'Restocking' ? 'selected' : '' }}>Restocking</option>
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
</x-app-layout>
