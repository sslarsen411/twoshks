<?php

namespace App\Livewire;

use App\Models\Review;
use Livewire\Component;
use App\Traits\Assistant;
use Illuminate\Support\Facades\Cache;
use RealRashid\SweetAlert\Facades\Alert;

class Questions extends Component{
    use Assistant;
    public $question;
    public $answer='';
    public $dex = 0;
    public string $ask = '';
    public array $aiMsg = [];
    public $key = 1;    
    public function mount(){
        $initMsg = $this->createInitialPrompt(session('location.PID'));
     //   $this->createMessage($initMsg, TRUE);
        $questions = Cache::get('questions');       
        $this->question=($questions[$this->dex]);         
    }
    protected function rules() {
        return [
            'answer' => 'required|string|min:6',
        ];
    } 
    protected $messages = [
        'answer.required' => 'Please type something',
    ];    
    public function handleHelp(){
           $this->aiMsg[] = ['role' => 'user', 'content' => $this->ask];
           $this->aiMsg[] = ['role' => 'assistant', 'content' => ''];
           $this->ask = '';   
    }
    function updateAnswers(string| null $inAnsStr, string|int $dex, string $newAns){
        if($inAnsStr)
            $ansArr =  unserialize($inAnsStr);
        else
            $ansArr = [];
        $ansArr[$dex] =  $newAns;  
        return serialize($ansArr);
      }    
    
    public function submitForm(){
        $this->validate();
        $questions = Cache::get('questions');
        $review =  Review::find( session('reviewID')); 
        $currAns = $this->updateAnswers($review->answers, $this->dex, strip_tags($this->answer) ); 
        $review->update(['answers' => $currAns]);
        
        $this->dex++;
        if(($this->dex) <= 5):
            $this->answer = '';
            $this->question =  $this->question=($questions[$this->dex]);      
        else:         
            Alert::success('Congratulations! You&apos;re finished with the questions.', 'Now generate your review');      
            return $this->redirect('/review', navigate: true);
        endif;
    }
    public function render()
    {
        return view('livewire.questions');
    }
}
