<div id="sidebar-tabs" class=" flex flex-col justify-center items-center py-5 bg-primary-200 ">

    <div role="tablist"
        class="tabs tabs-box tabs-md flex flex-row justify-around gap-1 bg-primary-100 rounded-none px-15">
        @foreach ($tabs as $tab)
            <a data-tab={{ $tab['name'] }} role="tab"
                class="tab {{ $tab['default'] ? 'tab-active' : '' }} w-full sm:w-20 hover-interactive !text-primary-900">{{ $tab['name'] }}</a>
        @endforeach
    </div>
    <div id='sidebar-content'>
        @foreach ($tabs as $tab)
            <div data-content={{ $tab['name'] }} {{ !$tab['default'] ? 'hidden' : '' }}>
                @include($tab['component'])
            </div>
        @endforeach
    </div>
    @vite(['resources/ts/dataPublication/tab-handle.ts'])
</div>
