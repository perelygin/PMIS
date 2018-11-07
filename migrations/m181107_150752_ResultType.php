<?php

use yii\db\Migration;

/**
 * Class m181107_150752_ResultType
 */
class m181107_150752_ResultType extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert('ResultType', [
            'idResultType' => '1',
            'ResultTypeName' => 'Экспертиза',
        ]);
        $this->insert('ResultType', [
            'idResultType' => '2',
            'ResultTypeName' => 'БФТЗ',
        ]);
        $this->insert('ResultType', [
            'idResultType' => '3',
            'ResultTypeName' => 'Программное обеспечение',
        ]);
        $this->insert('ResultType', [
            'idResultType' => '4',
            'ResultTypeName' => 'Прочее',
        ]);
       

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->delete('ResultType', ['idResultType' => 1]);
		$this->delete('ResultType', ['idResultType' => 2]);
		$this->delete('ResultType', ['idResultType' => 3]);
		$this->delete('ResultType', ['idResultType' => 4]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181107_150752_ResultType cannot be reverted.\n";

        return false;
    }
    */
}
