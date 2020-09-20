@extends('layouts.template')

@section('title','Subscriptions')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('app.subscription')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
              <li class="breadcrumb-item"><a href="{{route('/subscription')}}">{{__('app.subscription')}}</a></li>
              <li class="breadcrumb-item active">{{__('app.checkout')}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          @include('layouts.includes.alerts')
          <div class="row">
          <div class="col-md-7">
          </div>

          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 text-center">
                      <h4 class="mb-0">{{$plan->plan_name}}</h4>
                      <hr/>
                      <div class="text-left">
                        <p class="mb-2 text-justify">{!!($plan->plan_description)!!}</p>
                      </div>
                      <h5 class="mt-2"><span>Period</span> : <span>{{ucfirst($plan->plan_interval)}}</span></h5>
                      <h5 class="mt-2"><span>Interval</span> : <span>{{$plan->plan_intervalCount}}</span></h5>
                      <h2 class="mt-3">@money($plan->plan_amount)</h2>
                  </div>
                </div>
              </div>
                <!-- /.card-body -->
            </div>
          </div>

          <div class="col-md-6">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div  class="col-md-12 mt-4 mb-4 text-center">
                    <h4 class="mb-4">{{__('app.subscription_enter_details')}}</h4>
                    <hr/>
                    <script src="https://js.stripe.com/v3/"></script>
                    <!-- placeholder for Elements -->
                    <div class="col-md-12" id="card-element"></div>
                  </div>
                </div>
              </div>
                <!-- /.card-body -->
              <div class="card-footer">
                <div class="row">
                    <div id="float-none" class="col-md-7 mx-auto">
                        <button id="card-button" class="btn btn-success col-md-12">{{__('app.subscription_make_payment')}} @money($plan->plan_amount)</button>
                    </div>
                </div>
              </div>
                <!-- /.card-footer -->
            </div>
            <script>
              "use strict";

                var stripe = Stripe('{{setting("stripe_key")}}');
                var elements = stripe.elements();
                // Set up Stripe.js and Elements to use in checkout form
                var style = {
                base: {
                  color: "#32325d",
                  }
                };
                var cardElement = elements.create('card', {style: style});
                cardElement.mount('#card-element');

                var cardholderName = "{{auth()->user()->fullname}}";
                var cardButton = document.getElementById('card-button');
                var paymentLoader = document.querySelector(".payment-loader");
                cardButton.addEventListener('click', function(ev) {
                  cardButton.disabled = true
                stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {name: cardholderName}
                }).then(function(result) {
                if (result.error) {
                  let failed_alert = document.getElementById('failed-alert')
                  swal({
                    icon:'error',
                    title:"Error",
                    text:result.error.message,
                    button: "Retry"
                  }).then(()=>{
                    cardButton.disabled = false
                  })
                } else {
                    cardButton.disabled = true
                    fetch('/subscription/stripe/subscribe', {
                      method: 'POST',
                      headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{csrf_token()}}',
                       },
                      body: JSON.stringify({
                        payment_method_id: result.paymentMethod.id,
                        plan_id: '{{$plan["plan_id"]}}',
                        trial_period:'{{$plan["trial_period_days"]}}',
                      })

                    }).then((res) =>{
                      return res.json()
                    }).then((data) =>{
                      if(data){
                        if(data =='successful')
                        {
                          swal({
                            icon:'success',
                            title: 'Payment Completed',
                            button: 'Complete'
                          })
                          .then((value) => {
                            window.location ="{{url('/subscription')}}"
                          });
                        }
                      }
                    }).catch(function(e){
                          swal({
                            icon:'error',
                            title:"Error",
                            text:'An error occured try again!',
                            button: "Retry"
                          }).then(()=>{
                            cardButton.disabled = false
                          })
                      });
                }
                });
                });
            </script>
            <!-- /.card -->
          </div>
        </div>
        </div>
      </div>
    </div>
  </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
