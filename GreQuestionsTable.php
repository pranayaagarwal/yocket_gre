<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GreQuestions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $GreTests
 * @property \Cake\ORM\Association\BelongsTo $GrePassages
 * @property \Cake\ORM\Association\HasMany $GreOptions
 * @property \Cake\ORM\Association\HasMany $GreResponses
 *
 * @method \App\Model\Entity\GreQuestion get($primaryKey, $options = [])
 * @method \App\Model\Entity\GreQuestion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GreQuestion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GreQuestion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GreQuestion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GreQuestion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GreQuestion findOrCreate($search, callable $callback = null)
 */
class GreQuestionsTable extends Table
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

        $this->table('gre_questions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('GreTests', [
            'foreignKey' => 'gre_test_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('GrePassages', [
            'foreignKey' => 'gre_passage_id',
            'joinType' => 'LEFT'
        ]);
        $this->hasMany('GreOptions', [
            'foreignKey' => 'gre_question_id'
        ]);
        $this->hasMany('GreResponses', [
            'foreignKey' => 'gre_question_id'
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
            ->integer('difficulty')
            ->requirePresence('difficulty', 'create')
            ->notEmpty('difficulty');

        $validator
            ->integer('section')
            ->requirePresence('section', 'create')
            ->notEmpty('section');

        $validator
            ->integer('response_type')
            ->requirePresence('response_type', 'create')
            ->notEmpty('response_type');

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
        $rules->add($rules->existsIn(['gre_test_id'], 'GreTests'));
        $rules->add($rules->existsIn(['gre_passage_id'], 'GrePassages'));

        return $rules;
    }
}
