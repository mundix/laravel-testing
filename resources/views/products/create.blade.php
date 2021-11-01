@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Products</div>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{session('status')}}
                        </div>
                    @endif
                    @if(auth()->user()->is_admin)
                            <a href="{{route('products.create')}}" class="btn btn-primary mb-3">Add new product</a>
                    @endif
                        <form action="{{route('products.store')}}" method="post"  >
                            @csrf
                            <div class="form-group">
                                <label for="product_name">Name</label>
                                <input type="text" class="form-control" id="product_name" aria-describedby="nameHelp" name="name">
                            </div>
                            <div class="form-group">
                                <label for="product_price">Price</label>
                                <input type="text" class="form-control" id="product_price" aria-describedby="priceHelp" name="price">
                            </div>

                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                </div>

            </div>
        </div>
    </div>
@endsection
