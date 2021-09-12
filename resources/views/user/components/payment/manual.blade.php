@extends('user.layouts')

@section('css')
    <style>
        .tab {
            display: none;
        }

        .hide {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="page-content container">
        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h1 class="panel-title cyan-600">
                    <i class="icon wb-payment"></i>{{sysConfig('website_name').' '.trans('common.payment.manual')}}
                </h1>
            </div>
            <div class="panel-body border-primary">
                <div class="steps row w-p100">
                    <div class="step col-lg-4 current">
                        <span class="step-number">1</span>
                        <div class="step-desc">
                            <span class="step-title">须知</span>
                            <p>如何正确使用本支付</p>
                        </div>
                    </div>
                    <div class="step col-lg-4">
                        <span class="step-number">2</span>
                        <div class="step-desc">
                            <span class="step-title">支付</span>
                            <p>获取支付二维码，进行支付</p>
                        </div>
                    </div>
                    <div class="step col-lg-4">
                        <span class="step-number">3</span>
                        <div class="step-desc">
                            <span class="step-title">等待</span>
                            <p>等待支付被确认</p>
                        </div>
                    </div>
                </div>

                <div id="payment-group" class="w-p100 text-center mb-20">
                    <div class="w-md-p50 w-p100 mx-auto btn-group">
                        @if(sysConfig('wechat_qrcode'))
                            <button id="btn-wechat" class="btn btn-lg btn-block" onclick="show(0)">{{trans('common.payment.wechat')}}</button>
                        @endif
                        @if(sysConfig('alipay_qrcode'))
                            <button id="btn-alipay" class="btn mt-0 btn-lg btn-block" onclick="show(1)">{{trans('common.payment.alipay')}}</button>
                        @endif
                    </div>
                </div>
                <div class="tab">
                    <div class="wechat hide">
                        <div class="mx-auto text-center">
                            <h4>备注账号</h4>
                            <img class="w-lg-350 w-md-p50 w-p100 mb-10" src="{{asset('assets/images/help/manual_wechat1.png')}}" alt=""/>
                            <h4>填入登录使用的账号</h4>
                            <img class="w-lg-350 w-md-p50 w-p100 mb-10" src="{{asset('assets/images/help/manual_wechat2.png')}}" alt=""/>
                        </div>
                    </div>
                    <div class="alipay hide">
                        <div class="mx-auto text-center">
                            <h5>备注1账号</h5>
                            <img class="w-lg-350 w-md-p50 w-p100 mb-10" src="{{asset('assets/images/help/manual_wechat1.png')}}" alt=""/>
                            <h5>填入登录使用的账号</h5>
                            <img class="w-lg-350 w-md-p50 w-p100 mb-10" src="{{asset('assets/images/help/manual_wechat2.png')}}" alt=""/>
                        </div>
                    </div>
                </div>

                <div class="tab">
                    <div class="wechat hide">
                        <div class="mx-auto text-center">
                            <div class="alert alert-info">
                                {!! trans('user.payment.qrcode_tips', ['software' => trans('common.payment.wechat')]) !!}
                            </div>
                            <img class="w-lg-350 w-md-p50 w-p100 mb-10" src="{{asset(sysConfig('wechat_qrcode'))}}" alt=""/>
                        </div>
                    </div>
                    <div class="alipay hide">
                        <div class="mx-auto text-center">
                            <div class="alert alert-info">
                                {!! trans('user.payment.qrcode_tips', ['software' => trans('common.payment.alipay')]) !!}
                            </div>
                            <p>{{trans('common.payment.alipay')}}</p>
                            <img class="w-lg-350 w-md-p50 w-p100 mb-10" src="{{asset(sysConfig('alipay_qrcode'))}}" alt=""/>
                        </div>
                    </div>
                    <div class="alert alert-danger text-center">
                        {!! trans('user.payment.mobile_tips') !!}
                    </div>
                </div>

                <div class="tab">
                    <div class="alert alert-danger text-center">
                        支付时，请充值正确金额（多不退，少要补）
                    </div>
                    <div class="mx-auto w-md-p50">
                        <ul class="list-group list-group-dividered">
                            <li class="list-group-item">{{trans('user.shop.service').'：'.$name}}</li>
                            <li class="list-group-item">{{trans('user.shop.price').'：¥'.$payment->amount}}</li>
                            @if($days !== 0)
                                <li class="list-group-item">{{trans('common.available_date').'：'.$days.trans_choice('validation.attributes.day', 1)}}</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="clearfix">
                    <button type="button" class="btn btn-lg btn-default float-left" id="prevBtn" onclick="nextPrev(-1)">上一步</button>
                    <button type="button" class="btn btn-lg btn-default float-right" id="nextBtn" onclick="nextPrev(1)">下一步</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        let currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab
        show(0);

        function showTab(n) {
            // This function will display the specified tab of the form ...
            const x = document.getElementsByClassName('tab');
            x[n].style.display = 'block';
            // ... and fix the Previous/Next buttons:
            if (n === 0) {
                document.getElementById('prevBtn').style.display = 'none';
            } else {
                document.getElementById('prevBtn').style.display = 'inline';
            }

            if (n === x.length - 1) {
                document.getElementById('payment-group').style.display = 'none';
                document.getElementById('nextBtn').classList.remove('btn-default');
                document.getElementById('nextBtn').classList.add('btn-primary');
                document.getElementById('nextBtn').innerHTML = '{{trans('user.status.completed')}}';
            } else {
                document.getElementById('payment-group').style.display = 'inline-flex';
                document.getElementById('nextBtn').innerHTML = '下一步';
                document.getElementById('nextBtn').style.display = 'inline';
            }

            fixStepIndicator(n);
        }

        function nextPrev(n) {
            // This function will figure out which tab to display
            const x = document.getElementsByClassName('tab');
            // Hide the current tab:
            x[currentTab].style.display = 'none';
            // Increase or decrease the current tab by 1:
            currentTab += n;

            // if you have reached the end of the form... :
            if (currentTab >= x.length) {
                //...the form gets submitted:
                $.post('{{route('manual.inform', ['payment' => $payment->trade_no])}}', {_token: '{{csrf_token()}}'}, function(ret) {
                    if (ret.status === 'success') {
                        swal.fire({title: '已受理', text: ret.message, icon: 'success'}).then(() => window.location.href = '{{route('invoice')}}');
                    } else {
                        swal.fire({title: ret.message, icon: 'error'}).then(() => window.location.reload());
                    }
                });
                return false;
            } else {
                showTab(currentTab);
            }

        }

        function fixStepIndicator(n) {
            // This function removes the "current" class of all steps...
            let i, x = document.getElementsByClassName('step');
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(' current', ' ');
            }
            //... and adds the "active" class to the current step:
            x[n].className += ' current';
        }

        function show(check) {
            const $wechat = document.getElementsByClassName('wechat');
            const $btn_wechat = document.getElementById('btn-wechat');
            const $alipay = document.getElementsByClassName('alipay');
            const $btn_alipay = document.getElementById('btn-alipay');
            if (check) {
                for (let i = 0; i < $wechat.length; i++) {
                    $wechat[i].style.display = 'none';
                    $alipay[i].style.display = 'inline';
                }
                $btn_wechat.classList.remove('btn-success');
                $btn_alipay.classList.add('btn-primary');
            } else {
                for (let i = 0; i < $wechat.length; i++) {
                    $wechat[i].style.display = 'inline';
                    $alipay[i].style.display = 'none';
                }
                $btn_wechat.classList.add('btn-success');
                $btn_alipay.classList.remove('btn-primary');
            }
        }
    </script>
@endsection
