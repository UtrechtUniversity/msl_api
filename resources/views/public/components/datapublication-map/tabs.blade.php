<div id="sidebar-tabs" class=" flex flex-col flex-1 justify-between items-center py-5 gap-2 ">

    <div role="tablist" class="tabs tabs-box tabs-md flex flex-row justify-around bg-primary-100 rounded-none w-70 gap-1">
        @foreach ($tabs as $tab)
            <a data-tab={{ $tab['name'] }} role="tab"
                class="tab {{ $tab['default'] ? 'tab-active' : '' }} flex-1 w-full sm:w-20 hover-interactive !text-primary-900">{{ $tab['name'] }}</a>
        @endforeach
    </div>

    <div id='sidebar-content' class=" w-70 bg-primary-100 flex-1 ">
        @foreach ($tabs as $tab)
            <div data-content={{ $tab['name'] }} {{ !$tab['default'] ? 'hidden' : '' }}>
                @include($tab['component'])
            </div>
        @endforeach
    </div>
    @vite(['resources/ts/dataPublication/tab-handle.ts'])
</div>
