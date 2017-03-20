<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GreOptions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $GreQuestions
 * @property \Cake\ORM\Association\HasMany $GreResponses
 *
 * @method \App\Model\Entity\GreOption get($primaryKey, $options = [])
 * @method \App\Model\Entity\GreOption newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GreOption[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GreOption|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GreOption patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GreOption[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GreOption findOrCreate($search, callable $callback = null)
 */
class GreOptionsTable extends Table
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

        $this->table('gre_options');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('GreQuestions', [
            'foreignKey' => 'gre_question_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('GreResponses', [
            'foreignKey' => 'gre_option_id'
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

        $validator
            ->boolean('is_answer')
            ->requirePresence('is_answer', 'create')
            ->notEmpty('is_answer');

        $validator
            ->requirePresence('label', 'create')
            ->notEmpty('label');

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

        return $rules;
    }
}
