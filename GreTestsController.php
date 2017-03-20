<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\Network\Exception\NotFoundException;
/**
 * GreTests Controller
 *
 * @property \App\Model\Table\GreTestsTable $GreTests
 */
class GreTestsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->request->session()->write('Auth.user.id','1');
        $section=1;
        $user_id=$this->request->session()->read('Auth.user.id');
        $this->request->session()->write('section',$section);
        $greTests = $this->paginate($this->GreTests);
        $test_ids=$greTests->extract('id')->toArray();
        $greTests=$greTests->toArray();     //to paginate Tests
        $this->set(compact('greTests'));
        $this->set('_serialize', ['greTests']);
        $this->set('section',$section);
        $this->set('test_ids',$test_ids);
        $query=$this->GreTests->GreQuestions->GreResponses->Users->GreScores->find()
                                                                            ->where(['GreScores.user_id'=>$user_id,'GreScores.gre_test_id IN'=>$test_ids])
                                                                            ->toArray();
        $this->set('query',$query);
    }

    /**
     * View method
     *
     * @param string|null $id Gre Test id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null,$section=0)
    {
        $this->request->session()->write('section', $section);
        if($section == 1 || $section == 2)
        $difficulty=array(3);


        $user_id=$this->request->session()->read('Auth.user.id');
        $check=$this->GreTests->GreQuestions->GreResponses->Users->GreScores->find()
                                                                  ->where(['user_id' => $user_id],['gre_test_id' => $id])
                                                                  ->toArray();
        if($section == 3)
        {
        if($check[0]->verbal1_score < 78)//65 + "13" questions right to get diificulty level  4, 5
          $difficulty=array(1,2);
        else
          $difficulty=array(4,5);
        }

        if($section == 4)
        {
        if($check[0]->quant1_score < 78)//65 + "13" questions right to get diificulty level  4, 5
          $difficulty=array(1,2);
        else
          $difficulty=array(4,5);
        }
        $questionIds = $this->GreTests->GreQuestions->find()        //find questions for a particular section
                                              ->where(['GreQuestions.section'=>$section])
                                              ->where(['GreQuestions.gre_test_id'=>$id])
                                              ->where(['GreQuestions.difficulty IN' =>$difficulty])
                                              ->all();
        $this->set('section',$section);
        $questionIds = $questionIds->extract('id')->toArray();      //extract all question ids into a array
        $this->request->session()->write('questionIds', $questionIds);    //write all questions
        $this->set('questionIds',$questionIds);
        $this->request->session()->write('test_id',$id);

    }


}
