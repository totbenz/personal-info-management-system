<x-modal name="create-account-modal">
    <x-card title="Create New Account">
        <form action="{{ route('accounts.store') }}" method="POST">
            @csrf
            <div>
                <label for="personnel_id" class="block font-medium text-sm text-gray-700 mb-1">Employee ID</label>
            
                <x-select
                    name="personnel_id"
                    wire:model.live.debounce.300ms="selectedPersonnelId"
                    placeholder="Select a Employee ID"
                    :async-data="route('api.personnel_list.index', ['without_accounts' => 1])"
                    option-label="personnel_id"
                    option-value="personnel_id"
                    option-description="full_name" 
                />
            </div>

            <div class="mt-4">
                    <x-input id="email" label="Email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    @if ($errors->has('email') && old('email'))
                        <span class="text-xs text-red-600">{{ $errors->first('email') }}</span>
                    @endif
            </div>

            <div class="mt-4">
                <x-input id="password" label="Password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    @if ($errors->has('password'))
                        <span class="text-xs text-red-600">{{ $errors->first('password') }}</span>
                    @endif
                    <span id="password-requirements-error" class="text-xs text-red-600" style="display:none;"></span>
            </div>

            <div class="mt-4">
                <x-input id="password_confirmation" label="Confirm Password" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <span id="password-match-error" class="text-xs text-red-600" style="display:none;">Passwords do not match.</span>
            </div>

            <div class="mt-4">
                <x-native-select name="role" id="role" class="form-control" label="Role">
                        <option value="teacher">Teacher</option>
                        <option value="school_head">School Head</option>
                        <option value="non_teaching">Non-Teaching Personnel</option>
                        <option value="admin">Admin</option>
                </x-native-select>
            </div>

            <div class="mt-5 flex justify-end gap-x-4">
                <div class="w-1/6">
                    <x-button type="button" x-on:click="close" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105"/>
                </div>
                <div class="w-1/6">
                    <x-button type="submit" label="Create" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150"/>
                </div>
            </div>
        </form>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const password = document.getElementById('password');
                    const confirm = document.getElementById('password_confirmation');
                    const errorMsg = document.getElementById('password-match-error');
                        const requirementsError = document.getElementById('password-requirements-error');
                        function checkPasswordRequirements(pw) {
                            const requirements = [
                                { regex: /.{8,}/, message: 'At least 8 characters.' },
                                { regex: /[A-Z]/, message: 'At least one uppercase letter.' },
                                { regex: /[a-z]/, message: 'At least one lowercase letter.' },
                                { regex: /[0-9]/, message: 'At least one number.' },
                                { regex: /[^A-Za-z0-9]/, message: 'At least one special character.' }
                            ];
                            let failed = requirements.filter(r => !r.regex.test(pw));
                                if (password === document.activeElement && pw.length > 0 && failed.length > 0) {
                                    requirementsError.style.display = 'block';
                                    requirementsError.textContent = 'Password must have: ' + failed.map(f => f.message).join(' ');
                                } else {
                                    requirementsError.style.display = 'none';
                                }
                        }
                    function checkMatch() {
                        if (confirm.value.length > 0 && password.value !== confirm.value) {
                            errorMsg.style.display = 'block';
                        } else {
                            errorMsg.style.display = 'none';
                        }
                            checkPasswordRequirements(password.value);
                    }
                    password.addEventListener('input', checkMatch);
                    confirm.addEventListener('input', checkMatch);
                        password.addEventListener('focus', function() {
                            checkPasswordRequirements(password.value);
                        });
                        password.addEventListener('blur', function() {
                            requirementsError.style.display = 'none';
                        });
                        // Initial check in case of autofill
                        requirementsError.style.display = 'none';
                });
            </script>
    </x-card>
</x-modal>
