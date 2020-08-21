<?php

namespace N949mac\StopWords;

use N949mac\StopWords\Models\StopWord;

class StopWords{
    public static function check($string) {
        $stopWords = StopWord::all();

        foreach ($stopWords as $stopWord) {
            if (strpos(strtolower($string), strtolower($stopWord->word)) !== false) {
                // Contains Stop Word
                return $stopWord->action;
            }
        }

        return false;
    }
}
