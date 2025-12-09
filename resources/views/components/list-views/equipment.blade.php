{{-- 
    equipment    = (array)
--}}

<a class="self-center w-9/12 no-underline hover-interactive p-4"
    href="{{ route('lab-detail-equipment', ['id' => $equipment['msl_lab_ckan_name']]) }}">
    @if (isset($equipment['title']))
        <h4 class="text-left">{{ $equipment['title'] }}</h4>
    @endif

    @if (isset($equipment['msl_domain_name']))
        <p>{{ $equipment['msl_domain_name'] }}</p>
    @endif

    @if (isset($equipment['msl_organization_name']))
        <p class="italic ">{{ $equipment['msl_organization_name'] }}</p>
    @endif
</a>
