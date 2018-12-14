<?php

use yii\db\Migration;

/**
 * Class m181213_145003_LifeCycleStages_update_resulttype
 */
class m181213_145003_LifeCycleStages_update_resulttype extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->update('LifeCycleStages', ['idResultType' => 4],'idStage = 1');
		$this->update('LifeCycleStages', ['idResultType' => 1],'idStage = 2');
		$this->update('LifeCycleStages', ['idResultType' => 2],'idStage = 3');
		$this->update('LifeCycleStages', ['idResultType' => 3],'idStage = 4');
		$this->update('LifeCycleStages', ['idResultType' => 4],'idStage = 5');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181213_145003_LifeCycleStages_update_resulttype cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181213_145003_LifeCycleStages_update_resulttype cannot be reverted.\n";

        return false;
    }
    */
}
