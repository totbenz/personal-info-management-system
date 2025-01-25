{{-- <div>
    <div class="px-2.5 py-1 bg-dandelion border-2 border-dandelion rounded-full hover:bg-slate-300 duration-300">
        <h6 class="text-main text-lg">
            {{ Auth::user()->personnel->school->school_name[0] }}
        </h6>
    </div>
</div> --}}
<div class="flex justify-center">
    <div class="flex items-center text-center px-3 py-2.5 bg-gray-300 m-1 mr-2 rounded-full text-gray-800 hover:bg-gray-400 duration-300 hover:scale-105">
        <p class="text-center font-semibold">{{ Auth::user()->personnel->school->school_name[0] }}{{ Auth::user()->personnel->first_name[0] }}</p>
    </div>
</div>
