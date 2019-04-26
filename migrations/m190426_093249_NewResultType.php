<?php

use yii\db\Migration;

/**
 * Class m190426_093249_NewResultType
 */
class m190426_093249_NewResultType extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		 $this->insert('ResultType', [
            'idResultType' => '5',
            'ResultTypeName' => 'Абонемент',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('ResultType', ['idResultType' => 5]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190426_093249_NewResultType cannot be reverted.\n";

        return false;
    }
    */
}
