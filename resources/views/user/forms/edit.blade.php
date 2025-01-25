
<x-modal name="edit-account-modal">
    <x-card title="Edit Account">
        <div class="px-8 py-5">
            <form action="{{ route('accounts.update', ['account' => $account->id]) }}" method="POST">
                @csrf
                @method('PUT') <!-- Use PUT or PATCH method for updates -->
                <div>
                    <x-input id="personnel_id" label="Personnel ID" class="block mt-1 w-full" type="number" name="personnel_id" value="{{ $account->personnel->personnel_id }}" required/>
                </div>

                <div class="mt-4">
                    <x-input id="email" label="Email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" value="{{ $account->email }}"/>
                </div>

                {{-- <div class="mt-4">
                    <x-input id="password" label="Password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-input id="password_confirmation" label="Confirm Password" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div> --}}

                <div class="mt-4">
                    <x-native-select value="{{ $account->role }}" class="form-control" label="Role" name="role" id="role">
                        <option value="teacher">Personnel</option>
                        <option value="school_head">School Head</option>
                        <option value="admin">Admin</option>
                    </x-native-select>
                </div>

                <div class="mt-5 flex justify-end gap-x-4">
                    <div class="w-1/6">
                        <x-button x-on:click="close" label="Cancel" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-danger hover:bg-danger-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-700"/>
                    </div>
                    <div class="w-1/6">
                        <x-button type="submit" label="Create" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-main hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700"/>
                    </div>
                </div>
            </form>
        </div>
    </x-card>
</x-modal>
