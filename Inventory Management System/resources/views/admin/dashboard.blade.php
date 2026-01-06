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
                    <div class="card">
                        <div class="card-header">
                            Inventory List
                            <a href="/admin/inventory/add" class="btn btn-success btn-sm float-end">Add New Inventory</a>
                        </div>
                        @if (Session::has('success'))
                            <span class="alert alert-success p-2">{{Session::get('success')}}</span>
                        @endif
                        @if (Session::has('fail'))
                            <span class="alert alert-danger p-2">{{Session::get('fail')}}</span>
                        @endif
                        <div class="card-body">
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
                                    @if ($all_inventories && count($all_inventories) > 0)
                                        @foreach ($all_inventories as $item)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$item->name}}</td>
                                                <td>{{$item->brand}}</td>
                                                <td>{{$item->category}}</td>
                                                <td>{{$item->quantity}}</td>
                                                <td>{{$item->price}}</td>
                                                <td>{{$item->status}}</td>
                                                <td>{{$item->updated_at}}</td>
                                                <td><a href="/admin/inventory/edit/{{$item->id}}" class="btn btn-primary btn-sm">Edit</a></td>
                                                <td><a href="/admin/inventory/delete/{{$item->id}}" class="btn btn-danger btn-sm">Delete</a></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="10">No Inventory Found!</td>
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

    <script>
        (function () {
            history.pushState(null, null, location.href);
            window.onpopstate = function () {
                history.pushState(null, null, location.href);
            };
        })();
    </script>

@endsection
