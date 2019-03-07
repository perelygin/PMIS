<?php

use yii\db\Migration;

/**
 * Class m190306_142334_LinkTypes
 */
class m190306_142334_LinkTypes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
			$this->insert('LinkType', [
	            'idLinkType' => '1',
	            'LinkTypeName' => 'Конец - Начало',
		        ]);
			$this->insert('LinkType', [
	            'idLinkType' => '2',
	            'LinkTypeName' => 'Начало - Начало',
		        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->delete('LinkType', ['idLinkType' => 1]);
          $this->delete('LinkType', ['idLinkType' => 2]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190306_142334_LinkTypes cannot be reverted.\n";

        return false;
    }
    */
}
