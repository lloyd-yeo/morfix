<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\User;
use App\FaqQna;
use App\FaqTopic;

class FaqController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $faq_topics = FaqTopic::all();
        $question_and_answers = array();
        foreach ($faq_topics as $faq_topic) {
            $topic_id = $faq_topic->id;
            $topic_name = $faq_topic->topic;

            $faq_qnas = FaqQna::where('topic_id', $topic_id)->get();
            $question_and_answers[$topic_name] = $faq_qnas;
        }
        
        return view('faq', [
            'question_and_answers' => $question_and_answers,
        ]);
    }
    
    public function topic() {
        return view('faq.topic', [
        ]);
    }
    
    public function listQuestions(Request $request, $topic) {
        $topics = FaqTopic::where('topic', $topic);
        
    }

}
