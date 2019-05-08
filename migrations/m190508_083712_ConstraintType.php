<?php

use yii\db\Migration;

/**
 * Class m190508_083712_ConstraintType
 */
class m190508_083712_ConstraintType extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert('ConstraintType', [
            'idConstrType' => '1',
            'ConstrTypeName' => 'Начало не ранее',
        ]);
        $this->insert('ConstraintType', [
            'idConstrType' => '2',
            'ConstrTypeName' => 'Окончание не позже',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('ConstraintType', ['idConstrType' => 1]);
        $this->delete('ConstraintType', ['idConstrType' => 2]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190508_083712_ConstraintType cannot be reverted.\n";

        return false;
    }
    */
}
