@extends('front.template.main')
@section('content')
    <div class="row rowheaderyellow">
        <div class="col-md-12 text-center">
            <h1 class="headerpage">Order geschiedenis</h1>
        </div>
    </div>
    <div class="row spacerbig"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table id="bootstrapDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Order Nr</th>
                        <th>Datum</th>
                        <th>Producten</th>
                        <th>Prijs</th>
                        <th>Details</th>
                    </tr>
                    </thead>
                    <tbody id="bodyItems">
                    @foreach($orders as $order)
                    <tr>
                        <td>{{$order->id}}</td>;
                        <td>{{$order->created_at}}</td>"
                        <td>
                            <?php $teller = 0;
                            foreach($order->cartItems as $cartItem){
                                if($teller <= 2){
                            ?>
                                {{$cartItem->amount}}  x  <b>{{$cartItem->productNr}}</b>  </br>
                            <?php } $teller++;
                                }?>
                        </td>
                        <td>Offerte op aanvraag</td>
                        <td><a href='/detailOrderFromHistory/{{$order->id}}' class='btn btn-default'>Details</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection