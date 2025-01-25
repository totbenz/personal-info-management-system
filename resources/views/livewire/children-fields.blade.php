<div>
    <!-- Existing Children -->
@foreach ($children as $index => $child)
<div class="mb-2 px-3 w-full flex items-center space-x-3 h-12 border border-gray-200 rounded focus:outline-none">
    <div class="w-8/12 ps-3 text-xs">
        <div class="sm:flex space-x-1 rounded-md border border-gray-300">
            <input id="first_name_{{ $index }}" wire:model="children.{{ $index }}.first_name" type="text" name="children[{{ $index }}][first_name]" placeholder="First Name" class="w-[14rem] rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
            <input id="middle_name_{{ $index }}" wire:model="children.{{ $index }}.middle_name" type="text" name="children[{{ $index }}][middle_name]" placeholder="M.I." class="w-[4rem] rounded-none border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
            <input id="last_name_{{ $index }}" wire:model="children.{{ $index }}.last_name" type="text" name="children[{{ $index }}][last_name]" placeholder="Last Name" class="w-[14rem] rounded-none border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
            <input id="name_ext_{{ $index }}" wire:model="children.{{ $index }}.name_ext" type="text" name="children[{{ $index }}][name_ext]" placeholder="Ext." class="w-[4rem] rounded-e-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
        </div>
    </div>
    <div class="w-3/12 ps-3 text-xs">
        <x-input wire:model="children.{{ $index }}.date_of_birth" type="date" name="children[{{ $index }}][date_of_birth]" class="text-xs" placeholder="0" required/>
    </div>
    <div class="w-1/12 pe-3 text-xs text-end">
        <button wire:click.prevent="removeField({{ $index }})" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
@endforeach

<!-- New Children -->
@foreach ($new_children as $index => $new_child)
<div class="mb-2 w-full flex items-center space-x-2 h-12 border border-gray-200 rounded focus:outline-none"
     x-cloak
     x-transition:enter="transition ease-in-out duration-200"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in-out duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95">
    <div class="w-8/12 ps-3 text-xs">
        <div class="sm:flex space-x-1 rounded-md border border-gray-300">
            <input id="first_name_{{ $index }}" wire:model="new_children.{{ $index }}.first_name" type="text" name="new_children[{{ $index }}][first_name]" placeholder="First Name" class="w-[14rem] rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
            <input id="middle_name_{{ $index }}" wire:model="new_children.{{ $index }}.middle_name" type="text" name="new_children[{{ $index }}][middle_name]" placeholder="M.I." class="w-[4rem] rounded-none border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
            <input id="last_name_{{ $index }}" wire:model="new_children.{{ $index }}.last_name" type="text" name="new_children[{{ $index }}][last_name]" placeholder="Last Name" class="w-[14rem] rounded-none border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
            <input id="name_ext_{{ $index }}" wire:model="new_children.{{ $index }}.name_ext" type="text" name="new_children[{{ $index }}][name_ext]" placeholder="Ext." class="w-[4rem] rounded-e-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
        </div>
    </div>
    <div class="w-3/12 ps-3 text-xs">
        <x-input wire:model="new_children.{{ $index }}.date_of_birth" type="date" name="new_children[{{ $index }}][date_of_birth]" class="text-xs" placeholder="0" required/>
    </div>
    <div class="w-1/12 pe-3 text-xs text-end">
        <button wire:click.prevent="removeNewField({{ $index }})" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
@endforeach

<!-- Add Child Button -->
<button type="button" wire:click.prevent="addField" class="mt-2 text-xs text-blue-600 hover:underline">Add Child</button>

</div>
