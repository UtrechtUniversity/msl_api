<a class="self-center w-9/12 no-underline hover-interactive p-4"
    href="{{ route('lab-detail-equipment', ['id' => $equipment->laboratory->ckan_id]) }}">
    @if ($equipment->name != "")
        <h4 class="text-left">{{ $equipment->name }}</h4>
    @endif

    @if ($equipment->domain_name != "")
        <p>{{ $equipment->domain_name }}</p>
    @endif

    @if ($equipment->laboratory)
        <p class="italic ">{{ $equipment->laboratory->laboratoryOrganization->name }}</p>
    @endif
</a>
