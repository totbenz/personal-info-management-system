
<x-app-layout>
    @include('personnel.modal.delete-personnel-confirmation-modal')
    @include('personnel.modal.delete-children-confirmation-modal')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $personnel->fullName() }}'s Profile
                </h2>
                <span class="ms-5 tracking-wider text-white bg-dandelion px-4 py-0.5 text-sm rounded-full leading-loose mx-2 font-semibold" title="">
                    <i class="fas fa-star" aria-hidden="true"></i> {{ $personnel->position->title }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="relative h-[38rem] bg-slate-100">
        <div>
            @livewire('personnel-navigation', ['personnelId' => $personnel->id])
        </div>
    </div>


</x-app-layout>
