<?php

use yii\db\Migration;

/**
 * Class m181107_152940_ResultStatus
 */
class m181107_152940_ResultStatus extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
			$this->insert('ResultStatus', [
	            'idResultStatus' => '1',
	            'ResultStatusName' => 'В ожидании',
	        ]);
	        $this->insert('ResultStatus', [
	            'idResultStatus' => '2',
	            'ResultStatusName' => 'В работе',
	        ]);
	        $this->insert('ResultStatus', [
	            'idResultStatus' => '3',
	            'ResultStatusName' => 'Выполнен',
	        ]);
	        $this->insert('ResultStatus', [
	            'idResultStatus' => '4',
	            'ResultStatusName' => 'Приостановлен',
	        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('ResultStatus', ['idResultStatus' => 1]);
        $this->delete('ResultStatus', ['idResultStatus' => 2]);
        $this->delete('ResultStatus', ['idResultStatus' => 3]);
        $this->delete('ResultStatus', ['idResultStatus' => 4]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181107_152940_ResultStatus cannot be reverted.\n";

        return false;
    }
    */
}
