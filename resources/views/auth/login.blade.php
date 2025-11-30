<x-guest-layout>
    <div class="card shadow-sm" style="max-width: 420px; margin: 80px auto;">
        <div class="card-body p-4">

            <h3 class="text-center mb-4 fw-bold">
                <i class="fa-solid fa-warehouse me-2"></i>WMS Login
            </h3>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark text-white">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="yourname@example.com"
                            value="{{ old('email') }}"
                            required
                            autofocus>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark text-white">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Enter your password"
                            required>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                        <label class="form-check-label" for="remember_me">
                            Remember me
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            <small>Forgot Password?</small>
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn btn-primary w-100 mt-2">
                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                    Login
                </button>

                <!-- Divider -->
                <div class="text-center my-3">
                    <small class="text-muted">Don't have an account?</small>
                </div>

                <!-- Register Link -->
                <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100">
                    <i class="fa-solid fa-user-plus me-2"></i>
                    Create Account
                </a>

            </form>

        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-muted py-3 mt-auto">
        <small>&copy; PAD PAMELA RIZQI MAHARANI 2025 - WMS System</small>
    </footer>
</x-guest-layout>
