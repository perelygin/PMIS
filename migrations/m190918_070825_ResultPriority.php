<?php

use yii\db\Migration;

/**
 * Class m190918_070825_ResultPriority
 */
class m190918_070825_ResultPriority extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert('ResultPriority', [
				'idResultPriority' => 1,
	            'ResultPriorityOrder' => '0',
	            'ResultPriorityName' => 'Без приоритета',
	        ]);
	    $this->insert('ResultPriority', [
				'idResultPriority' => 2,
	            'ResultPriorityOrder' => '1',
	            'ResultPriorityName' => 'Приоритет 1',
	        ]); 
	    $this->insert('ResultPriority', [
				'idResultPriority' => 3,
	            'ResultPriorityOrder' => '2',
	            'ResultPriorityName' => 'Приоритет 2',
	        ]); 
        $this->insert('ResultPriority', [
			'idResultPriority' => 4,
            'ResultPriorityOrder' => '3',
            'ResultPriorityName' => 'Приоритет 3',
        ]); 
        $this->insert('ResultPriority', [
			'idResultPriority' => 5,
            'ResultPriorityOrder' => '4',
            'ResultPriorityName' => 'Приоритет4',
        ]); 
        $this->insert('ResultPriority', [
			'idResultPriority' => 6,
            'ResultPriorityOrder' => '5',
            'ResultPriorityName' => 'Приоритет 5',
        ]); 
        $this->insert('ResultPriority', [
			'idResultPriority' => 7,
            'ResultPriorityOrder' => '6',
            'ResultPriorityName' => 'Приоритет 6',
        ]); 
        $this->insert('ResultPriority', [
			'idResultPriority' => 8,
            'ResultPriorityOrder' => '7',
            'ResultPriorityName' => 'Приоритет 7',
        ]); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('ResultPriority', ['idResultPriority' => 1]);    
        $this->delete('ResultPriority', ['idResultPriority' => 2]);    
        $this->delete('ResultPriority', ['idResultPriority' => 8]);    
        $this->delete('ResultPriority', ['idResultPriority' => 3]);    
        $this->delete('ResultPriority', ['idResultPriority' => 4]);    
        $this->delete('ResultPriority', ['idResultPriority' => 5]);    
        $this->delete('ResultPriority', ['idResultPriority' => 6]); 
        $this->delete('ResultPriority', ['idResultPriority' => 7]);       
        }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190918_070825_ResultPriority cannot be reverted.\n";

        return false;
    }
    */
}
