<div>
    @switch($questionConfig->question_type->name)
        @case('text')
            <x-forms.text-question 
                :title="$questionConfig->question->title"
                :titleBold="$questionConfig->question->titleBold"
                :sectionName="$questionConfig->question->sectionName"
                :placeholder="$questionConfig->question->placeholder"
                :textBlock="$questionConfig->question->textBlock"
            />
        @break
        
        @case('select')
            <x-forms.select-question
            :title="$questionConfig->question->title"
            :sectionName="$questionConfig->question->sectionName"
            :placeholder="$questionConfig->question->placeholder"
            :options="$questionConfig->question->options"
            :titleBold="$questionConfig->question->titleBold"
            />
        @break

        @case('radio')
            <x-forms.radio-select
            :title="$questionConfig->question->title"
            :sectionName="$questionConfig->question->sectionName"
            :options="$questionConfig->question->options"
            :titleBold="$questionConfig->question->titleBold"
            />
        @break

        @case('check')
            <x-forms.check-box
            :title="$questionConfig->question->title"
            :sectionName="$questionConfig->question->sectionName"
            :options="$questionConfig->question->options"
            :titleBold="$questionConfig->question->titleBold"
            />
        @break

        @case('displayBlade')

        {{-- @php
            dd($questionConfig->question->bladeVars);
        @endphp --}}

            <br>
            <x-forms.display-blade-content
            :bladeName="$questionConfig->question->bladeName"
            :bladeVars="$questionConfig->question->bladeVars"
            />
            <br>
        @break

        @default
            <p>Component not found</p>
    @endswitch
</div>