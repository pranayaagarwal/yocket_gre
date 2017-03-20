<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GrePassages Model
 *
 * @property \Cake\ORM\Association\HasMany $GreQuestions
 *
 * @method \App\Model\Entity\GrePassage get($primaryKey, $options = [])
 * @method \App\Model\Entity\GrePassage newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GrePassage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GrePassage|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GrePassage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GrePassage[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GrePassage findOrCreate($search, callable $callback = null)
 */
class GrePassagesTable extends Table
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

        $this->table('gre_passages');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('GreQuestions', [
            'foreignKey' => 'gre_passage_id'
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
}
