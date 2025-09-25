{{-- 
    vars

    $ElementsArray => array(string)
    $sectionName => string, describes the name of the elements for interactions

--}}

<div class="form-control flex flex-col phone:flex-row">
    @foreach ($ElementsArray as $choice)
        <label class="label cursor-pointer flex flex-col gap-4 p-2 phone:w-20 sm:w-28 border sm:border-0 ">
            <span class="label-text text-center">{{ $choice }}</span>
            <input type="radio" name={{ $sectionName }} class="radio checked:bg-secondary-500" checked="false" />
        </label>
    @endforeach
</div>