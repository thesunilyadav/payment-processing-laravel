@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> Make a Payment</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('pay') }}" method="post" id="paymentForm">
                        @csrf

                        <div class="row">
                            <div class="col-auto">
                                <label> Money to pay</label>
                                <input type="number" name="value" value="{{ old('value') }}" min="1" step="0.01" class="form-control">
                            </div>
                            <div class="col-auto">
                                <label> Currency </label>
                               <select class="form-select" name="currency" id="">
                                   <option selected value=""> Select Currency </option>
                                   @foreach ($currencies as $currency)
                                        <option value="{{$currency->iso}}"> {{$currency->iso}} ({{$currency->name}}) </option>
                                   @endforeach
                               </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label> Select method to pay </label>
                                <div class="form-group" id="toggler">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        @foreach ($platforms as $platform)
                                            <label 
                                                class="btn btn-outline-secondary rounded m-2 p-1" 
                                                data-target="#{{$platform->name}}Collapse"
                                                data-toggle="collapse"
                                            >
                                                <input type="radio" name="payment_platform" value="{{$platform->id}}" id="">
                                                <img src="{{asset($platform->image)}}" class="img-thumbnail" alt="">
                                            </label>
                                        @endforeach
                                    </div>
                                    @foreach ($platforms as $platform)
                                        <div 
                                            id="{{$platform->name}}Collapse"
                                            class="collpase"
                                            data-parent="#toggler"
                                        >
                                            @includeIf('components.' . strtolower($platform->name) . '-collapse')
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center">
                                <button class="btn btn-primary mt-3" id="payBtn" type="submit"> Pay </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
