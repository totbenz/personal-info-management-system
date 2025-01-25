<div class="py-3" x-data='itemFields({{ isset($school) && $school->funded_items()->count() > 0 ? 1 : 0 }})'>
    <div>
        <div class="w-full flex h-10 border border-gray-100 bg-gray-lightest items-center">
            <h6 class="w-6/12 text-center py-2">
                <span class="text-xs text-gray-dark font-semibold uppercase">Title</span>
            </h6>
            <h6 class="w-3/12 text-center py-2 ">
                <span class="text-xs text-gray-dark font-semibold uppercase">Category</span>
            </h6>
            <h6 class="w-2/12 text-center py-2 ">
                <span class="text-xs text-gray-dark font-semibold uppercase">Incumbent</span>
            </h6>
            <h6 class="w-1/12 font-semibold"></h6>
        </div>
        <div class="mt-2" x-data="funded_items = {{ $school->funded_items }}">
            @if(isset($school) && $school->funded_items()->count() > 0)
            <p x-text="funded_items"></p>
                <template x-for="(funded_item, index) in funded_item" :key="index">
                    <div class="mb-2 w-full flex items-center space-x-4 h-14 border border-gray-200 rounded focus:outline-none"
                        x-cloak
                        x-transition:leave="transition ease-in-out duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95">
                        <div class="w-6/12 px-3 text-xs text-center">
                            <x-input x-model="funded_item.title" type="text" name="funded_item.title[]" class="text-xs self-center" required/>
                        </div>
                        <div class="w-3/12 ps-3 text-xs">
                            <select x-model="funded_item.category" name="funded_item.category[]" class="appearance-none block w-full bg-gray-50 text-gray-700 border border-gray-200 rounded py-2.5 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                                <option value="teaching"">Teaching</option>
                                <option value="non-teaching"">Non-Teaching</option>
                            </select>
                        </div>
                        <div class="w-2/12 ps-3 text-xs">
                            <x-input x-model="funded_item.incumbent" type="number" name="funded_item.incumbent[]" class="text-xs" placeholder="0" required/>
                            {{-- <p class="bg-pink-300" x-text="funded_items.length > 1"></p> --}}
                        </div>
                        <div class="w-1/12 ps-3 text-xs">
                            <div x-show="funded_items.length > 1">uwu</div>
                            <button x-show="funded_items.length > 1" @click="removeField()" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            @endif
            <div>
                <template x-for="(new_item, index) in new_items" :key="index">
                    <div class="mb-2 w-full flex items-center space-x-4 h-14 border border-gray-200 rounded focus:outline-none"
                         x-cloak
                         x-transition:enter="transition ease-in-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in-out duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95">
                        <div class="w-6/12 px-3 text-xs text-center">
                            <x-input x-model="new_item.title" type="text" name="new_item.title[]" class="text-xs self-center" required/>
                        </div>
                        <div class="w-3/12 ps-3 text-xs">
                            <select x-model="new_item.category" name="new_item.category[]" class="appearance-none block w-full bg-gray-50 text-gray-700 border border-gray-200 rounded py-2.5 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                                <option value="teaching"">Teaching</option>
                                <option value="non-teaching"">Non-Teaching</option>
                            </select>
                        </div>
                        <div class="w-2/12 ps-3 text-xs">
                            <x-input x-model="new_item.incumbent" type="number" name="new_item.incumbent[]" class="text-xs" placeholder="0" required/>
                        </div>
                        <div class="w-1/12 ps-3 text-xs">
                            <button x-show="new_items.length > 1" @click="removeField()" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <div class="mt-3 flex space-x-3 items-center">
        <div class="w-full">
            <button @click.prevent="addNewField()" class="py-2 w-full text-base bg-main text-white tracking-wide font-medium rounded hover:bg-main_hover hover:text-white duration-300 focus:outline-none">New Item</button>
        </div>
    </div>
    <script>
        function itemFields(with_funded_items) {
            return {
                new_items: with_funded_items ? [] : [{}],
                addNewField() {
                    this.new_items.push({
                        title: '',
                        category: '',
                        incumbent: ''
                    });
                },
                removeField(index) {
                    this.new_items.splice(index, 1);
                }
            };
        }
    </script>
</div>
