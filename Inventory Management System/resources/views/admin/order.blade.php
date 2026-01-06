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
                            Order List
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
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total Price</th>
                                    <th>Supplier</th>
                                    <th>Phone Number</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Last Update Time</th>
                                    <th colspan="2">Action</th>
                                </thead>
                                <tbody>
                                    @if ($all_orders && count($all_orders) > 0)
                                        @foreach ($all_orders as $item)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$item->product->name}}</td>                                                
                                                <td>{{$item->quantity}}</td>
                                                <td>{{$item->price}}</td>
                                                <td>{{$item->totalprice}}</td>
                                                <td>{{$item->user->name}}</td>
                                                <td>{{$item->user->phoneno}}</td>
                                                <td>{{$item->user->address}}</td>
                                                <td>{{$item->status}}</td>
                                                <td>{{$item->updated_at}}</td>
                                                @if ($item->status === 'Ordering' || $item->status === 'Contacting')  
                                                    <td>  
                                                        <a href="/admin/order/edit/{{$item->id}}" class="btn btn-primary btn-sm">Edit</a>
                                                    </td>
                                                    <td> 
                                                        <a href="/admin/order/delete/{{$item->id}}" class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                @elseif ($item->status === 'Delivered') 
                                                    <td colspan="2"> 
                                                        <a href="/admin/inventory/add/{{$item->id}}" class="btn btn-success btn-sm">Add to Inventory</a>
                                                    </td>                                                    
                                                @else
                                                    <td colspan="2"></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="11">No Order Found!</td>
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
@endsection
