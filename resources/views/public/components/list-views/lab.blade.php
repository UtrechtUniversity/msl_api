<a class="self-center w-9/12 no-underline hover-interactive p-4"
    href="{{ route('lab-detail', ['id' => $laboratory->ckan_id]) }}">

    <h4 class="text-left">{{ $laboratory->name }}</h4>

    @if ($laboratory->fast_domain_name != "")
        <p>{{ $laboratory->fast_domain_name }}</p>
    @endif

    @if ($laboratory->laboratoryOrganization)
        <p class="italic ">{{ $laboratory->laboratoryOrganization->name }}</p>
    @endif
</a>
