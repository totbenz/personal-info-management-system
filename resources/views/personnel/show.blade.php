<x-app-layout>
    @include('personnel.modal.delete-personnel-confirmation-modal')
    @include('personnel.modal.delete-children-confirmation-modal')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                    <svg class="w-6 h-6 mr-2 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $personnel->fullName() }}'s Profile
                </h2>
                <span class="ms-5 tracking-wider text-white bg-dandelion px-4 py-0.5 text-sm rounded-full leading-loose mx-2 font-semibold" title="">
                    <i class="fas fa-star" aria-hidden="true"></i> {{ $personnel->position->title }}
                </span>
            </div>
            <nav class="flex text-sm items-center" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.home') }}" class="text-blue-600 hover:underline flex items-center">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <span class="mx-2 text-gray-400">/</span>
                    </li>
                    <li class="inline-flex items-center">
                        <a href="{{ route('personnels.index') }}" class="text-blue-600 hover:underline flex items-center">
                            Personnel
                        </a>
                    </li>
                    <li>
                        <span class="mx-2 text-gray-400">/</span>
                    </li>
                    <li class="inline-flex items-center text-gray-500">
                        {{ $personnel->fullName() }}
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>


    <div class="relative h-[38rem] bg-slate-100">
        <div>
            @livewire('personnel-navigation', ['personnelId' => $personnel->id])
        </div>
    </div>
</x-app-layout>
