<a class="self-center w-9/12 no-underline hover-interactive p-4"
    href="{{ route('lab-detail', ['id' => $laboratory['name']]) }}">
    @if (isset($laboratory['title']))
        <h4 class="text-left">{{ $laboratory['title'] }}</h4>
    @endif

    @if (isset($laboratory['msl_domain_name']))
        <p>{{ $laboratory['msl_domain_name'] }}</p>
    @endif

    @if (isset($laboratory['msl_organization_name']))
        <p class="italic ">{{ $laboratory['msl_organization_name'] }}</p>
    @endif
</a>
