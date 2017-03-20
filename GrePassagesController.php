<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * GrePassages Controller
 *
 * @property \App\Model\Table\GrePassagesTable $GrePassages
 */
class GrePassagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $grePassages = $this->paginate($this->GrePassages);

        $this->set(compact('grePassages'));
        $this->set('_serialize', ['grePassages']);
    }

    /**
     * View method
     *
     * @param string|null $id Gre Passage id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $grePassage = $this->GrePassages->get($id, [
            'contain' => ['GreQuestions']
        ]);

        $this->set('grePassage', $grePassage);
        $this->set('_serialize', ['grePassage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $grePassage = $this->GrePassages->newEntity();
        if ($this->request->is('post')) {
            $grePassage = $this->GrePassages->patchEntity($grePassage, $this->request->data);
            if ($this->GrePassages->save($grePassage)) {
                $this->Flash->success(__('The gre passage has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The gre passage could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('grePassage'));
        $this->set('_serialize', ['grePassage']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Gre Passage id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $grePassage = $this->GrePassages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $grePassage = $this->GrePassages->patchEntity($grePassage, $this->request->data);
            if ($this->GrePassages->save($grePassage)) {
                $this->Flash->success(__('The gre passage has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The gre passage could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('grePassage'));
        $this->set('_serialize', ['grePassage']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Gre Passage id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $grePassage = $this->GrePassages->get($id);
        if ($this->GrePassages->delete($grePassage)) {
            $this->Flash->success(__('The gre passage has been deleted.'));
        } else {
            $this->Flash->error(__('The gre passage could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
