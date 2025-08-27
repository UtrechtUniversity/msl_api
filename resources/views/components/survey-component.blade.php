<form method="POST"
    autocomplete="off"
    action={{ route('survey-form-process', ['surveyName' => $surveyName]) }}
    class="space-y-8 flex flex-col justify-center items-center" novalidate>
        @csrf
        <x-honeypot />

    @foreach ($allQuestions as $question)

            <x-question-component :questionConfig="$question" />

    @endforeach

                

    <div class="flex place-content-center">
        <button type="submit" class="btn btn-primary" >Submit</button>
    </div>
    
</form>
