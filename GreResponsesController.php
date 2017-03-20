<?php
namespace App\Controller;

use App\Controller\AppController;

class GreResponsesController extends AppController
{
      public function viewall($current_ques)
      {
        $questionIds=$this->request->session()->read('questionIds');
        $questions=$this->GreResponses->GreQuestions->find()
                                                    ->where(['GreQuestions.id IN'=>$questionIds])
                                                    ->toArray();
        $user_id=$this->request->session()->read('Auth.user.id');
        $marked=$this->GreResponses->find()
                                        ->distinct(['GreResponses.gre_question_id'])
                                        ->where(['GreResponses.gre_question_id IN'=>$questionIds,'GreResponses.user_id'=>$user_id])
                                        ->toArray();
        $this->set('marked',$marked);
        $this->set('questions',$questions);
        $this->set('current_ques',$current_ques);//question from were you enter "viewall question"
      }

      public function mark($ques_id)
      {
        $questionIds=$this->request->session()->read('questionIds');
        $user_id=$this->request->session()->read('Auth.user.id');
        $questions=$this->GreResponses->GreQuestions->find()
                                                    ->where(['GreQuestions.id IN'=>$questionIds])
                                                    ->select(['GreQuestions.id','GreQuestions.text'])
                                                    ->toArray();
        $marked=$this->GreResponses->find()
                                  ->distinct(['GreResponses.gre_question_id'])
                                  ->where(['GreResponses.gre_question_id IN'=>$questionIds,'GreResponses.user_id'=>$user_id])
                                  ->toArray();

        $this->set('marked',$marked);
        $this->set('questions',$questions);
        $this->set('ques_id',$ques_id);//question from were you enter "marked question"
      }






      public function score()
      {
          $user_id=$this->request->session()->read('Auth.user.id');
          $questionIds=$this->request->session()->read('questionIds');
          $section=$this->request->session()->read('section');
          $test_id=$this->request->session()->read('test_id');
          $response_r=$this->GreResponses->find()//all responses for questions in a section-- radio buttons
                                    ->contain(['GreOptions','GreQuestions'])
                                    ->where(['GreResponses.gre_question_id IN'=>$questionIds,'GreQuestions.response_type'=>1,'GreResponses.user_id'=>$user_id])
                                    ->toArray();

          $score=0;
          foreach ($response_r as $res)
          {
            if(!empty($res->gre_option) && $res->gre_option->is_answer == true && $res->gre_question->response_type == 1)
            $score++;
          }
          //end of radio score
          $response_c=$this->GreResponses->find()//all questionids for check box
                                    ->contain(['GreOptions','GreQuestions'])
                                    ->where(['GreResponses.gre_question_id IN'=>$questionIds,'GreQuestions.response_type'=>2,'GreResponses.user_id'=>$user_id])
                                    ->distinct(['GreResponses.gre_question_id'])
                                    ->toArray();
          foreach($response_c as $r)
          {
            $correct_res=$this->GreResponses->GreOptions->find()        //correct responses
                                                       ->where(['GreOptions.gre_question_id'=>$r->gre_question_id,'GreOptions.is_answer'=> true])
                                                       ->select(['GreOptions.id'])
                                                       ->toArray();
            $response=$this->GreResponses->find() //all inputs for check box
                                           ->contain(['GreOptions','GreQuestions'])
                                           ->where(['GreOptions.gre_question_id'=>$r->gre_question_id,'GreResponses.user_id'=>$user_id])
                                           ->select(['GreResponses.gre_option_id'])
                                           ->toArray();

            foreach ($correct_res as $key=>$value) {
                  $correct_res[$key]= $value->id;

            }
            foreach ($response as $key=>$value) {
                $response[$key]= $value->gre_option_id;
            }
            if(is_array($response) && is_array($correct_res))
            {
            $result= array_intersect($response,$correct_res);
            if(sizeof($result)==sizeof($response))
            $score++;
          }

         }// end of check box score calculation
         $options_text=$this->GreResponses->GreOptions->find()    //all options having response type 3
                                                      ->contain(['GreQuestions'])
                                                      ->where(['GreOptions.gre_question_id IN'=>$questionIds])
                                                      ->where(['GreQuestions.response_type' => 3])
                                                      ->toArray();
          $response_text=$this->GreResponses->find()
                                  ->contain(['GreQuestions'])
                                  ->where(['GreResponses.gre_question_id IN'=>$questionIds,'GreResponses.user_id'=>$user_id])
                                  ->where(['GreQuestions.response_type' => 3])
                                  ->toArray();
          if(!empty($options_text) && !empty($response_text)){
          $i=0;
          for($i=0;$i<count($response_text);$i++){
          if(!strcasecmp($response_text[0]->text,$options_text[0]->text))
          {
            $score++;
          }

         }
        }
        $marks= 65 + $score;
        $score = $this->GreResponses->Users->GreScores->scorecal($user_id,$test_id,$section,$marks);
      }

      public function add($ques_id)
      {
          $user_id=$this->request->session()->read('Auth.user.id');
          $questionIds=$this->request->session()->read('questionIds');
          $requestdata= $this->GreResponses->newEntity($this->request->data);
          $response=$this->GreResponses->newEntity();
          $test_id=$this->request->session()->read('test_id');
          $next = $requestdata->next;

          $section=$this->request->session()->read('section');


          $duplicate= $this->GreResponses->find()        // to find all responses in response table with same question id
                                      ->select(['id'])
                                      ->where(['GreResponses.gre_question_id' => $ques_id,'GreResponses.user_id'=> $user_id])
                                      ->all()
                                      ->toArray();


          $check=$this->GreResponses->GreQuestions->find() //get response type
                                                  ->select(['response_type'])
                                                  ->where(['GreQuestions.id' => $ques_id])
                                                  ->first();


        if($check->response_type=='1')
        {
          $response=$requestdata->option_id;
            if(empty($duplicate))
            {
                    $answer = $this->GreResponses->newEntity();
                    $answer->gre_option_id=$response;
                    $answer->gre_question_id=$ques_id;
                    $answer->user_id=$user_id;
                    $answer->is_marked=$requestdata->is_marked;
                    if ($this->request->is('post'))
                    {
                        if ($this->GreResponses->save($answer))
                        {
                                $this->Flash->success(__('Your answer has been saved.'));
                                if(array_search($next,$questionIds))
                                   return $this->redirect(['controller'=>'gre_questions','action' => 'view',$next]);
                                else
                                {
                                  return $this->redirect(['controller'=>'gre_responses','action'=>'view',$test_id,$section]);
                                }
                        }
                        else
                            $this->Flash->error(__('The answer could not be saved. Please, try again.'));
                    }
              }//closing first time save
            else
            {
                    $ans = $this->GreResponses->get($duplicate[0]->id);
                    $answer = $this->GreResponses->newEntity();
                    $answer->gre_option_id=$response;
                    $answer->gre_question_id=$ques_id;
                    $answer->user_id=$user_id;
                    $answer->is_marked=$requestdata->is_marked;
                    $answer=$answer->toArray();
                    if ($this->request->is('post'))
                    {
                        $this->GreResponses->patchEntity($ans, $answer);
                        if ($this->GreResponses->save($ans))
                        {
                              $this->Flash->success(__('Your answer has been updated.'));
                              if(array_search($next,$questionIds))
                                  return $this->redirect(['controller'=>'gre_questions','action' => 'view',$next]);
                              else
                                  return $this->redirect(['controller'=>'gre_responses','action'=>'view',$test_id,$section]);
                        }
                        else
                            $this->Flash->error(__('Unable to update your answer.'));
                    }
                }//closing update
        }//closing radio button
        if($check->response_type == '2')
        {
          $i=0;
          $response=$requestdata->option_id;
            if(empty($duplicate))
            {
                $k=count($response);
                echo $k;
                do{
                    $answer = $this->GreResponses->newEntity();
                    $answer->gre_option_id=$response[$i];
                    $answer->gre_question_id=$ques_id;
                    $answer->user_id=$user_id;
                    $answer->is_marked=$requestdata->is_marked;
                    if ($this->request->is('post'))
                    {
                        if ($this->GreResponses->save($answer))
                        {
                            if($k == 0 || $i==($k-1))
                            {
                                $this->Flash->success(__('Your answer has been saved.'));
                                if(array_search($next,$questionIds))
                                    return $this->redirect(['controller'=>'gre_questions','action' => 'view',$next]);
                                else
                                    return $this->redirect(['controller'=>'gre_responses','action'=>'view',$test_id,$section]);
                            }
                        }
                        else
                            $this->Flash->error(__('The answer could not be saved. Please, try again.'));
                    }$i++;
                  }while($i<$k);
            }
            else
            {
                $i=0;
                if(count($duplicate) >= count($response))
                $k=count($duplicate);
                else
                $k=count($response);
                do{
                    $answer = $this->GreResponses->newEntity();
                    if(!empty($response[$i]))
                    $answer->gre_option_id=$response[$i];
                    else
                    $answer->gre_option_id=NULL;
                    $answer->gre_question_id=$ques_id;
                    $answer->user_id=$user_id;
                    $answer->is_marked=$requestdata->is_marked;
                    if ($this->request->is('post'))
                    {
                        if(!empty($duplicate[$i]->id))//duplicate value updated
                        {
                        $ans = $this->GreResponses->get($duplicate[$i]->id);
                        $answer=$answer->toArray();
                        $this->GreResponses->patchEntity($ans, $answer);
                        if ($this->GreResponses->save($ans))
                        {
                          if($k==0 || $i==($k-1))
                          {
                              $this->Flash->success(__('Your answer has been updated.'));
                              if(array_search($next,$questionIds))
                                  return $this->redirect(['controller'=>'gre_questions','action' => 'view',$next]);
                              else
                                  return $this->redirect(['controller'=>'gre_responses','action'=>'view',$test_id,$section]);
                          }
                        }
                        else
                            $this->Flash->error(__('Unable to update your answer.'));
                          }
                          else//extra value to be added
                          {
                            if ($this->GreResponses->save($answer))
                            {
                                if($k==0 || $i==($k-1))
                                {
                                    $this->Flash->success(__('Your answer has been saved.'));
                                    if(array_search($next,$questionIds))
                                        return $this->redirect(['controller'=>'gre_questions','action' => 'view',$next]);
                                    else
                                        return $this->redirect(['controller'=>'gre_responses','action'=>'view',$test_id,$section]);
                                }
                            }
                            else
                                $this->Flash->error(__('The answer could not be saved. Please, try again.'));
                          }
                    }$i++;
              }while($i<$k);
            }
        }//closing checkbox
        if($check->response_type == '3')
        {

          $response=$requestdata->text;

          if(empty($duplicate))
           {
              $answer = $this->GreResponses->newEntity();
              $answer->text=$response;
              $answer->gre_question_id=$ques_id;
              $answer->user_id=$user_id;
              $answer->is_marked=$requestdata->is_marked;
              if ($this->request->is('post'))
              {
                  if ($this->GreResponses->save($answer))
                  {
                          $this->Flash->success(__('Your answer has been saved.'));
                          if(array_search($next,$questionIds))
                             return $this->redirect(['controller'=>'gre_questions','action' => 'view',$next]);
                          else
                              return $this->redirect(['controller'=>'gre_responses','action'=>'view',$test_id,$section]);
                  }
                  else
                      $this->Flash->error(__('The answer could not be saved. Please, try again.'));
              }
          }
          else
          {
              $ans = $this->GreResponses->get($duplicate[0]->id);
              $answer = $this->GreResponses->newEntity();
              $answer->text=$response;
              $answer->gre_question_id=$ques_id;
              $answer->user_id=$user_id;
              $answer->is_marked=$requestdata->is_marked;
              $answer=$answer->toArray();
              if ($this->request->is('post'))
              {
                  $this->GreResponses->patchEntity($ans, $answer);
                  if ($this->GreResponses->save($ans))
                  {
                        $this->Flash->success(__('Your answer has been updated.'));
                        if(array_search($next,$questionIds))
                            return $this->redirect(['controller'=>'gre_questions','action' => 'view',$next]);
                        else
                            return $this->redirect(['controller'=>'gre_responses','action'=>'view',$test_id,$section]);
                  }
                  else
                      $this->Flash->error(__('Unable to update your answer.'));
             }
        }
      }
    }// closing of add function




  public function view($test_id,$section)
  {

    $questionIds=$this->request->session()->read('questionIds');
    $marked=$this->GreResponses->find()  //responses with distinct question ids
                              ->distinct(['GreResponses.gre_question_id'])
                              ->where(['GreResponses.gre_question_id IN'=>$questionIds])
                              ->toArray();
                              $this->set('marked',$marked);
                              $this->set('test_id',$test_id);

    $questions=$this->GreResponses->GreQuestions->find()
                                                ->where(['GreQuestions.id IN'=>$questionIds])
                                                ->toArray();
    $this->set('questions',$questions);
  }// closing of view



  public function finalpage()
  {
    $user_id=$this->request->session()->read('Auth.user.id');
    $test_id=$this->request->session()->read('test_id');

    $query=$this->GreResponses->Users->GreScores->find()
                                                ->where(['user_id'=>$user_id,'gre_test_id'=>$test_id])
                                                ->first();

                                                $verbal= $query->verbal1_score + $query->verbal2_score;
                                                if($verbal < 130)
                                                $verbal=130;

                                                $quant=$query->quant1_score + $query->quant2_score;
                                                if($quant < 130)
                                                $quant=130;

     $total=$verbal + $quant;
     $this->set('total',$total);
    $this->set('query',$query);
    $this->set('verbal',$verbal);
    $this->set('quant',$quant);
  }
}
