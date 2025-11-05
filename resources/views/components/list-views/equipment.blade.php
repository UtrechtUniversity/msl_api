{{-- 
    data    = (array)
--}}

<a class="self-center w-9/12 no-underline hover-interactive p-4" 
    href="{{ route('lab-detail-equipment', ['id' => $equipment['msl_lab_ckan_name']] ) }}">
    @if (isset( $data['title']))
        <h4 class="text-left">{{  $data['title'] }}</h4> 
    @endif

    @if (isset($data['msl_domain_name']))
        <p>{{ $data['msl_domain_name'] }}</p>
    @endif 

    @if (isset($data['msl_organization_name']))
        <p class="italic ">{{ $data['msl_organization_name'] }}</p>
    @endif        
</a>