{{-- 
    data    = (array)
--}}

<a class="self-center w-9/12 no-underline" href="{{ route('lab-detail-equipment', ['id' => $equipment['msl_lab_ckan_name']] ) }}">

<div class="hover:bg-secondary-100 ">

    <div class="p-4"> 
        
        @if (isset( $data['title']))
            <h4 class="text-left">{{  $data['title'] }}</h4> 
        @endif

        @if (isset($data['msl_domain_name']))
            <p>{{ $data['msl_domain_name'] }}</p>
        @endif 

        @if (isset($data['msl_organization_name']))
            <p class="italic ">{{ $data['msl_organization_name'] }}</p>
        @endif        
    </div>

</div>    

</a>