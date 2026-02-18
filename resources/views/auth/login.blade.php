@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-container__wrap">
            <div class="auth-left__container">
                <div class="auth-left__wrapper">
                    <div class="auth-left__wrapper-logo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 832 1024">
                            <path fill="currentColor"
                                  d="M773 832q-24 52-73 102t-109 90H241q-56-37-103.5-88T64 832Q0 704 0 320q0-35 11-96.5T32 128l160-96Q291 0 419 0q121 0 221 32l160 96q10 34 21 95.5t11 96.5q0 384-59 512zM271 960h294q88-61 139-160l-64 32h-64l-48-64H304l-48 64h-64l-64-32q43 90 143 160zm497-768L606 92q-33-11-65-17l-29 117q-7 27-23 45.5T448 256h-64q-24 0-40.5-19.5T320 192L291 76q-30 6-60 16L64 192v224q0 55 16 137.5T96 672q0 24 6 51l125 62l61-81h256l61 81l125-62q6-28 6-51q0-34 16-118.5T768 416V192zM512 416l192-32v64l-192 64v-96zm-384-32l192 32v96l-192-64v-64z"/>
                        </svg>
                    </div>
                    <h1 class="auth-left__wrapper-title h1_auth_bold">
                        {{ __('variable.login') }}
                    </h1>
                    <form method="POST" action="{{ route('login', ['locale' => app()->getLocale()])}}" class="auth-left__wrapper-content">
                        @csrf
                        @php
                            if(isset($data)){
                        @endphp
                        <input type="hidden" name="redirect_url" value="{{ $data['redirect_url']}}">
                        <input type="hidden" name="user_id" value="{{ $data['user_id']}}">
                        @php
                            }
                        @endphp

                        <div class="label-field">
                            <label class="label-field" for="email">
                                <span class="label-field-text">{{__('variable.email')}}</span>
                                <div class="label-field-outer-input @error('email') error @enderror">
                                    <svg class="label-field-icon-left" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 5L10 10L17 5M3 5V15C3 15.5523 3.44772 16 4 16H16C16.5523 16 17 15.5523 17 15V5M3 5C3 4.44772 3.44772 4 4 4H16C16.5523 4 17 4.44772 17 5" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <input class="label-field-input" placeholder="{{__('variable.Email Address')}}" type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>
                            </label>
                            @error('email')
                            <div class="label-field-alert">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 14C11.3137 14 14 11.3137 14 8C14 4.68629 11.3137 2 8 2C4.68629 2 2 4.68629 2 8C2 11.3137 4.68629 14 8 14Z" fill="#EF4444"/>
                                    <path d="M8 5V9M8 11H8.01" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="label-field-alert-message">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>

                        <div class="label-field auth-left__wrapper-content-item">
                            <label class="label-field" for="password">
                                <span class="label-field-text">{{ __('variable.password') }}</span>
                                <div class="label-field-outer-input @error('password') error @enderror">
                                    <svg class="label-field-icon-left" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 9V6C5 3.79086 6.79086 2 9 2H11C13.2091 2 15 3.79086 15 6V9M4 9H16C16.5523 9 17 9.44772 17 10V17C17 17.5523 16.5523 18 16 18H4C3.44772 18 3 17.5523 3 17V10C3 9.44772 3.44772 9 4 9Z" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <input class="label-field-input" id="password" name="password" placeholder="{{__('variable.password')}}" type="password" required autocomplete="current-password">
                                    <button class="label-field-btn" type="button" onclick="togglePasswordVisibility(event)">
                                        <svg id="toggle-icon-closed" class="label-field-input-icon active" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.26 11.602C2.942 11.305 2.783 11.156 2.719 10.901C2.679 10.748 2.679 10.253 2.719 10.1C2.783 9.845 2.942 9.696 3.26 9.399C4.579 8.15 7.029 6 10 6C12.971 6 15.421 8.15 16.74 9.399C17.058 9.696 17.217 9.845 17.281 10.1C17.321 10.253 17.321 10.748 17.281 10.901C17.217 11.156 17.058 11.305 16.74 11.602C15.421 12.851 12.971 15 10 15C7.029 15 4.579 12.851 3.26 11.602Z" stroke="#6B7280" stroke-width="1.5"/>
                                            <path d="M10 12C11.1046 12 12 11.1046 12 10C12 8.89543 11.1046 8 10 8C8.89543 8 8 8.89543 8 10C8 11.1046 8.89543 12 10 12Z" stroke="#6B7280" stroke-width="1.5"/>
                                            <path d="M3 3L17 17" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                        <svg id="toggle-icon-open" class="label-field-input-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                            <path d="M3.26 11.602C2.942 11.305 2.783 11.156 2.719 10.901C2.679 10.748 2.679 10.253 2.719 10.1C2.783 9.845 2.942 9.696 3.26 9.399C4.579 8.15 7.029 6 10 6C12.971 6 15.421 8.15 16.74 9.399C17.058 9.696 17.217 9.845 17.281 10.1C17.321 10.253 17.321 10.748 17.281 10.901C17.217 11.156 17.058 11.305 16.74 11.602C15.421 12.851 12.971 15 10 15C7.029 15 4.579 12.851 3.26 11.602Z" stroke="#6B7280" stroke-width="1.5"/>
                                            <path d="M10 12C11.1046 12 12 11.1046 12 10C12 8.89543 11.1046 8 10 8C8.89543 8 8 8.89543 8 10C8 11.1046 8.89543 12 10 12Z" stroke="#6B7280" stroke-width="1.5"/>
                                        </svg>
                                    </button>
                                </div>
                            </label>
                            @error('password')
                            <div class="label-field-alert">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 14C11.3137 14 14 11.3137 14 8C14 4.68629 11.3137 2 8 2C4.68629 2 2 4.68629 2 8C2 11.3137 4.68629 14 8 14Z" fill="#EF4444"/>
                                    <path d="M8 5V9M8 11H8.01" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="label-field-alert-message">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>

                        <div class="auth-left__wrapper-login__helping">
                            <div class="auth-left__wrapper-login__checkbox">
                                <input class="auth-left__wrapper-login__checkbox-inp" type="checkbox" name="remember"
                                       id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="auth-left__wrapper-login__checkbox-label" for="remember">
                                    {{ __('variable.remember_me') }}
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="auth-left__wrapper-login__forgot"
                                   href="{{ route('password.request', ['locale' => app()->getLocale()]) }}">
                                    {{ __('variable.forgot_password') }}
                                </a>
                            @endif
                        </div>

                        <div class="auth-left__wrapper-submit">
                            <button type="submit" class="custom-btn primary">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 3L17 10M17 10L9 17M17 10H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ __('variable.Login') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>

    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
    }

    .auth-container__wrap {
        width: 100%;
        max-width: 450px;
    }

    .auth-left__container {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }

    .auth-left__wrapper {
        padding: 48px 40px;
    }

    .auth-left__wrapper-logo {
        display: flex;
        justify-content: center;
        margin-bottom: 32px;
        animation: fadeInDown 0.6s ease-out;
    }

    .auth-left__wrapper-title {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        text-align: center;
        margin-bottom: 8px;
        animation: fadeInDown 0.6s ease-out 0.1s backwards;
    }

    .auth-left__wrapper-subtitle {
        text-align: center;
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 32px;
        animation: fadeInDown 0.6s ease-out 0.2s backwards;
    }

    .auth-left__wrapper-content {
        margin-top: -10px !important;
        display: flex;
        flex-direction: column;
        gap: 20px;
        animation: fadeInUp 0.6s ease-out 0.3s backwards;
    }

    .label-field {
        width: 100%;
    }

    .label-field-text {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .label-field-outer-input {
        position: relative;
        display: flex;
        align-items: center;
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .label-field-outer-input:focus-within {
        background: white;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .label-field-outer-input.error {
        border-color: #ef4444;
        background: #fef2f2;
    }

    .label-field-icon-left {
        margin-left: 16px;
        flex-shrink: 0;
    }

    .label-field-input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 14px 16px;
        font-size: 15px;
        color: #1f2937;
        outline: none;
    }

    .label-field-input::placeholder {
        color: #9ca3af;
    }

    .label-field-btn {
        background: none;
        border: none;
        padding: 0 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: opacity 0.2s;
    }

    .label-field-btn:hover {
        opacity: 0.7;
    }

    .label-field-input-icon {
        display: block;
    }

    .label-field-alert {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        padding: 8px 12px;
        background: #fef2f2;
        border-radius: 8px;
        border-left: 3px solid #ef4444;
    }

    .label-field-alert-message {
        font-size: 13px;
        color: #991b1b;
        font-weight: 500;
    }

    .auth-left__wrapper-login__helping {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 4px;
    }

    .auth-left__wrapper-login__checkbox {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .auth-left__wrapper-login__checkbox-inp {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #6366f1;
    }

    .auth-left__wrapper-login__checkbox-label {
        font-size: 14px;
        color: #4b5563;
        cursor: pointer;
        user-select: none;
    }

    .auth-left__wrapper-login__forgot {
        font-size: 14px;
        color: #6366f1;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }

    .auth-left__wrapper-login__forgot:hover {
        color: #4f46e5;
        text-decoration: underline;
    }

    .auth-left__wrapper-submit {
        margin-top: 12px;
    }

    .custom-btn {
        width: 100%;
        padding: 14px 24px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .custom-btn.primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    .custom-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
    }

    .custom-btn.primary:active {
        transform: translateY(0);
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 500px) {
        .auth-left__wrapper {
            padding: 32px 24px;
        }

        .auth-left__wrapper-title {
            font-size: 28px;
        }
    }
</style>

<script>
    function togglePasswordVisibility(event) {
        event.preventDefault();

        const passwordInput = document.getElementById('password');
        const toggleIconClosed = document.getElementById('toggle-icon-closed');
        const toggleIconOpen = document.getElementById('toggle-icon-open');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIconClosed.style.display = 'none';
            toggleIconOpen.style.display = 'block';
        } else {
            passwordInput.type = 'password';
            toggleIconClosed.style.display = 'block';
            toggleIconOpen.style.display = 'none';
        }
    }
</script>
