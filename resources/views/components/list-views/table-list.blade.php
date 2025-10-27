@props([   
        'entries' => [],
        'withKeys' => true,
        'numericKeys' => false,
        'textSize' => 'sm'
    ])

<table class="table-fixed w-full">
    <tbody>
        @foreach ($entries as $key => $value)
            @if ($value != '')
                <tr>
                    @if ($withKeys)
                        <td class=" w-20 sm:w-40 text-{{ $textSize }} p-0">
                                @if (!$numericKeys)
                                    {{ $key }}
                                @else
                                    @if (is_numeric($key))
                                        {{ $key }}
                                    @else
                                        {{ '' }}
                                    @endif
                                @endif
                        </td>
                    @endif

                    <td class="text-{{ $textSize }} p-0">
                        @if (filter_var($value, FILTER_VALIDATE_URL))
                            <a class='underline hover-interactive' href={{ $value }}>{{ $value }}</a>
                        @else
                            {{ $value }}
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach      
    </tbody>
</table>    