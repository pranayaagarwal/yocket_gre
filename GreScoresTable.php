<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * GreScores Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $GreTests
 *
 * @method \App\Model\Entity\GreScore get($primaryKey, $options = [])
 * @method \App\Model\Entity\GreScore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GreScore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GreScore|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GreScore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GreScore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GreScore findOrCreate($search, callable $callback = null)
 */
class GreScoresTable extends Table
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

        $this->table('gre_scores');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('GreTests', [
            'foreignKey' => 'gre_test_id',
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
            ->integer('verbal_score')
            ->requirePresence('verbal_score', 'create')
            ->notEmpty('verbal_score');

        $validator
            ->integer('quant_score')
            ->requirePresence('quant_score', 'create')
            ->notEmpty('quant_score');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['gre_test_id'], 'GreTests'));

        return $rules;
    }


    public function scorecal($user_id,$test_id,$section,$marks)
    {
      $scoresTable = TableRegistry::get('GreScores');
      $score = $scoresTable->newEntity();

      $user = $this->find()
                    ->where(['GreScores.user_id' => $user_id,'GreScores.gre_test_id' => $test_id])
                    ->first();
      $score->gre_test_id=$test_id;
      $score->user_id=$user_id;
      if($section == '1js')
      $score->verbal1_score = $marks;
      if($section == '2js')
      $score->quant1_score = $marks;
      if($section == '3js')
      $score->verbal2_score = $marks;
      if($section == '4js')
      $score->quant2_score = $marks;
      if(empty($user))
      {
            if ($this->save($score))
            {
            return $score;
            }
      }
      if(!empty($user))
      {
          $score=$score->toArray();
          $scoresTable->patchEntity($user, $score);
          if($this->save($user))
          {
              return $score;
          }
      }
    }
}
