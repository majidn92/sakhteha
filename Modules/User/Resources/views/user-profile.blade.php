@extends('common::layouts.master')
@section('modal')
@include('gallery::image-gallery')
@endsection
@section('content')


<section class="pt-4" id="gd-list">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if(session('error'))
                <div id="error_m" class="alert alert-danger">
                    {{session('error')}}
                </div>
                @endif
                @if(session('success'))
                <div id="success_m" class="alert alert-success">
                    {{session('success')}}
                </div>
                @endif
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <section class="card">
                    <div class="card-header">
                        <h3 class="center">{{ __('profile') }}</h3>
                    </div>
                    <div class="card-body">

                        @if(Session::get('message'))
                        <div class="alert alert-success" id="message">
                            <h3 class=" text-center text-success"> {{ Session::get('message') }}</h3>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table" width="100%">
                                    <tr class="">
                                        <th class="w-50" scope="col">{{ __('name') }}</th>
                                        <td class="w-50" data-title="Name">
                                            {{ Sentinel::getUser() ? Sentinel::getUser()->first_name.' '.Sentinel::getUser()->last_name : '' }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <th scope="col">{{ __('email') }}</th>
                                        <td data-title="Email">{{ Sentinel::getUser()->email }}</td>
                                    </tr>
                                    <tr class="">
                                        <th scope="col">{{ __('mobile') }}</th>
                                        <td data-title="Phone">{{ Sentinel::getUser()->phone }}</td>
                                    </tr>
                                    <tr class="">
                                        <th scope="col">{{ __('dob') }}</th>
                                        <td data-title="dob">{{ miladi_to_jalali(Sentinel::getUser()->dob) }}</td>
                                    </tr>
                                    <tr class="">
                                        <th scope="col">{{ __('gender') }}</th>
                                        <td data-title="gender">
                                            @if(Sentinel::getUser()->gender == \App\Enums\GenderType::Male)
                                            {{ __('male') }}
                                            @elseif(Sentinel::getUser()->gender == \App\Enums\GenderType::Female)
                                            {{ __('female') }}
                                            @else
                                            {{ __('other') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <th scope="col">{{ __('last_login') }}</th>
                                        <td data-title="Email">{{ miladi_to_jalali(Sentinel::getUser()->last_login , 1) }}</td>
                                    </tr>
                                    <tr class="">
                                        <th scope="col">{{ __('newsletter') }}</th>
                                        <td data-title="Newsletter">@if (Sentinel::getUser()->newsletter_enable==1){{ __('enable') }} @else {{ __('disable') }} @endif</td>
                                    </tr>

                                    <tr class="">
                                        <th scope="col">
                                            <a class="btn btn-block btn-warning modal-menu" href="javascript:void(0)" data-title="تغییر رمز عبور" data-url="{{route('edit-info',['page_name'=>'change-password'])}}" data-toggle="modal" data-target="#common-modal"><i class="m-l-10 mdi mdi-key-variant"></i>{{ __('change_password') }}
                                            </a>
                                        </th>
                                        <td data-title="">

                                            <a class="btn btn-block btn-primary modal-menu" href="javascript:void(0)" data-title="ویرایش حساب کاربری" data-url="{{route('edit-info',['page_name'=>'edit-my-profle','param1'=>Sentinel::getUser()->id])}}" data-toggle="modal" data-target="#common-modal"><i class="fa fa-edit option-icon"></i>{{ __('edit_profile') }}</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                @if(profile_exist(Sentinel::getUser()->profile_image) && Sentinel::getUser()->profile_image!=null)
                                <img src=" {{static_asset(Sentinel::getUser()->profile_image)}} " class="img-thumbnail" height="200">
                                @else
                                <img src="{{static_asset('default-image/user.jpg') }}" height="200" class="img-thumbnail">
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
</section>

@endsection