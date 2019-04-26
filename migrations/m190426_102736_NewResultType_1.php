<?php

use yii\db\Migration;

/**
 * Class m190426_102736_NewResultType_1
 */
class m190426_102736_NewResultType_1 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert('ResultType', [
            'idResultType' => '6',
            'ResultTypeName' => 'Внутренний тест',
        ]);
        $this->insert('ResultType', [
            'idResultType' => '7',
            'ResultTypeName' => 'МТ банка',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('ResultType', ['idResultType' => 6]);
        $this->delete('ResultType', ['idResultType' => 7]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190426_102736_NewResultType_1 cannot be reverted.\n";

        return false;
    }
    */
}
