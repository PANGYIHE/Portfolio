<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Supplier Dashboard') }}
        </h2>
    </x-slot>
        
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="card">
                        <div class="card-header">
                            Product List
                            <a href="/product/add" class="btn btn-success btn-sm float-end">Add New Product</a>
                        </div>
                        @if (Session::has('success'))
                            <span class="alert alert-success p-2">{{Session::get('success')}}</span>
                        @endif
                        @if (Session::has('fail'))
                            <span class="alert alert-danger p-2">{{Session::get('fail')}}</span>
                        @endif
                        <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered">
                                <thead>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Last Update Time</th>
                                    <th colspan="2">Action</th>
                                </thead>
                                <tbody>
                                    @if ($all_products && count($all_products) > 0)
                                        @foreach ($all_products as $item)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$item->name}}</td>
                                                <td>{{$item->brand}}</td>
                                                <td>{{$item->category}}</td>
                                                <td>{{$item->quantity}}</td>
                                                <td>{{$item->price}}</td>
                                                <td>{{$item->status}}</td>
                                                <td>{{$item->updated_at}}</td>
                                                <td><a href="/product/edit/{{$item->id}}" class="btn btn-primary btn-sm">Edit</a></td>
                                                <td><a href="/product/delete/{{$item->id}}" class="btn btn-danger btn-sm">Delete</a></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="10">No Product Found!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
