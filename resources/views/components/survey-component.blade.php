<div class="w-full">

    @foreach ($allQuestions as $question)

            <x-question-component :questionConfig="$question" />

    @endforeach

</div>