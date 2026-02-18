@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-container__wrap">
        <div class="auth-left__container">
            <div class="auth-left__wrapper">
                <div class="auth-left__wrapper-logo">
                        <img src="/images/login/parking-logo.svg" alt="ITR parking logo">
                </div>
                <h1 class="auth-left__wrapper-title h1_auth_bold">
                    {{ __('variable.reset_password') }}
                </h1>
                <form method="POST" action="{{ route('password.update', ['locale' => app()->getLocale()]) }}" class="auth-left__wrapper-content">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                    <div class="label-field" >
                        <label class="label-field" for="password" >
                            <span class="label-field-text">{{__('variable.new_password')}}</span>
                            <div class="label-field-outer-input @error('password') error @enderror">
                                <input class="label-field-input" :placeholder="__('variable.new_password')" type="password" id="password" name="password" required autocomplete="new-password">
                            </div>
                        </label>
                        @error('password')
                        <div class="label-field-alert">
                            <img src="/images/login/error-warning-fill.svg" alt="">
                            <span class="label-field-alert-message">{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <div class="label-field auth-left__wrapper-content-item">
                        <label class="label-field" for="password-confirm" >
                            <span class="label-field-text">{{__('variable.confirm_new_password')}}</span>
                            <div class="label-field-outer-input @error('password') error @enderror">
                                <input class="label-field-input" :placeholder="__('variable.confirm_new_password')" id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </label>
                    </div>

                    <div class="auth-left__wrapper-submit">
                        <button type="submit" class="custom-btn primary">
                            {{ __('variable.save_new_password') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>  
</div>
@endsection
