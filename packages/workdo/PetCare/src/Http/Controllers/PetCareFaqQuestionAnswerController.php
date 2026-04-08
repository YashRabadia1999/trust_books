<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\Entities\PetCareFAQ;
use Workdo\PetCare\Entities\PetCareFaqQuestionAnswer;
use Workdo\PetCare\Events\SavedPetCareFaqQuestionAnswer;

class PetCareFaqQuestionAnswerController extends Controller
{
    public function showQuestionAnswerPage($faqId)
    {
        if (\Auth::user()->isAbleTo('petcare_faq add question & answer')) {
            try {
                $decryptedFAQsId = \Illuminate\Support\Facades\Crypt::decrypt($faqId);
                $faq = PetCareFAQ::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedFAQsId);
                if (!$faq || $faq->created_by != creatorId() || $faq->workspace != getActiveWorkSpace()) {
                    return redirect()->back()->with('error', __('FAQ not found.'));
                }
    
                $faqQuestionAnswers = $faq->questionAnswers()->get();
    
                return view('pet-care::system_setup.FAQ.question_answer', compact('faqId', 'faq', 'faqQuestionAnswers'));
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', $th->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    public function storeQuestionAnswer(Request $request, $faqId)
    {
        if (\Auth::user()->isAbleTo('petcare_faq add question & answer')) {
            try {
                $decryptedFAQsId = \Illuminate\Support\Facades\Crypt::decrypt($faqId);
                $faq = PetCareFAQ::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->find($decryptedFAQsId);
                if (!$faq || $faq->created_by != creatorId() || $faq->workspace != getActiveWorkSpace()) {
                    return redirect()->back()->with('error', __('FAQ not found.'));
                }

                $validator = Validator::make(
                    $request->all(),
                    [
                        'question'                      => 'required|array',
                        'question.*'                    => 'required|string',
                        'answer'                        => 'required|array',
                        'answer.*'                      => 'required|string',
                    ],
                    [
                        'question.*.required'      => 'The question field is required.',
                        'answer.*.required'        => 'The answer field is required.',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $submittedQueAnsIds = $request->que_ans_id ?? [];
                $existingQaIds = $faq->questionAnswers()->pluck('id')->toArray();
                $queAnsIdsToDelete = array_diff($existingQaIds, array_filter($submittedQueAnsIds));

                if (!empty($queAnsIdsToDelete)) {
                    PetCareFaqQuestionAnswer::where('faq_id', $faq->id)->whereIn('id', $queAnsIdsToDelete)->delete();
                }

                $created = false;
                $submittedQueAnswers = [];

                foreach ($request->question as $index => $questionText) {
                    $queAnsId = $submittedQueAnsIds[$index] ?? null;
                    if ($queAnsId) {

                        $QuestionAnswer = PetCareFaqQuestionAnswer::where('id', $queAnsId)->where('faq_id', $faq->id)->first();

                        if ($QuestionAnswer) {
                            
                            $QuestionAnswer->question   = $questionText;
                            $QuestionAnswer->answer     = $request->answer[$index] ?? null;
                            $QuestionAnswer->save();
                            
                            $submittedQueAnswers[] = $QuestionAnswer;
                        }
                    } else {
                        $QuestionAnswer                 = new PetCareFaqQuestionAnswer();
                        $QuestionAnswer->faq_id         = $faq->id;
                        $QuestionAnswer->question       = $questionText;
                        $QuestionAnswer->answer         = $request->answer[$index];
                        $QuestionAnswer->workspace      = getActiveWorkSpace();
                        $QuestionAnswer->created_by     = creatorId();
                        $QuestionAnswer->save();

                        $submittedQueAnswers[] = $QuestionAnswer;
                        $created = true;
                    }
                }

                event(new SavedPetCareFaqQuestionAnswer($request, $submittedQueAnswers, $faq));
                $message = $created ? __('The question & answer has been created successfully.') : __('The question & answer are updated successfully.');

                return redirect()->back()->with('success', $message);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }
}
