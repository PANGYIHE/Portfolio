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
                            Order Check List
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
                                    <th>Customer</th>
                                    <th>Phone Number</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Last Update Time</th>
                                    <th>Action</th>
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
                                                <td>{{$item->admin->name}}</td>
                                                <td>{{$item->admin->phoneno}}</td>
                                                <td>{{$item->admin->address}}</td>
                                                <td>{{$item->status}}</td>
                                                <td>{{$item->updated_at}}</td>
                                                <td>
                                                    @if ($item->status != 'Delivered')       
                                                        <a href="/order/edit/{{$item->id}}" class="btn btn-primary btn-sm">Update</a>
                                                    @endif         
                                                </td>                                   
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
</x-app-layout>
