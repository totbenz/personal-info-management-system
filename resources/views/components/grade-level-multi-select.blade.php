@props(['curricular_classification'])

<div x-data="{ options: [], open: false }" class="w-full relative">
    <div @click="open = !open" class="placeholder-dandelion-400 border border-dandelion-300 focus:ring-main-500 focus:border-main-500 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm">
        <template x-if="options.length === 0">
            <p class="my-1 py-1 text-gray-500">Choose Curricular Classifications</p>
        </template>
        @if(isset($curricularClassification))
        <P>{{ $curricularClassification }}</P>
            <div class="flex flex-wrap items-center space-x-1" x-init="options = {{ json_encode($curricular_classification) }}">
                <template x-for="(option, index) in options.slice().sort((a, b) => a.match(/\d+/) - b.match(/\d+/))" :key="index">
                    <span class="mt-1 mb-2 py-1 px-3 flex justify-between items-center space-x-3 bg-gray-200 shadow-md text-gray-800 rounded-full text-sm hover:bg-gray-300 hover:text-gray-600 duration-200" @click.stop="options.splice(options.indexOf(option), 1), open = true">
                        <p class="capitalize  truncate" x-text="option"></p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </span>
                </template>
            </div>
        @else
            <div class="flex flex-wrap items-center space-x-1" x-init="options = []">
                <template x-for="(option, index) in options.slice().sort((a, b) => a.match(/\d+/) - b.match(/\d+/))" :key="index">
                    <span class="mt-1 mb-2 py-1 px-3 flex justify-between items-center space-x-3 bg-gray-200 shadow-md text-gray-800 rounded-full text-sm hover:bg-gray-300 hover:text-gray-600 duration-200" @click.stop="options.splice(options.indexOf(option), 1), open = true">
                        <p class="capitalize  truncate" x-text="option"></p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </span>
                </template>
            </div>
        @endif
    </div>

    <input id="options" wire:model="curricular_classification" type="hidden" name="curricular_classification" :value="options" required>
    <div x-show="open" x-trap="open"
         @click.outside="open = false"
         @keydown.escape.window="open = false"
         x-transition:enter=" ease-[cubic-bezier(.3,2.3,.6,1)] duration-200"
         x-transition:enter-start="!opacity-0 !mt-1"
         x-transition:enter-end="!opacity-1 !mt-1"
         x-transition:leave=" ease-out duration-200"
         x-transition:leave-start="!opacity-1 !mt-1"
         x-transition:leave-end="!opacity-0 !mt-1"
         class="px-3 rounded-lg flex gap-3 w-full shadow-lg x-50 flex-col bg-white mt-1">
        <div class="pb-3 flex space-x-5">
            <div class="w-1/2">
                <div class="flex items-center hover:bg-gray-100 hover:rounded-md duration-200">
                    <input x-model="options" id="grade_1" type="checkbox"value="grade 1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded" x-bind:checked="options.includes('grade 1')">
                    <label for="grade_1" class="ml-2 py-0.5 font-medium text-gray-900 flex-grow">Grade 1</label>
                </div>
                <div class="flex items-center hover:bg-gray-100 hover:rounded-md duration-200">
                    <input x-model="options" id="grade_2" type="checkbox" value="grade 2" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded" x-bind:checked="options.includes('grade 2')">
                    <label for="grade_2" class="ml-2 py-0.5 font-medium text-gray-900 flex-grow">Grade 2</label>
                </div>
                <div class="flex items-center hover:bg-gray-100 hover:rounded-md duration-200">
                    <input x-model="options" id="grade_3" type="checkbox" value="grade 3" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded" x-bind:checked="options.includes('grade 3')">
                    <label for="grade_3" class="ml-2 py-0.5 font-medium text-gray-900 flex-grow">Grade 3</label>
                </div>
                <div class="flex items-center hover:bg-gray-100 hover:rounded-md duration-200">
                    <input x-model="options" id="grade_4" type="checkbox" value="grade 4" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"" x-bind:checked="options.includes('grade 4')">
                    <label for="grade_4" class="ml-2 py-0.5 font-medium text-gray-900 flex-grow">Grade 4</label>
                </div>
                <div class="flex items-center hover:bg-gray-100 hover:rounded-md duration-200">
                    <input x-model="options" id="grade_5" type="checkbox" value="grade 5" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"" x-bind:checked="options.includes('grade 5')">
                    <label for="grade_5" class="ml-2 py-0.5 font-medium text-gray-900 flex-grow">Grade 5</label>
                </div>
                <div class="flex items-center hover:bg-gray-100 hover:rounded-md duration-200">
                    <input x-model="options" id="grade_6" type="checkbox" value="grade 6" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"" x-bind:checked="options.includes('grade 6')">
                    <label for="grade_6" class="ml-2 py-0.5 font-medium text-gray-900 flex-grow">Grade 6</label>
                </div>
            </div>
            <div class="w-1/2">
                <div class="flex items-center hover:bg-gray-100 hover:rounded-md duration-200">
                    <input x-model="options" id="grade_7" type="checkbox" value="grade 7" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"" x-bind:checked="options.includes('grade 7')">
                    <label for="grade_7" class="ml-2 py-0.5 font-medium text-gray-900 flex-grow">Grade 7</label>
                </div>
                <div class="flex items-center hover:bg-gray-100 hover:rounded-md duration-200" x-bind:checked="options.includes('grade 8')">
                    <input x-model="options" id="grade_8" type="checkbox" value="grade 8" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"" x-bind:checked="options.includes('grade 8')">
                    <label for="grade_8" class="ml-2 py-0.5 font-medium text-gray-900 flex-grow">Grade 8</label>
                </div>
                <div class="flex items-center hover:bg-gray-100 hover:rounded-md duration-200">
                    <input x-model="options" id="grade_9" type="checkbox" value="grade 9" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"" x-bind:checked="options.includes('grade 9')">
                    <label for="grade_9" class="ml-2 py-0.5 font-medium text-gray-900 flex-grow">Grade 9</label>
                </div>
                <div class="flex items-center hover:bg-gray-100 hover:rounded-md duration-200">
                    <input x-model="options" id="grade_10" type="checkbox" value="grade 10" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"" x-bind:checked="options.includes('grade 10')">
                    <label for="grade_10" class="ml-2 py-0.5 font-medium text-gray-900 flex-grow">Grade 10</label>
                </div>
                <div class="flex items-center hover:bg-gray-100 hover:rounded-md duration-200">
                    <input x-model="options" id="grade_11" type="checkbox" value="grade 11" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"" x-bind:checked="options.includes('grade 11')">
                    <label for="grade_11" class="ml-2 py-0.5 font-medium text-gray-900 flex-grow">Grade 11</label>
                </div>
                <div class="flex items-center hover:bg-gray-100 hover:rounded-md duration-200">
                    <input x-model="options" id="grade_12" type="checkbox" value="grade 12" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"" x-bind:checked="options.includes('grade 12')">
                    <label for="grade_12" class="ml-2 py-0.5 font-medium text-gray-900 flex-grow">Grade 12</label>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

</script>
