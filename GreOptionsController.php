<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * GreOptions Controller
 *
 * @property \App\Model\Table\GreOptionsTable $GreOptions
 */
class GreOptionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['GreQuestions']
        ];
        $greOptions = $this->paginate($this->GreOptions);

        $this->set(compact('greOptions'));
        $this->set('_serialize', ['greOptions']);
    }

    /**
     * View method
     *
     * @param string|null $id Gre Option id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $greOption = $this->GreOptions->get($id, [
            'contain' => ['GreQuestions', 'GreResponses']
        ]);

        $this->set('greOption', $greOption);
        $this->set('_serialize', ['greOption']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $greOption = $this->GreOptions->newEntity();
        if ($this->request->is('post')) {
            $greOption = $this->GreOptions->patchEntity($greOption, $this->request->data);
            if ($this->GreOptions->save($greOption)) {
                $this->Flash->success(__('The gre option has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The gre option could not be saved. Please, try again.'));
            }
        }
        $greQuestions = $this->GreOptions->GreQuestions->find('list', ['limit' => 200]);
        $this->set(compact('greOption', 'greQuestions'));
        $this->set('_serialize', ['greOption']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Gre Option id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $greOption = $this->GreOptions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $greOption = $this->GreOptions->patchEntity($greOption, $this->request->data);
            if ($this->GreOptions->save($greOption)) {
                $this->Flash->success(__('The gre option has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The gre option could not be saved. Please, try again.'));
            }
        }
        $greQuestions = $this->GreOptions->GreQuestions->find('list', ['limit' => 200]);
        $this->set(compact('greOption', 'greQuestions'));
        $this->set('_serialize', ['greOption']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Gre Option id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $greOption = $this->GreOptions->get($id);
        if ($this->GreOptions->delete($greOption)) {
            $this->Flash->success(__('The gre option has been deleted.'));
        } else {
            $this->Flash->error(__('The gre option could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
