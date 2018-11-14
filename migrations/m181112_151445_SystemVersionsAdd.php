<?php

use yii\db\Migration;

/**
 * Class m181112_151445_SystemVersionsAdd
 */
class m181112_151445_SystemVersionsAdd extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert('SystemVersions', [
	            'idsystem_versions' => '1',
	            'version_number' => '23',
	            'release_date' => '2018-11-30',
	            'commit_ date' => '2018-11-26',
	        ]);
	        
	    $this->insert('SystemVersions', [
	            'idsystem_versions' => '2',
	            'version_number' => '24',
	            'release_date' => '2019-01-18',   
	            'commit_ date' => '2019-01-14',
	        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('SystemVersions', ['idsystem_versions' => 1]);

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181112_151445_SystemVersionsAdd cannot be reverted.\n";

        return false;
    }
    */
}
