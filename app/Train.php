<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TrainProblem;
use App\Submission;

class Train extends Model
{
    protected $table = "train_info";
    protected $primaryKey = "train_id";

    public function getUserChapter($uid)
    {
        $train_id = $this->train_id;
        $trainingObj = Train::where('train_id', $train_id)->first();
        $chapter_in = 1;
        for($i = 1; $i <= $trainingObj->train_chapter; $i++)
        {
            $trainingProblemObj = TrainProblem::where([
                'train_id' => $train_id,
                'chapter_id' => $i
            ])->get();
            if(isset($trainingProblemObj) && $chapter_in == $i)
                $checkChapterAc = 1;
            $problem_num = 0;
            foreach($trainingProblemObj as $trainingProblem)
            {
                if(!$trainingProblem->problem->getNumberOfUsedContests())
                {
                    $submissionObj = Submission::select('uid')->where([
                        'uid' => $uid,
                        'pid' => $trainingProblem->problem_id,
                        'result' => 'Accepted'
                    ])->orderby('runid','asc')->first();
                    if(isset($submissionObj) && $checkChapterAc == 1)
                        $checkChapterAc = 1;
                    else
                        $checkChapterAc = 0;
                }
            }
            if($checkChapterAc == 1)
                $chapter_in = $i + 1;
        }
        return $chapter_in;
    }
}
