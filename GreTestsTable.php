<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GreTests Model
 *
 * @property \Cake\ORM\Association\HasMany $GreQuestions
 * @property \Cake\ORM\Association\HasMany $GreScores
 *
 * @method \App\Model\Entity\GreTest get($primaryKey, $options = [])
 * @method \App\Model\Entity\GreTest newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GreTest[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GreTest|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GreTest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GreTest[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GreTest findOrCreate($search, callable $callback = null)
 */
class GreTestsTable extends Table
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

        $this->table('gre_tests');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->hasMany('GreQuestions', [
            'foreignKey' => 'gre_test_id'
        ]);
        $this->hasMany('GreScores', [
            'foreignKey' => 'gre_test_id'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        return $validator;
    }
}
