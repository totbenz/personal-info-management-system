<div>
    <x-select
        label="Search a School"
        wire:model.defer="search"
        placeholder="Select school to get ID"
        :async-data="[
                        'api' => route('schools.index'),
                        'method' => 'POST', // default is GET
                        'params' => ['ble' => 'baz'], // default is []
                        'credentials' => 'include' // default is undefined
                    ]"
    />
</div>
