<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Network\Exception\NotFoundException;
/**
 * GreQuestions Controller
 *
 * @property \App\Model\Table\GreQuestionsTable $GreQuestions
 */
class GreQuestionsController extends AppController
{
    public function view($q_id = null)
    {
      $query = $this->GreQuestions->find()
                        ->where(['GreQuestions.id'=>$q_id])
                        ->contain(['GreTests','GreOptions']);

       $user_id=$this->request->session()->read('Auth.user.id');
      if ($query->isEmpty())            //to check if question exists
      {
        throw new NotFoundException(__('Question not found'));
      }
      $question=$query->first();

      if(!empty($question->gre_passage_id))
      {
        $passage=$this->GreQuestions->GrePassages->findById($question->gre_passage_id)
                                   ->select(['GrePassages.text'])
                                   ->toArray();
         $this->set('passage',$passage[0]->text);
      }

      $duplicate= $this->GreQuestions->GreOptions->GreResponses->find()        // to find all responses in response table with same question id
                                  ->select(['GreResponses.gre_option_id','GreResponses.text','GreResponses.is_marked'])
                                  ->where(['GreResponses.gre_question_id' => $q_id,'GreResponses.user_id'=>$user_id])
                                  ->all()
                                  ->toArray();

      $this->set('q_id',$q_id);
      $this->set('greQuestion', $question);
      $this->set('flag',$duplicate);
      $this->set('_serialize', ['greQuestion']);

    }


}
