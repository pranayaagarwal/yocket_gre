<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GreResponses Model
 *
 * @property \Cake\ORM\Association\BelongsTo $GreQuestions
 * @property \Cake\ORM\Association\BelongsTo $GreOptions
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\GreResponse get($primaryKey, $options = [])
 * @method \App\Model\Entity\GreResponse newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GreResponse[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GreResponse|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GreResponse patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GreResponse[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GreResponse findOrCreate($search, callable $callback = null)
 */
class GreResponsesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('gre_responses');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('GreQuestions', [
            'foreignKey' => 'gre_question_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('GreOptions', [
            'foreignKey' => 'gre_option_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('text', 'create')
            ->notEmpty('text');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['gre_question_id'], 'GreQuestions'));
        $rules->add($rules->existsIn(['gre_option_id'], 'GreOptions'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
