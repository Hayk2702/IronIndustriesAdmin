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
                    {{ __('variable.forgot_password') }}
                    </h1>
                    <p class="body_1_medium">{{ __("variable.forgot_helping_message") }}</p>
                    <form id="forgot-password-form" method="POST" action="{{ route('password.email', ['locale' => app()->getLocale()]) }}" class="auth-left__wrapper-content">
                        @csrf
                        <div class="label-field">
                            <label class="label-field" for="email">
                                <span class="label-field-text">{{ __('variable.email') }}</span>
                                <div class="label-field-outer-input @error('email') error @enderror">
                                    <input class="label-field-input" placeholder="{{ __('variable.Email Address') }}" type="text" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>
                            </label>
                            @error('email')
                            <div class="label-field-alert">
                                <img src="/images/login/error-warning-fill.svg" alt="">
                                <span class="label-field-alert-message">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>
                        <div class="auth-left__wrapper-submit">
                            <button type="submit" id="submit-button" class="custom-btn primary">
                                {{ __('variable.reset_password') }}
                            </button>
                        </div>
                    </form>
                    <!-- <p id="resend-message" class="auth-left__wrapper-forgot__helping" style="display: none;">
                    {{ __('variable.still_cant_find_email') }}
                    <span id="countdown">00:30</span> {{ __("min.") }}
                    </p> -->
                    <div class="auth-left__wrapper-forgot__helping">
                        <span class="auth-left__wrapper-forgot__backText">
                            {{ __("variable.back_to") }}
                        </span>
                        <a class="auth-left__wrapper-login__forgot" href="{{ route('login', ['locale' => app()->getLocale()]) }}">
                            {{ __('variable.login_page') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let formElement = document.getElementById('forgot-password-form');
        formElement.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            const submitButton = document.getElementById('submit-button');
            // const resendMessage = document.getElementById('resend-message');
            const countdownElement = document.getElementById('countdown');

            submitButton.disabled = true;
            // resendMessage.style.display = 'block';

            // Start countdown
            let timeLeft = 30;
            const countdownInterval = setInterval(() => {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                countdownElement.innerText = `00:${seconds < 10 ? '0' : ''}${seconds}`;

                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    submitButton.disabled = false;
                    // resendMessage.style.display = 'none';
                }
            }, 1000);
            formElement.submit();
        });
    })
</script>
