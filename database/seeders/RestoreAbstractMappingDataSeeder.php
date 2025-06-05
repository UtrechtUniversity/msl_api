<?php

namespace Database\Seeders;

use App\Models\Keyword;
use App\Models\Vocabulary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestoreAbstractMappingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // materials vocabulary
        $vocabulary = Vocabulary::where('name', '=', 'materials')->where('version', '=', '1.3')->first();

        $keywords = $vocabulary->keywords()->where('level', '=', 1)->get();

        foreach($keywords as $keyword)
        {
            $searchKeywords = $keyword->keyword_search;

            foreach($searchKeywords as $searchKeyword)
            {
                $searchKeyword->exclude_abstract_mapping = true;
                $searchKeyword->save();
            }
        }

        // porefluids vocabulary
        $vocabulary = Vocabulary::where('name', '=', 'porefluids')->where('version', '=', '1.3')->first();

        $keywords = $vocabulary->keywords()->where('level', '=', 1)->get();

        foreach($keywords as $keyword)
        {
            $searchKeywords = $keyword->keyword_search;

            foreach($searchKeywords as $searchKeyword)
            {
                $searchKeyword->exclude_abstract_mapping = true;
                $searchKeyword->save();
            }
        }

        // rockphysics vocabulary
        $vocabulary = Vocabulary::where('name', '=', 'rockphysics')->where('version', '=', '1.3')->first();

        $keywords = $vocabulary->keywords()->where('level', '=', 1)->get();

        foreach($keywords as $keyword)
        {
            $searchKeywords = $keyword->keyword_search;

            foreach($searchKeywords as $searchKeyword)
            {
                $searchKeyword->exclude_abstract_mapping = true;
                $searchKeyword->save();
            }
        }

        // analogue vocabulary
        $vocabulary = Vocabulary::where('name', '=', 'analogue')->where('version', '=', '1.3')->first();

        $keywords = $vocabulary->keywords()->where('level', '=', 1)->get();

        foreach($keywords as $keyword)
        {
            $searchKeywords = $keyword->keyword_search;

            foreach($searchKeywords as $searchKeyword)
            {
                $searchKeyword->exclude_abstract_mapping = true;
                $searchKeyword->save();
            }

            if($keyword->value == 'Modeled structure' || $keyword->value == 'Modeled geomorphological feature') {
                $this->setExcludeAbstractMappingAllChildren($keyword);
            }
        }

        // geologicalage vocabulary
        $vocabulary = Vocabulary::where('name', '=', 'geologicalage')->where('version', '=', '1.3')->first();

        $keywords = $vocabulary->keywords()->where('level', '=', 1)->get();

        foreach($keywords as $keyword)
        {
            $searchKeywords = $keyword->keyword_search;

            foreach($searchKeywords as $searchKeyword)
            {
                $searchKeyword->exclude_abstract_mapping = true;
                $searchKeyword->save();
            }
        }

        // geologicalsetting vocabulary
        $vocabulary = Vocabulary::where('name', '=', 'geologicalsetting')->where('version', '=', '1.3')->first();

        $keywords = $vocabulary->keywords()->where('level', '=', 1)->get();

        foreach($keywords as $keyword)
        {
            $searchKeywords = $keyword->keyword_search;

            foreach($searchKeywords as $searchKeyword)
            {
                $searchKeyword->exclude_abstract_mapping = true;
                $searchKeyword->save();
            }
        }

        // paleomagnetism vocabulary
        $vocabulary = Vocabulary::where('name', '=', 'paleomagnetism')->where('version', '=', '1.3')->first();

        $keywords = $vocabulary->keywords()->where('level', '=', 1)->get();

        foreach($keywords as $keyword)
        {
            $searchKeywords = $keyword->keyword_search;

            foreach($searchKeywords as $searchKeyword)
            {
                $searchKeyword->exclude_abstract_mapping = true;
                $searchKeyword->save();
            }
        }

        // geochemistry vocabulary
        $vocabulary = Vocabulary::where('name', '=', 'geochemistry')->where('version', '=', '1.3')->first();

        $keywords = $vocabulary->keywords()->where('level', '=', 1)->get();

        foreach($keywords as $keyword)
        {
            $searchKeywords = $keyword->keyword_search;

            foreach($searchKeywords as $searchKeyword)
            {
                $searchKeyword->exclude_abstract_mapping = true;
                $searchKeyword->save();
            }
        }

        // microscopy vocabulary
        $vocabulary = Vocabulary::where('name', '=', 'microscopy')->where('version', '=', '1.3')->first();

        $keywords = $vocabulary->keywords()->where('level', '=', 1)->get();

        foreach($keywords as $keyword)
        {
            $searchKeywords = $keyword->keyword_search;

            foreach($searchKeywords as $searchKeyword)
            {
                $searchKeyword->exclude_abstract_mapping = true;
                $searchKeyword->save();
            }
        }

        // subsurface vocabulary

        // no changes needed

        // testbeds vocabulary
        $vocabulary = Vocabulary::where('name', '=', 'testbeds')->where('version', '=', '1.3')->first();

        $keywords = $vocabulary->keywords()->where('level', '=', 1)->get();

        foreach($keywords as $keyword)
        {
            $searchKeywords = $keyword->keyword_search;

            foreach($searchKeywords as $searchKeyword)
            {
                $searchKeyword->exclude_abstract_mapping = true;
                $searchKeyword->save();
            }
        }

    }

    private function setExcludeAbstractMappingAllChildren(Keyword $keyword)
    {
        $searchKeywords = $keyword->keyword_search;

        foreach($searchKeywords as $searchKeyword)
        {
            $searchKeyword->exclude_abstract_mapping = true;
            $searchKeyword->save();
        }

        $children = $keyword->getChildren();

        if($children->count() > 0){
            foreach($children as $child) {
                $this->setExcludeAbstractMappingAllChildren($child);
            }
        }
    }
}
